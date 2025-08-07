<?php
include( "../system/database.php" );
require_once( "../src/analytics.class.php" );

echo "<h2>Test Date Range Filtering</h2>";

try {
    $analytics = new Analytics($pdo);
    
    // Test with different date ranges
    $testRanges = [
        ['start' => '2024-01-01', 'end' => '2024-01-31', 'name' => 'January 2024'],
        ['start' => '2024-02-01', 'end' => '2024-02-29', 'name' => 'February 2024'],
        ['start' => '2024-03-01', 'end' => '2024-03-31', 'name' => 'March 2024'],
        ['start' => date('Y-m-01'), 'end' => date('Y-m-d'), 'name' => 'Current Month']
    ];
    
    foreach ($testRanges as $range) {
        echo "<h3>Testing: {$range['name']} ({$range['start']} to {$range['end']})</h3>";
        
        // Test basic stats
        $stats = $analytics->getStats($range['start'], $range['end']);
        echo "<p><strong>Basic Stats:</strong> Total Visitors: {$stats['totalVisitors']}, Page Views: {$stats['totalPageViews']}</p>";
        
        // Test unique visitors
        $uniqueVisitors = $analytics->getUniqueVisitors($range['start'], $range['end']);
        echo "<p><strong>Unique Visitors:</strong> {$uniqueVisitors}</p>";
        
        // Test bounce rate
        $bounceRate = $analytics->getBounceRate($range['start'], $range['end']);
        echo "<p><strong>Bounce Rate:</strong> {$bounceRate}%</p>";
        
        // Test device breakdown
        $deviceBreakdown = $analytics->getDeviceBreakdown($range['start'], $range['end']);
        echo "<p><strong>Device Breakdown:</strong></p><ul>";
        foreach ($deviceBreakdown as $device) {
            echo "<li>{$device['device']}: {$device['count']} ({$device['percentage']}%)</li>";
        }
        echo "</ul>";
        
        // Test browser breakdown
        $browserBreakdown = $analytics->getBrowserBreakdown($range['start'], $range['end']);
        echo "<p><strong>Browser Breakdown:</strong></p><ul>";
        foreach ($browserBreakdown as $browser) {
            echo "<li>{$browser['browser']}: {$browser['count']} ({$browser['percentage']}%)</li>";
        }
        echo "</ul>";
        
        // Test top pages
        $topPages = $analytics->getTopPages(5, $range['start'], $range['end']);
        echo "<p><strong>Top Pages:</strong></p><ul>";
        foreach ($topPages as $page) {
            echo "<li>{$page['page_url']}: {$page['count']} visits ({$page['percentage']}%)</li>";
        }
        echo "</ul>";
        
        echo "<hr>";
    }
    
    echo "<h3>Test Complete!</h3>";
    echo "<p>If you see data for different date ranges, the filtering is working correctly.</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?> 