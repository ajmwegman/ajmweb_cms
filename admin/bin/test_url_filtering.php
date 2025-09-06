<?php
// Test script for URL filtering
require_once __DIR__ . '/../../system/database.php';
require_once __DIR__ . '/../src/analytics.class.php';

try {
    $analytics = new Analytics($pdo);
    
    echo "<h2>URL Filtering Test Results</h2>";
    
    // Test the getTopPages method with filtering
    echo "<h3>Top Pages (with spam filtering):</h3>";
    $topPages = $analytics->getTopPages(10);
    
    if (empty($topPages)) {
        echo "<p>No legitimate pages found in analytics data.</p>";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Page URL</th><th>Visits</th><th>Percentage</th></tr>";
        
        foreach ($topPages as $page) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($page['page_url']) . "</td>";
            echo "<td>" . $page['count'] . "</td>";
            echo "<td>" . $page['percentage'] . "%</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Check for any suspicious URLs in the database
    echo "<h3>Checking for Suspicious URLs in Database:</h3>";
    
    $stmt = $pdo->prepare("
        SELECT page_url, COUNT(*) as count 
        FROM analytics 
        WHERE page_url LIKE '%?e=%' 
           OR page_url LIKE '%?channel=%' 
           OR page_url LIKE '%?from=%' 
           OR page_url LIKE '%?utm_%' 
           OR page_url LIKE '%?fbclid=%' 
           OR page_url LIKE '%?gclid=%' 
           OR LENGTH(page_url) > 200
        GROUP BY page_url 
        ORDER BY count DESC 
        LIMIT 10
    ");
    $stmt->execute();
    $suspiciousUrls = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($suspiciousUrls)) {
        echo "<p style='color: green;'>✓ No suspicious URLs found in database.</p>";
    } else {
        echo "<p style='color: red;'>⚠ Found suspicious URLs in database:</p>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Suspicious URL</th><th>Count</th></tr>";
        
        foreach ($suspiciousUrls as $url) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($url['page_url']) . "</td>";
            echo "<td>" . $url['count'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<p><strong>Note:</strong> These URLs will be filtered out from the Top Pages display.</p>";
    }
    
    // Show total records count
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM analytics");
    $stmt->execute();
    $totalRecords = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    echo "<h3>Database Summary:</h3>";
    echo "<p>Total analytics records: " . $totalRecords . "</p>";
    
} catch (PDOException $e) {
    error_log('PDOException in test_url_filtering.php: ' . $e->getMessage());
    echo "<h3>Error:</h3>";
    echo "<p style='color: red;'>A database error occurred. Please try again later.</p>";
} catch (Exception $e) {
    error_log('Exception in test_url_filtering.php: ' . $e->getMessage());
    echo "<h3>Error:</h3>";
    echo "<p style='color: red;'>An unexpected error occurred. Please try again later.</p>";
}
?>
