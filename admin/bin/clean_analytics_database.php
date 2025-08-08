<?php
// Script om spam/bot data automatisch uit de analytics database te verwijderen
require_once("../../system/database.php");

try {
    echo "<h2>Analytics Database Cleanup</h2>";
    
    // Eerst tellen hoeveel records er zijn die gefilterd worden
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as total_records 
        FROM analytics 
        WHERE page_url LIKE '%?e=%' 
           OR page_url LIKE '%?channel=%' 
           OR page_url LIKE '%?from=%' 
           OR page_url LIKE '%?utm_%' 
           OR page_url LIKE '%?fbclid=%' 
           OR page_url LIKE '%?gclid=%' 
           OR LENGTH(page_url) > 200
    ");
    $stmt->execute();
    $spamRecords = $stmt->fetch()['total_records'];
    
    // Totaal aantal records
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM analytics");
    $stmt->execute();
    $totalRecords = $stmt->fetch()['total'];
    
    echo "<h3>Database Status:</h3>";
    echo "<p>Totale records: " . $totalRecords . "</p>";
    echo "<p>Spam/Bot records die verwijderd worden: " . $spamRecords . "</p>";
    echo "<p>Percentage spam: " . round(($spamRecords / $totalRecords) * 100, 2) . "%</p>";
    
    if ($spamRecords > 0) {
        echo "<h3>Verwijderen van spam data...</h3>";
        
        // Verwijder spam records
        $stmt = $pdo->prepare("
            DELETE FROM analytics 
            WHERE page_url LIKE '%?e=%' 
               OR page_url LIKE '%?channel=%' 
               OR page_url LIKE '%?from=%' 
               OR page_url LIKE '%?utm_%' 
               OR page_url LIKE '%?fbclid=%' 
               OR page_url LIKE '%?gclid=%' 
               OR LENGTH(page_url) > 200
        ");
        $stmt->execute();
        $deletedRecords = $stmt->rowCount();
        
        echo "<p style='color: green;'>✓ " . $deletedRecords . " spam records verwijderd</p>";
        
        // Toon nieuwe database status
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM analytics");
        $stmt->execute();
        $newTotalRecords = $stmt->fetch()['total'];
        
        echo "<h3>Nieuwe Database Status:</h3>";
        echo "<p>Overgebleven records: " . $newTotalRecords . "</p>";
        echo "<p>Ruimte bespaard: " . ($totalRecords - $newTotalRecords) . " records</p>";
        
    } else {
        echo "<p style='color: blue;'>✓ Geen spam data gevonden om te verwijderen</p>";
    }
    
    // Toon voorbeeld van verdachte URLs die nog in de database staan
    echo "<h3>Controle op verdachte URLs:</h3>";
    $stmt = $pdo->prepare("
        SELECT page_url, COUNT(*) as count 
        FROM analytics 
        WHERE page_url LIKE '%?%' 
           OR page_url LIKE '%&%' 
           OR LENGTH(page_url) > 100
        GROUP BY page_url 
        ORDER BY count DESC 
        LIMIT 10
    ");
    $stmt->execute();
    $suspiciousUrls = $stmt->fetchAll();
    
    if (empty($suspiciousUrls)) {
        echo "<p style='color: green;'>✓ Geen verdachte URLs gevonden</p>";
    } else {
        echo "<p style='color: orange;'>⚠ Verdachte URLs gevonden (mogelijk legitieme URLs met parameters):</p>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>URL</th><th>Aantal</th></tr>";
        
        foreach ($suspiciousUrls as $url) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($url['page_url']) . "</td>";
            echo "<td>" . $url['count'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Database optimalisatie
    echo "<h3>Database Optimalisatie:</h3>";
    
    // OPTIMIZE TABLE
    $stmt = $pdo->prepare("OPTIMIZE TABLE analytics");
    $stmt->execute();
    echo "<p style='color: green;'>✓ Database geoptimaliseerd</p>";
    
    // Toon tabel grootte
    $stmt = $pdo->prepare("
        SELECT 
            table_name,
            ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size (MB)'
        FROM information_schema.tables 
        WHERE table_schema = DATABASE() 
        AND table_name = 'analytics'
    ");
    $stmt->execute();
    $tableInfo = $stmt->fetch();
    
    if ($tableInfo) {
        echo "<p>Analytics tabel grootte: " . $tableInfo['Size (MB)'] . " MB</p>";
    }
    
    echo "<h3>Automatische Cleanup Instellingen:</h3>";
    echo "<p>Je kunt dit script automatisch laten draaien via een cron job:</p>";
    echo "<code>0 2 * * * php /path/to/your/site/admin/bin/clean_analytics_database.php</code>";
    echo "<p>Dit zal elke dag om 2:00 's nachts de database opschonen.</p>";
    
} catch (Exception $e) {
    echo "<h3>Error:</h3>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
}
?>
