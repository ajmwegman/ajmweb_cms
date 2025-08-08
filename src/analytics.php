<?php
class AdvancedAnalytics {

    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    
    public function startSession() {
        if (!isset($_SESSION['session_start_time'])) {
            $_SESSION['session_start_time'] = time();
        }
    }
    
    private function getCountry($ipAddress) {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://api.ipstack.com/$ipAddress?access_key=" . IPSTACK_API_KEY);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $output = curl_exec($ch);
            curl_close($ch);
            
            if ($output === false) {
                return 'UNKNOWN';
            }
            
            $outputData = json_decode($output, true);
            return $outputData['country_code'] ?? 'UNKNOWN';
        } catch (Exception $e) {
            return 'UNKNOWN';
        }
    }

   public function recordVisit() {
    try {
        $sessionId = session_id();
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $countryCode = $this->getCountry($ipAddress);
        
        // Referer
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'unknown';
        
        // Browser detectie
        $browser = 'unknown';
        if (strpos($userAgent, 'Edge') !== false) {
            $browser = 'Edge';
        } elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) {
            $browser = 'Opera';
        } elseif (strpos($userAgent, 'Chrome') !== false && strpos($userAgent, 'Safari') !== false) {
            $browser = 'Chrome';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            $browser = 'Safari';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            $browser = 'Firefox';
        } elseif (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
            $browser = 'Internet Explorer';
        }

        // Skip recording if no valid browser or referer
        if ($browser === 'unknown' && $referer === 'unknown') {
            return;
        }
        
        // Verbeterde device detectie
        $isMobile = false;
        
        // Tablet detectie
        if (preg_match('/iPad|Android.*Tablet|PlayBook|Silk|Kindle/', $userAgent)) {
            $isMobile = true;
        }
        // Mobile detectie (exclusief tablets)
        elseif (preg_match('/Mobile|Android|iPhone|BlackBerry|Windows Phone/', $userAgent)) {
            $isMobile = true;
        }
        
        // Huidige pagina URL
        $pageUrl = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Check if analytics table exists and has required columns
        $this->ensureAnalyticsTable();
        
        // Check for existing visit from same IP and user agent
        $stmt = $this->pdo->prepare("SELECT id, page_views FROM analytics WHERE ip_address = ? AND user_agent = ? LIMIT 1");
        $stmt->execute([$ipAddress, $userAgent]);
        $row = $stmt->fetch();
        
        if ($row) {
            // Update existing record
            $stmt = $this->pdo->prepare("UPDATE analytics SET page_views = page_views + 1, bounced = 0, page_url = ?, visit_time = NOW() WHERE id = ?");
            $stmt->execute([$pageUrl, $row['id']]);
        } else {
            // Insert new record
            $stmt = $this->pdo->prepare("INSERT INTO analytics (session_id, ip_address, user_agent, country_code, referer_url, browser, is_mobile, session_start, visit_time, page_url, page_views, bounced) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?, 1, 0)");
            $stmt->execute([$sessionId, $ipAddress, $userAgent, $countryCode, $referer, $browser, $isMobile, $pageUrl]);
        }
    } catch (Exception $e) {
        // Log error but don't break the application
        error_log("Analytics recording error: " . $e->getMessage());
    }
}

    private function ensureAnalyticsTable() {
        try {
            // Check if analytics table exists
            $stmt = $this->pdo->prepare("SHOW TABLES LIKE 'analytics'");
            $stmt->execute();
            
            if ($stmt->rowCount() == 0) {
                // Create analytics table
                $sql = "CREATE TABLE analytics (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    session_id VARCHAR(255),
                    ip_address VARCHAR(45),
                    user_agent TEXT,
                    country_code VARCHAR(10),
                    referer_url TEXT,
                    browser VARCHAR(50),
                    is_mobile TINYINT(1) DEFAULT 0,
                    session_start DATETIME,
                    visit_time DATETIME,
                    page_url VARCHAR(500),
                    page_views INT DEFAULT 1,
                    bounced TINYINT(1) DEFAULT 0,
                    session_duration INT DEFAULT 0,
                    INDEX idx_ip_useragent (ip_address, user_agent),
                    INDEX idx_visit_time (visit_time),
                    INDEX idx_page_url (page_url)
                )";
                $this->pdo->exec($sql);
            } else {
                // Check if bounced column exists
                $stmt = $this->pdo->prepare("SHOW COLUMNS FROM analytics LIKE 'bounced'");
                $stmt->execute();
                
                if ($stmt->rowCount() == 0) {
                    // Add bounced column
                    $this->pdo->exec("ALTER TABLE analytics ADD COLUMN bounced TINYINT(1) DEFAULT 0");
                }
                
                // Check if page_views column exists
                $stmt = $this->pdo->prepare("SHOW COLUMNS FROM analytics LIKE 'page_views'");
                $stmt->execute();
                
                if ($stmt->rowCount() == 0) {
                    // Add page_views column
                    $this->pdo->exec("ALTER TABLE analytics ADD COLUMN page_views INT DEFAULT 1");
                }
            }
        } catch (Exception $e) {
            error_log("Error ensuring analytics table: " . $e->getMessage());
        }
    }

    public function updateSessionDuration($sessionId, $duration) {
        try {
            $sql = "UPDATE analytics SET session_duration = ? WHERE session_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$duration, $sessionId]);
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Fout bij het updaten van sessieduur: " . $e->getMessage());
            return false;
        }
    }
    
    public function getStats() {
        try {
            // Haal statistieken op
            $stmt = $this->pdo->query("SELECT COUNT(*) as totalVisitors, SUM(page_views) as totalPageViews, AVG(session_duration) as averageDuration, SUM(bounced) as totalBounces FROM analytics");
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("Error getting stats: " . $e->getMessage());
            return [
                'totalVisitors' => 0,
                'totalPageViews' => 0,
                'averageDuration' => 0,
                'totalBounces' => 0
            ];
        }
    }
    
    public function getAverageSessionDuration() {
        try {
            $sql = "SELECT AVG(session_duration) as avg_duration FROM analytics WHERE session_duration > 0";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['avg_duration'] ?? 0;
        } catch (PDOException $e) {
            error_log("Fout bij het berekenen van de gemiddelde sessieduur: " . $e->getMessage());
            return 0;
        }
    }
}
?>