<?php
include( "../system/database.php" );
require_once( "../src/analytics.class.php" );

echo "<!DOCTYPE html>
<html>
<head>
    <title>Analytics Date Range Demo</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .demo-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background-color: #d4edda; border-color: #c3e6cb; }
        .info { background-color: #d1ecf1; border-color: #bee5eb; }
        .warning { background-color: #fff3cd; border-color: #ffeaa7; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 3px; }
    </style>
</head>
<body>";

echo "<h1>📊 Analytics Date Range Demo</h1>";

echo "<div class='demo-section info'>
    <h2>🎯 Demo: Date Range 01-08-2025 to 07-08-2025</h2>
    <p>This demo shows how all analytics data is filtered by the selected date range.</p>
</div>";

try {
    $analytics = new Analytics($pdo);
    
    $startDate = '2025-08-01';
    $endDate = '2025-08-07';
    
    echo "<div class='demo-section success'>
        <h3>✅ Date Range Applied</h3>
        <p><strong>Start Date:</strong> $startDate</p>
        <p><strong>End Date:</strong> $endDate</p>
        <p><strong>Duration:</strong> 7 days</p>
    </div>";
    
    // Test all analytics methods
    echo "<div class='demo-section'>
        <h3>📈 Analytics Results</h3>";
    
    // Basic stats
    $stats = $analytics->getStats($startDate, $endDate);
    echo "<h4>📊 Basic Statistics</h4>";
    echo "<ul>";
    echo "<li><strong>Total Visitors:</strong> " . ($stats['totalVisitors'] ?? 0) . "</li>";
    echo "<li><strong>Total Page Views:</strong> " . ($stats['totalPageViews'] ?? 0) . "</li>";
    echo "<li><strong>Average Session Duration:</strong> " . round($stats['averageDuration'] ?? 0) . " seconds</li>";
    echo "<li><strong>Total Bounces:</strong> " . ($stats['totalBounces'] ?? 0) . "</li>";
    echo "</ul>";
    
    // Enhanced stats
    $enhancedStats = $analytics->getEnhancedStats($startDate, $endDate);
    echo "<h4>🎯 Enhanced Statistics</h4>";
    echo "<ul>";
    echo "<li><strong>Unique Visitors:</strong> " . ($enhancedStats['uniqueVisitors'] ?? 0) . "</li>";
    echo "<li><strong>Bounce Rate:</strong> " . ($enhancedStats['bounceRate'] ?? 0) . "%</li>";
    echo "<li><strong>Average Pages per Session:</strong> " . ($enhancedStats['avgPagesPerSession'] ?? 0) . "</li>";
    echo "</ul>";
    
    // Device breakdown
    $deviceBreakdown = $analytics->getDeviceBreakdown($startDate, $endDate);
    echo "<h4>📱 Device Breakdown</h4>";
    echo "<ul>";
    foreach ($deviceBreakdown as $device) {
        echo "<li><strong>{$device['device']}:</strong> {$device['count']} visits ({$device['percentage']}%)</li>";
    }
    echo "</ul>";
    
    // Browser breakdown
    $browserBreakdown = $analytics->getBrowserBreakdown($startDate, $endDate);
    echo "<h4>🌐 Browser Breakdown</h4>";
    echo "<ul>";
    foreach ($browserBreakdown as $browser) {
        echo "<li><strong>{$browser['browser']}:</strong> {$browser['count']} visits ({$browser['percentage']}%)</li>";
    }
    echo "</ul>";
    
    // Top pages
    $topPages = $analytics->getTopPages(5, $startDate, $endDate);
    echo "<h4>📄 Top 5 Pages</h4>";
    if (!empty($topPages)) {
        echo "<ol>";
        foreach ($topPages as $page) {
            echo "<li><strong>{$page['page_url']}:</strong> {$page['count']} visits ({$page['percentage']}%)</li>";
        }
        echo "</ol>";
    } else {
        echo "<p><em>No page data available for this date range</em></p>";
    }
    
    echo "</div>";
    
    echo "<div class='demo-section success'>
        <h3>✅ All Analytics Components Support Date Ranges</h3>
        <p>The following components automatically filter data based on the selected date range:</p>
        <ul>
            <li>📊 Stat Cards (Total Visitors, Page Views, Session Duration, Bounce Rate)</li>
            <li>📱 Device Breakdown Chart</li>
            <li>🌐 Browser Breakdown Chart</li>
            <li>📄 Top Pages Table</li>
            <li>📈 Website Performance Chart</li>
        </ul>
    </div>";
    
    echo "<div class='demo-section info'>
        <h3>🔧 How It Works</h3>
        <p>When you change the date range in the admin dashboard:</p>
        <ol>
            <li>The <code>updateAllAnalytics()</code> function is called</li>
            <li>It sends the date range to <code>/admin/bin/get_all_analytics.php</code></li>
            <li>The backend filters all SQL queries with <code>WHERE DATE(visit_time) BETWEEN ? AND ?</code></li>
            <li>All dashboard components are updated with the filtered data</li>
        </ol>
    </div>";
    
} catch (Exception $e) {
    echo "<div class='demo-section warning'>
        <h3>❌ Error occurred</h3>
        <p>" . $e->getMessage() . "</p>
    </div>";
}

echo "</body></html>";
?> 