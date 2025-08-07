<?php
class Analytics {
    
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getStats() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as totalVisitors, SUM(page_views) as totalPageViews, AVG(session_duration) as averageDuration, SUM(bounced) as totalBounces FROM analytics");
        return $stmt->fetch();
    }
    
    public function getEnhancedStats() {
        $basicStats = $this->getStats();
        
        $enhancedStats = array_merge($basicStats, [
            'uniqueVisitors' => $this->getUniqueVisitors(),
            'bounceRate' => $this->getBounceRate(),
            'avgPagesPerSession' => $this->getAvgPagesPerSession(),
            'topReferrers' => $this->getTopReferrers(),
            'deviceBreakdown' => $this->getDeviceBreakdown(),
            'browserBreakdown' => $this->getBrowserBreakdown(),
            'topPages' => $this->getTopPages(),
            'conversionRate' => $this->getConversionRate()
        ]);
        
        return $enhancedStats;
    }
    
    public function getUniqueVisitors() {
        $stmt = $this->pdo->query("SELECT COUNT(DISTINCT ip_address) as unique_visitors FROM analytics");
        return $stmt->fetch()['unique_visitors'];
    }

    public function getBounceRate() {
        $stmt = $this->pdo->query("
            SELECT 
                ROUND((SUM(bounced) * 100.0 / COUNT(*)), 2) as bounce_rate 
            FROM analytics
        ");
        return $stmt->fetch()['bounce_rate'];
    }

    public function getAvgPagesPerSession() {
        $stmt = $this->pdo->query("
            SELECT ROUND(AVG(page_views), 2) as avg_pages 
            FROM analytics 
            WHERE page_views > 0
        ");
        return $stmt->fetch()['avg_pages'];
    }

    public function getTopReferrers($limit = 5) {
        $stmt = $this->pdo->prepare("
            SELECT referer_url, COUNT(*) as count 
            FROM analytics 
            WHERE referer_url != 'unknown' 
            GROUP BY referer_url 
            ORDER BY count DESC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getDeviceBreakdown() {
        $stmt = $this->pdo->query("
            SELECT 
                CASE WHEN is_mobile = 1 THEN 'Mobile' ELSE 'Desktop' END as device,
                COUNT(*) as count,
                ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM analytics)), 2) as percentage
            FROM analytics 
            GROUP BY is_mobile
        ");
        return $stmt->fetchAll();
    }

    public function getBrowserBreakdown() {
        $stmt = $this->pdo->query("
            SELECT browser, COUNT(*) as count,
            ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM analytics)), 2) as percentage
            FROM analytics 
            WHERE browser != 'unknown'
            GROUP BY browser 
            ORDER BY count DESC
        ");
        return $stmt->fetchAll();
    }

    public function getTopPages($limit = 5) {
        $stmt = $this->pdo->prepare("
            SELECT page_url, COUNT(*) as count 
            FROM analytics 
            WHERE page_url IS NOT NULL
            GROUP BY page_url 
            ORDER BY count DESC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
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