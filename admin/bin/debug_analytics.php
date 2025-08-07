<?php
/**
 * Debug Analytics Script
 * Controleert wat er in de analytics database staat
 */

include("../../system/database.php");
require_once("../src/database.class.php");
require_once("../src/analytics.class.php");

echo "<h2>Analytics Debug Script</h2>";
echo "<p>Tijd: " . date('Y-m-d H:i:s') . "</p>";

try {
    $db = new database($pdo);
    $analytics = new Analytics($pdo);
    
    echo "<h3>Database Status:</h3>";
    
    // Controleer of analytics tabel bestaat
    $stmt = $pdo->query("SHOW TABLES LIKE 'analytics'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ Analytics tabel bestaat</p>";
        
        // Totaal aantal records
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM analytics");
        $totalRecords = $stmt->fetch()['total'];
        echo "<p>Totaal aantal records: <strong>$totalRecords</strong></p>";
        
        if ($totalRecords > 0) {
            // Sample data
            $stmt = $pdo->query("SELECT * FROM analytics ORDER BY id DESC LIMIT 5");
            $sampleData = $stmt->fetchAll();
            
            echo "<h4>Laatste 5 records:</h4>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>IP</th><th>Browser</th><th>Mobile</th><th>Page Views</th><th>Bounced</th><th>Visit Time</th></tr>";
            
            foreach ($sampleData as $row) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['ip_address'] . "</td>";
                echo "<td>" . $row['browser'] . "</td>";
                echo "<td>" . ($row['is_mobile'] ? 'Ja' : 'Nee') . "</td>";
                echo "<td>" . $row['page_views'] . "</td>";
                echo "<td>" . ($row['bounced'] ? 'Ja' : 'Nee') . "</td>";
                echo "<td>" . $row['visit_time'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            // Device breakdown test
            echo "<h4>Device Breakdown Test:</h4>";
            $deviceData = $analytics->getDeviceBreakdown();
            echo "<pre>" . print_r($deviceData, true) . "</pre>";
            
            // Browser breakdown test
            echo "<h4>Browser Breakdown Test:</h4>";
            $browserData = $analytics->getBrowserBreakdown();
            echo "<pre>" . print_r($browserData, true) . "</pre>";
            
            // Enhanced stats test
            echo "<h4>Enhanced Stats Test:</h4>";
            $enhancedStats = $analytics->getEnhancedStats();
            echo "<pre>" . print_r($enhancedStats, true) . "</pre>";
            
        } else {
            echo "<p style='color: orange;'>⚠ Geen records gevonden in analytics tabel</p>";
            echo "<p>Dit betekent dat er nog geen bezoekers zijn getrackt.</p>";
        }
        
    } else {
        echo "<p style='color: red;'>✗ Analytics tabel bestaat niet</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Fout: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><strong>Volgende stappen:</strong></p>";
echo "<ol>";
echo "<li>Als er geen data is, bezoek je website om analytics te genereren</li>";
echo "<li>Controleer of de analytics tracking werkt</li>";
echo "<li>Test de charts in de admin panel</li>";
echo "</ol>";
?> 