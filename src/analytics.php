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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://api.ipstack.com/$ipAddress?access_key=" . IPSTACK_API_KEY);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $outputData = json_decode($output, true);
        return $outputData['country_code'] ?? 'UNKNOWN';
    }

   public function recordVisit() {
    $sessionId = session_id();
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
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

    if ($browser === 'unknown' && $referer === 'unknown') {
        return;  // Verlaat de functie vroegtijdig
    }
    
    // Verbeterde device detectie
    $isMobile = false;
    $isTablet = false;
    
    // Tablet detectie
    if (preg_match('/iPad|Android.*Tablet|PlayBook|Silk|Kindle/', $userAgent)) {
        $isTablet = true;
        $isMobile = true; // Tablets worden ook als mobile gezien
    }
    // Mobile detectie (exclusief tablets)
    elseif (preg_match('/Mobile|Android|iPhone|BlackBerry|Windows Phone/', $userAgent)) {
        $isMobile = true;
    }
    
    // Huidige pagina URL
    $pageUrl = $_SERVER['REQUEST_URI'];
    
    $stmt = $this->pdo->prepare("SELECT * FROM analytics WHERE ip_address = ? AND user_agent = ?");
    $stmt->execute([$ipAddress, $userAgent]);
    $row = $stmt->fetch();
    
    if ($row) {
        $stmt = $this->pdo->prepare("UPDATE analytics SET page_views = page_views + 1, bounced = FALSE, page_url = ? WHERE ip_address = ?");
        $stmt->execute([$pageUrl, $ipAddress]);
    } else {
        $stmt = $this->pdo->prepare("INSERT INTO analytics (session_id, ip_address, user_agent, country_code, referer_url, browser, is_mobile, session_start, visit_time, page_url) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
        $stmt->execute([$sessionId, $ipAddress, $userAgent, $countryCode, $referer, $browser, $isMobile, $pageUrl]);
    }
}

    public function updateSessionDuration($sessionId, $duration) {
        $sql = "UPDATE analytics SET session_duration = ? WHERE session_id = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$duration, $sessionId]);
            
            return $stmt->fetch();
        } catch (PDOException $e) {
            echo "Fout bij het updaten van sessieduur: " . $e->getMessage();
        }
    }
    public function getStats() {
        // Haal statistieken op
        $stmt = $this->pdo->query("SELECT COUNT(*) as totalVisitors, SUM(page_views) as totalPageViews, AVG(session_duration) as averageDuration, SUM(bounced) as totalBounces FROM analytics");
        return $stmt->fetch();
    }
    
    public function getAverageSessionDuration() {
    $sql = "SELECT AVG(session_duration) as avg_duration FROM visits";
    try {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['avg_duration'];
    } catch (PDOException $e) {
        echo "Fout bij het berekenen van de gemiddelde sessieduur: " . $e->getMessage();
        return 0;
    }
}
}
?>