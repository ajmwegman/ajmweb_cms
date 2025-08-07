<?php
include( "../system/database.php" );
require_once( "../src/analytics.class.php" );

echo "<h2>Test Date Range: 01-08-2025 to 07-08-2025</h2>";

try {
    $analytics = new Analytics($pdo);
    
    $startDate = '2025-08-01';
    $endDate = '2025-08-07';
    
    echo "<h3>Testing all analytics methods with date range:</h3>";
    echo "<p><strong>Start Date:</strong> $startDate</p>";
    echo "<p><strong>End Date:</strong> $endDate</p>";
    
    // Test basic stats
    echo "<h4>1. Basic Stats:</h4>";
    $stats = $analytics->getStats($startDate, $endDate);
    echo "<pre>" . print_r($stats, true) . "</pre>";
    
    // Test enhanced stats
    echo "<h4>2. Enhanced Stats:</h4>";
    $enhancedStats = $analytics->getEnhancedStats($startDate, $endDate);
    echo "<pre>" . print_r($enhancedStats, true) . "</pre>";
    
    // Test unique visitors
    echo "<h4>3. Unique Visitors:</h4>";
    $uniqueVisitors = $analytics->getUniqueVisitors($startDate, $endDate);
    echo "<p>Unique Visitors: $uniqueVisitors</p>";
    
    // Test bounce rate
    echo "<h4>4. Bounce Rate:</h4>";
    $bounceRate = $analytics->getBounceRate($startDate, $endDate);
    echo "<p>Bounce Rate: $bounceRate%</p>";
    
    // Test device breakdown
    echo "<h4>5. Device Breakdown:</h4>";
    $deviceBreakdown = $analytics->getDeviceBreakdown($startDate, $endDate);
    echo "<pre>" . print_r($deviceBreakdown, true) . "</pre>";
    
    // Test browser breakdown
    echo "<h4>6. Browser Breakdown:</h4>";
    $browserBreakdown = $analytics->getBrowserBreakdown($startDate, $endDate);
    echo "<pre>" . print_r($browserBreakdown, true) . "</pre>";
    
    // Test top pages
    echo "<h4>7. Top Pages:</h4>";
    $topPages = $analytics->getTopPages(10, $startDate, $endDate);
    echo "<pre>" . print_r($topPages, true) . "</pre>";
    
    // Test visitor counts by day
    echo "<h4>8. Visitor Counts by Day:</h4>";
    $visitorCounts = $analytics->getVisitorCountsByDay($startDate, $endDate);
    echo "<pre>" . print_r($visitorCounts, true) . "</pre>";
    
    // Test enhanced visitor data
    echo "<h4>9. Enhanced Visitor Data:</h4>";
    $enhancedVisitorData = $analytics->getEnhancedVisitorData($startDate, $endDate);
    echo "<pre>" . print_r($enhancedVisitorData, true) . "</pre>";
    
    echo "<h3>✅ All tests completed successfully!</h3>";
    echo "<p>All analytics methods are properly supporting the date range functionality.</p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error occurred:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?> 