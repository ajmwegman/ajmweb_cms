<?php
include("../../system/database.php");
require_once("../src/analytics.class.php");

echo "<h2>Test SQL Parameter Binding Fixes</h2>";

try {
    $analytics = new Analytics($pdo);
    
    $startDate = '2025-08-01';
    $endDate = '2025-08-07';
    
    echo "<h3>Testing all methods with date range:</h3>";
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
    
    // Test top referrers
    echo "<h4>3. Top Referrers:</h4>";
    $topReferrers = $analytics->getTopReferrers(5, $startDate, $endDate);
    echo "<pre>" . print_r($topReferrers, true) . "</pre>";
    
    // Test top pages
    echo "<h4>4. Top Pages:</h4>";
    $topPages = $analytics->getTopPages(10, $startDate, $endDate);
    echo "<pre>" . print_r($topPages, true) . "</pre>";
    
    // Test conversion rate
    echo "<h4>5. Conversion Rate:</h4>";
    $conversionRate = $analytics->getConversionRate($startDate, $endDate);
    echo "<p>Conversion Rate: $conversionRate%</p>";
    
    echo "<h3>✅ All SQL parameter binding issues fixed!</h3>";
    echo "<p>The analytics methods should now work without SQL parameter errors.</p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error occurred:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<p><strong>Stack trace:</strong></p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?> 