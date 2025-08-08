<?php
// Test script for analytics functionality
require_once("../../system/database.php");
require_once("../src/analytics.class.php");

try {
    $analytics = new Analytics($pdo);
    
    echo "<h2>Analytics Test Results</h2>";
    
    // Test basic stats
    echo "<h3>Basic Stats:</h3>";
    $stats = $analytics->getStats();
    echo "<pre>";
    print_r($stats);
    echo "</pre>";
    
    // Test enhanced stats
    echo "<h3>Enhanced Stats:</h3>";
    $enhancedStats = $analytics->getEnhancedStats();
    echo "<pre>";
    print_r($enhancedStats);
    echo "</pre>";
    
    // Test top pages specifically
    echo "<h3>Top Pages:</h3>";
    $topPages = $analytics->getTopPages(10);
    echo "<pre>";
    print_r($topPages);
    echo "</pre>";
    
    // Check if analytics table exists
    echo "<h3>Database Check:</h3>";
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'analytics'");
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo "✓ Analytics table exists<br>";
        
        // Check table structure
        $stmt = $pdo->prepare("DESCRIBE analytics");
        $stmt->execute();
        $columns = $stmt->fetchAll();
        echo "<h4>Table Structure:</h4>";
        echo "<pre>";
        print_r($columns);
        echo "</pre>";
        
        // Check if we have any data
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM analytics");
        $stmt->execute();
        $count = $stmt->fetch();
        echo "Total records in analytics table: " . $count['total'] . "<br>";
        
        if ($count['total'] > 0) {
            // Show some sample data
            $stmt = $pdo->prepare("SELECT page_url, COUNT(*) as visits FROM analytics WHERE page_url IS NOT NULL GROUP BY page_url ORDER BY visits DESC LIMIT 5");
            $stmt->execute();
            $sampleData = $stmt->fetchAll();
            echo "<h4>Sample Page Data:</h4>";
            echo "<pre>";
            print_r($sampleData);
            echo "</pre>";
        }
    } else {
        echo "✗ Analytics table does not exist<br>";
    }
    
} catch (Exception $e) {
    echo "<h3>Error:</h3>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
