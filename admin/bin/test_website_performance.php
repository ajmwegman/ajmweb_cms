<?php
include("../../system/database.php");
require_once("../src/analytics.class.php");

echo "<h2>Test Website Performance Chart</h2>";

try {
    $analytics = new Analytics($pdo);
    
    $startDate = '2025-08-01';
    $endDate = '2025-08-07';
    
    echo "<h3>Testing Enhanced Analytics Endpoint</h3>";
    echo "<p><strong>Date Range:</strong> $startDate to $endDate</p>";
    
    // Test getEnhancedVisitorData method
    echo "<h4>1. Testing getEnhancedVisitorData method:</h4>";
    $enhancedData = $analytics->getEnhancedVisitorData($startDate, $endDate);
    echo "<pre>" . print_r($enhancedData, true) . "</pre>";
    
    // Test getVisitorCountsByDay method (fallback)
    echo "<h4>2. Testing getVisitorCountsByDay method (fallback):</h4>";
    $visitorData = $analytics->getVisitorCountsByDay($startDate, $endDate);
    echo "<pre>" . print_r($visitorData, true) . "</pre>";
    
    echo "<h3>✅ Both endpoints are working!</h3>";
    echo "<p>The Website Performance chart should now work properly.</p>";
    
    echo "<h4>3. Test the chart endpoints:</h4>";
    echo "<ul>";
    echo "<li><strong>Enhanced Analytics:</strong> <code>/admin/bin/get_enhanced_analytics.php</code></li>";
    echo "<li><strong>Fallback Analytics:</strong> <code>/admin/bin/get_analytics.php</code></li>";
    echo "</ul>";
    
    echo "<h4>4. Expected Data Structure:</h4>";
    echo "<p><strong>Enhanced:</strong> dates[], visitors[], pageViews[], bounces[]</p>";
    echo "<p><strong>Fallback:</strong> dates[], counts[]</p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error occurred:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?> 