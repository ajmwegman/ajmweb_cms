<?php
// Script om oude analytics data te aggregeren (ouder dan 1 dag)
// Dit voorkomt dat de database te groot wordt
require_once("../../system/database.php");

try {
    echo "<h2>Analytics Data Aggregation</h2>";
    
    // Eerst controleren of er data ouder dan 1 dag is
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as old_records 
        FROM analytics 
        WHERE DATE(visit_time) < DATE_SUB(NOW(), INTERVAL 1 DAY)
    ");
    $stmt->execute();
    $oldRecords = $stmt->fetch()['old_records'];
    
    // Totaal aantal records
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM analytics");
    $stmt->execute();
    $totalRecords = $stmt->fetch()['total'];
    
    echo "<h3>Database Status:</h3>";
    echo "<p>Totale records: " . $totalRecords . "</p>";
    echo "<p>Records ouder dan 1 dag: " . $oldRecords . "</p>";
    echo "<p>Percentage oude data: " . round(($oldRecords / $totalRecords) * 100, 2) . "%</p>";
    
    if ($oldRecords > 0) {
        echo "<h3>Aggregeren van oude data...</h3>";
        
        // Maak een tijdelijke tabel voor geaggregeerde data
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS analytics_aggregated (
                id INT AUTO_INCREMENT PRIMARY KEY,
                date_key DATE,
                page_url VARCHAR(500),
                total_visits INT DEFAULT 0,
                total_page_views INT DEFAULT 0,
                total_bounces INT DEFAULT 0,
                total_session_duration INT DEFAULT 0,
                unique_visitors INT DEFAULT 0,
                device_desktop INT DEFAULT 0,
                device_mobile INT DEFAULT 0,
                device_tablet INT DEFAULT 0,
                browser_chrome INT DEFAULT 0,
                browser_firefox INT DEFAULT 0,
                browser_safari INT DEFAULT 0,
                browser_edge INT DEFAULT 0,
                browser_other INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_date_page (date_key, page_url),
                INDEX idx_date (date_key)
            )
        ");
        
        // Aggregeer data per dag en pagina
        $aggregateQuery = "
            INSERT INTO analytics_aggregated (
                date_key, page_url, total_visits, total_page_views, total_bounces, 
                total_session_duration, unique_visitors,
                device_desktop, device_mobile, device_tablet,
                browser_chrome, browser_firefox, browser_safari, browser_edge, browser_other
            )
            SELECT 
                DATE(visit_time) as date_key,
                page_url,
                COUNT(*) as total_visits,
                SUM(page_views) as total_page_views,
                SUM(bounced) as total_bounces,
                SUM(session_duration) as total_session_duration,
                COUNT(DISTINCT ip_address) as unique_visitors,
                SUM(CASE WHEN is_mobile = 0 THEN 1 ELSE 0 END) as device_desktop,
                SUM(CASE WHEN is_mobile = 1 AND user_agent NOT LIKE '%iPad%' AND user_agent NOT LIKE '%Tablet%' THEN 1 ELSE 0 END) as device_mobile,
                SUM(CASE WHEN is_mobile = 1 AND (user_agent LIKE '%iPad%' OR user_agent LIKE '%Tablet%') THEN 1 ELSE 0 END) as device_tablet,
                SUM(CASE WHEN browser = 'Chrome' THEN 1 ELSE 0 END) as browser_chrome,
                SUM(CASE WHEN browser = 'Firefox' THEN 1 ELSE 0 END) as browser_firefox,
                SUM(CASE WHEN browser = 'Safari' THEN 1 ELSE 0 END) as browser_safari,
                SUM(CASE WHEN browser = 'Edge' THEN 1 ELSE 0 END) as browser_edge,
                SUM(CASE WHEN browser NOT IN ('Chrome', 'Firefox', 'Safari', 'Edge') THEN 1 ELSE 0 END) as browser_other
            FROM analytics 
            WHERE DATE(visit_time) < DATE_SUB(NOW(), INTERVAL 1 DAY)
            GROUP BY DATE(visit_time), page_url
            ON DUPLICATE KEY UPDATE
                total_visits = total_visits + VALUES(total_visits),
                total_page_views = total_page_views + VALUES(total_page_views),
                total_bounces = total_bounces + VALUES(total_bounces),
                total_session_duration = total_session_duration + VALUES(total_session_duration),
                unique_visitors = VALUES(unique_visitors),
                device_desktop = device_desktop + VALUES(device_desktop),
                device_mobile = device_mobile + VALUES(device_mobile),
                device_tablet = device_tablet + VALUES(device_tablet),
                browser_chrome = browser_chrome + VALUES(browser_chrome),
                browser_firefox = browser_firefox + VALUES(browser_firefox),
                browser_safari = browser_safari + VALUES(browser_safari),
                browser_edge = browser_edge + VALUES(browser_edge),
                browser_other = browser_other + VALUES(browser_other)
        ";
        
        $stmt = $pdo->prepare($aggregateQuery);
        $stmt->execute();
        $aggregatedRows = $stmt->rowCount();
        
        echo "<p style='color: green;'>✓ " . $aggregatedRows . " geaggregeerde rijen toegevoegd</p>";
        
        // Verwijder oude data die nu geaggregeerd is
        $stmt = $pdo->prepare("
            DELETE FROM analytics 
            WHERE DATE(visit_time) < DATE_SUB(NOW(), INTERVAL 1 DAY)
        ");
        $stmt->execute();
        $deletedRows = $stmt->rowCount();
        
        echo "<p style='color: green;'>✓ " . $deletedRows . " oude records verwijderd</p>";
        
        // Toon nieuwe database status
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM analytics");
        $stmt->execute();
        $newTotalRecords = $stmt->fetch()['total'];
        
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM analytics_aggregated");
        $stmt->execute();
        $aggregatedTotal = $stmt->fetch()['total'];
        
        echo "<h3>Nieuwe Database Status:</h3>";
        echo "<p>Huidige records (laatste 24 uur): " . $newTotalRecords . "</p>";
        echo "<p>Geaggregeerde records: " . $aggregatedTotal . "</p>";
        echo "<p>Ruimte bespaard: " . ($totalRecords - $newTotalRecords) . " records</p>";
        
    } else {
        echo "<p style='color: blue;'>✓ Geen oude data gevonden om te aggregeren</p>";
    }
    
    // Database optimalisatie
    echo "<h3>Database Optimalisatie:</h3>";
    
    // OPTIMIZE TABLES
    $stmt = $pdo->prepare("OPTIMIZE TABLE analytics");
    $stmt->execute();
    echo "<p style='color: green;'>✓ Analytics tabel geoptimaliseerd</p>";
    
    $stmt = $pdo->prepare("OPTIMIZE TABLE analytics_aggregated");
    $stmt->execute();
    echo "<p style='color: green;'>✓ Aggregated tabel geoptimaliseerd</p>";
    
    // Toon tabel grootte
    $stmt = $pdo->prepare("
        SELECT 
            table_name,
            ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size (MB)'
        FROM information_schema.tables 
        WHERE table_schema = DATABASE() 
        AND table_name IN ('analytics', 'analytics_aggregated')
    ");
    $stmt->execute();
    $tableInfo = $stmt->fetchAll();
    
    echo "<h3>Tabel Grootte:</h3>";
    foreach ($tableInfo as $table) {
        echo "<p>" . $table['table_name'] . ": " . $table['Size (MB)'] . " MB</p>";
    }
    
    echo "<h3>Automatische Aggregatie Instellingen:</h3>";
    echo "<p>Je kunt dit script automatisch laten draaien via een cron job:</p>";
    echo "<code>0 3 * * * php /path/to/your/site/admin/bin/aggregate_old_analytics.php</code>";
    echo "<p>Dit zal elke dag om 3:00 's nachts oude data aggregeren.</p>";
    
} catch (Exception $e) {
    echo "<h3>Error:</h3>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
}
?>
