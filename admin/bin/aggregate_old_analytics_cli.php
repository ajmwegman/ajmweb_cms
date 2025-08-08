<?php
// Command-line versie van de analytics aggregatie
// Gebruik: php aggregate_old_analytics_cli.php

// Zorg ervoor dat we in CLI mode zijn
if (php_sapi_name() !== 'cli') {
    die("Dit script moet via command line worden uitgevoerd\n");
}

require_once("../../system/database.php");

try {
    echo "=== Analytics Data Aggregation ===\n\n";
    
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
    
    echo "Database Status:\n";
    echo "- Totale records: " . $totalRecords . "\n";
    echo "- Records ouder dan 1 dag: " . $oldRecords . "\n";
    echo "- Percentage oude data: " . round(($oldRecords / $totalRecords) * 100, 2) . "%\n\n";
    
    if ($oldRecords > 0) {
        echo "Aggregeren van oude data...\n";
        
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
        
        echo "✓ " . $aggregatedRows . " geaggregeerde rijen toegevoegd\n";
        
        // Verwijder oude data die nu geaggregeerd is
        $stmt = $pdo->prepare("
            DELETE FROM analytics 
            WHERE DATE(visit_time) < DATE_SUB(NOW(), INTERVAL 1 DAY)
        ");
        $stmt->execute();
        $deletedRows = $stmt->rowCount();
        
        echo "✓ " . $deletedRows . " oude records verwijderd\n";
        
        // Toon nieuwe database status
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM analytics");
        $stmt->execute();
        $newTotalRecords = $stmt->fetch()['total'];
        
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM analytics_aggregated");
        $stmt->execute();
        $aggregatedTotal = $stmt->fetch()['total'];
        
        echo "\nNieuwe Database Status:\n";
        echo "- Huidige records (laatste 24 uur): " . $newTotalRecords . "\n";
        echo "- Geaggregeerde records: " . $aggregatedTotal . "\n";
        echo "- Ruimte bespaard: " . ($totalRecords - $newTotalRecords) . " records\n";
        
    } else {
        echo "✓ Geen oude data gevonden om te aggregeren\n";
    }
    
    // Database optimalisatie
    echo "\nDatabase optimalisatie...\n";
    
    // OPTIMIZE TABLES
    $stmt = $pdo->prepare("OPTIMIZE TABLE analytics");
    $stmt->execute();
    echo "✓ Analytics tabel geoptimaliseerd\n";
    
    $stmt = $pdo->prepare("OPTIMIZE TABLE analytics_aggregated");
    $stmt->execute();
    echo "✓ Aggregated tabel geoptimaliseerd\n";
    
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
    
    echo "\nTabel Grootte:\n";
    foreach ($tableInfo as $table) {
        echo "- " . $table['table_name'] . ": " . $table['Size (MB)'] . " MB\n";
    }
    
    echo "\n=== Aggregatie voltooid ===\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
