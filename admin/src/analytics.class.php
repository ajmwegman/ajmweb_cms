<?php
class Analytics {

    /** @var PDO */
    private $pdo;
    
    public function __construct($pdo)
        {
            $this->pdo = $pdo;
        }

    public function getStats() {
        // Haal statistieken op
        $stmt = $this->pdo->query("SELECT COUNT(*) as totalVisitors, SUM(page_views) as totalPageViews, AVG(session_duration) as averageDuration, SUM(bounced) as totalBounces FROM analytics");
        return $stmt->fetch();
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
            echo "Fout bij het ophalen van bezoekersaantallen: " . $e->getMessage();
            return [];
        }
    }
}
?>