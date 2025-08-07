<?php
class Analytics {
    
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getStats($startDate = null, $endDate = null) {
        $whereClause = "";
        $params = [];
        
        if ($startDate && $endDate) {
            $whereClause = "WHERE DATE(visit_time) BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
        }
        
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as totalVisitors, SUM(page_views) as totalPageViews, AVG(session_duration) as averageDuration, SUM(bounced) as totalBounces FROM analytics " . $whereClause);
        $stmt->execute($params);
        return $stmt->fetch();
    }
    
    public function getEnhancedStats($startDate = null, $endDate = null) {
        $basicStats = $this->getStats($startDate, $endDate);
        
        $enhancedStats = array_merge($basicStats, [
            'uniqueVisitors' => $this->getUniqueVisitors($startDate, $endDate),
            'bounceRate' => $this->getBounceRate($startDate, $endDate),
            'avgPagesPerSession' => $this->getAvgPagesPerSession($startDate, $endDate),
            'topReferrers' => $this->getTopReferrers(5, $startDate, $endDate),
            'deviceBreakdown' => $this->getDeviceBreakdown($startDate, $endDate),
            'browserBreakdown' => $this->getBrowserBreakdown($startDate, $endDate),
            'topPages' => $this->getTopPages(10, $startDate, $endDate),
            'conversionRate' => $this->getConversionRate($startDate, $endDate)
        ]);
        
        return $enhancedStats;
    }
    
    public function getUniqueVisitors($startDate = null, $endDate = null) {
        $whereClause = "";
        $params = [];
        
        if ($startDate && $endDate) {
            $whereClause = "WHERE DATE(visit_time) BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
        }
        
        $stmt = $this->pdo->prepare("SELECT COUNT(DISTINCT ip_address) as unique_visitors FROM analytics " . $whereClause);
        $stmt->execute($params);
        return $stmt->fetch()['unique_visitors'];
    }

    public function getBounceRate($startDate = null, $endDate = null) {
        $whereClause = "";
        $params = [];
        
        if ($startDate && $endDate) {
            $whereClause = "WHERE DATE(visit_time) BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
        }
        
        $stmt = $this->pdo->prepare("
            SELECT 
                ROUND((SUM(bounced) * 100.0 / COUNT(*)), 2) as bounce_rate 
            FROM analytics " . $whereClause
        );
        $stmt->execute($params);
        return $stmt->fetch()['bounce_rate'];
    }

    public function getAvgPagesPerSession($startDate = null, $endDate = null) {
        $whereClause = "WHERE page_views > 0";
        $params = [];
        
        if ($startDate && $endDate) {
            $whereClause .= " AND DATE(visit_time) BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
        }
        
        $stmt = $this->pdo->prepare("
            SELECT ROUND(AVG(page_views), 2) as avg_pages 
            FROM analytics 
            " . $whereClause
        );
        $stmt->execute($params);
        return $stmt->fetch()['avg_pages'];
    }

    public function getTopReferrers($limit = 5, $startDate = null, $endDate = null) {
        $whereClause = "WHERE referer_url != 'unknown'";
        $params = [$limit];
        
        if ($startDate && $endDate) {
            $whereClause .= " AND DATE(visit_time) BETWEEN ? AND ?";
            $params = [$startDate, $endDate, $limit];
        }
        
        $stmt = $this->pdo->prepare("
            SELECT referer_url, COUNT(*) as count 
            FROM analytics 
            " . $whereClause . "
            GROUP BY referer_url 
            ORDER BY count DESC 
            LIMIT ?
        ");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getDeviceBreakdown() {
        try {
            // Eerst controleren of er data is
            $checkStmt = $this->pdo->query("SELECT COUNT(*) as total FROM analytics");
            $totalRecords = $checkStmt->fetch()['total'];
            
            if ($totalRecords == 0) {
                // Geen data, return dummy data
                return [
                    ['device' => 'Desktop', 'count' => 0, 'percentage' => 0],
                    ['device' => 'Mobile', 'count' => 0, 'percentage' => 0],
                    ['device' => 'Tablet', 'count' => 0, 'percentage' => 0]
                ];
            }
            
            // Verbeterde device detection met user agent parsing
            $stmt = $this->pdo->query("
                SELECT 
                    CASE 
                        WHEN is_mobile = 1 AND (
                            user_agent LIKE '%iPad%' OR 
                            user_agent LIKE '%Android%' AND user_agent LIKE '%Tablet%' OR
                            user_agent LIKE '%PlayBook%' OR
                            user_agent LIKE '%Silk%' OR
                            user_agent LIKE '%Kindle%'
                        ) THEN 'Tablet'
                        WHEN is_mobile = 1 THEN 'Mobile'
                        ELSE 'Desktop'
                    END as device,
                    COUNT(*) as count,
                    ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM analytics)), 2) as percentage
                FROM analytics 
                GROUP BY device
                ORDER BY count DESC
            ");
            
            $result = $stmt->fetchAll();
            
            // Als er geen resultaten zijn, voeg dummy data toe
            if (empty($result)) {
                return [
                    ['device' => 'Desktop', 'count' => 0, 'percentage' => 0],
                    ['device' => 'Mobile', 'count' => 0, 'percentage' => 0],
                    ['device' => 'Tablet', 'count' => 0, 'percentage' => 0]
                ];
            }
            
            // Zorg ervoor dat alle device types aanwezig zijn
            $devices = ['Desktop', 'Mobile', 'Tablet'];
            $existingDevices = array_column($result, 'device');
            
            foreach ($devices as $device) {
                if (!in_array($device, $existingDevices)) {
                    $result[] = ['device' => $device, 'count' => 0, 'percentage' => 0];
                }
            }
            
            // Sorteer op count (hoogste eerst)
            usort($result, function($a, $b) {
                return $b['count'] - $a['count'];
            });
            
            return $result;
        } catch (PDOException $e) {
            // Bij fout, return dummy data
            return [
                ['device' => 'Desktop', 'count' => 0, 'percentage' => 0],
                ['device' => 'Mobile', 'count' => 0, 'percentage' => 0],
                ['device' => 'Tablet', 'count' => 0, 'percentage' => 0]
            ];
        }
    }

    public function getBrowserBreakdown() {
        try {
            // Eerst controleren of er data is
            $checkStmt = $this->pdo->query("SELECT COUNT(*) as total FROM analytics");
            $totalRecords = $checkStmt->fetch()['total'];
            
            if ($totalRecords == 0) {
                // Geen data, return dummy data
                return [
                    ['browser' => 'Chrome', 'count' => 0, 'percentage' => 0],
                    ['browser' => 'Firefox', 'count' => 0, 'percentage' => 0],
                    ['browser' => 'Safari', 'count' => 0, 'percentage' => 0]
                ];
            }
            
            $stmt = $this->pdo->query("
                SELECT browser, COUNT(*) as count,
                ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM analytics)), 2) as percentage
                FROM analytics 
                WHERE browser != 'unknown'
                GROUP BY browser 
                ORDER BY count DESC
                LIMIT 5
            ");
            
            $result = $stmt->fetchAll();
            
            // Als er geen resultaten zijn, voeg dummy data toe
            if (empty($result)) {
                return [
                    ['browser' => 'Chrome', 'count' => 0, 'percentage' => 0],
                    ['browser' => 'Firefox', 'count' => 0, 'percentage' => 0],
                    ['browser' => 'Safari', 'count' => 0, 'percentage' => 0]
                ];
            }
            
            return $result;
        } catch (PDOException $e) {
            // Bij fout, return dummy data
            return [
                ['browser' => 'Chrome', 'count' => 0, 'percentage' => 0],
                ['browser' => 'Firefox', 'count' => 0, 'percentage' => 0],
                ['browser' => 'Safari', 'count' => 0, 'percentage' => 0]
            ];
        }
    }

    public function getTopPages($limit = 10) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    page_url, 
                    COUNT(*) as count,
                    ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM analytics WHERE page_url IS NOT NULL)), 2) as percentage
                FROM analytics 
                WHERE page_url IS NOT NULL AND page_url != ''
                GROUP BY page_url 
                ORDER BY count DESC 
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getConversionRate() {
        $stmt = $this->pdo->query("
            SELECT 
                ROUND((COUNT(DISTINCT session_id) * 100.0 / 
                (SELECT COUNT(DISTINCT session_id) FROM analytics)), 2) as conversion_rate
            FROM analytics 
            WHERE session_duration > 300
        ");
        return $stmt->fetch()['conversion_rate'];
    }
    
    public function getVisitorCountsByDay($startDate, $endDate) {
        $sql = "SELECT COUNT(*) as count, DATE(visit_time) as visit_date FROM analytics WHERE DATE(visit_time) BETWEEN :startDate AND :endDate GROUP BY DATE(visit_time) ORDER BY visit_date ASC";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':startDate', $startDate);
            $stmt->bindParam(':endDate', $endDate);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $visitorCounts = [];
            $visitDates = [];

            foreach ($result as $row) {
                $visitorCounts[] = $row['count'];
                $visitDates[] = $row['visit_date'];
            }

            return ['counts' => $visitorCounts, 'dates' => $visitDates];
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
    public function getEnhancedVisitorData($startDate, $endDate) {
        $sql = "SELECT 
                    DATE(visit_time) as visit_date,
                    COUNT(*) as visitors,
                    SUM(page_views) as page_views,
                    SUM(bounced) as bounces
                FROM analytics 
                WHERE DATE(visit_time) BETWEEN :startDate AND :endDate 
                GROUP BY DATE(visit_time) 
                ORDER BY visit_date ASC";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':startDate', $startDate);
            $stmt->bindParam(':endDate', $endDate);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $dates = [];
            $visitors = [];
            $pageViews = [];
            $bounces = [];

            foreach ($result as $row) {
                $dates[] = $row['visit_date'];
                $visitors[] = (int)$row['visitors'];
                $pageViews[] = (int)$row['page_views'];
                $bounces[] = (int)$row['bounces'];
            }

            return [
                'dates' => $dates,
                'visitors' => $visitors,
                'pageViews' => $pageViews,
                'bounces' => $bounces
            ];
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
?>