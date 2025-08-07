<?php
include("../../system/database.php");
require_once("../src/analytics.class.php");

header('Content-Type: application/json');

try {
    $analytics = new Analytics($pdo);
    
    // Get date range from request
    $input = json_decode(file_get_contents('php://input'), true);
    $startDate = $input['startDate'] ?? date('Y-m-01');
    $endDate = $input['endDate'] ?? date('Y-m-d');
    
    // Fetch all analytics data for the date range
    $stats = $analytics->getEnhancedStats($startDate, $endDate);
    $deviceBreakdown = $analytics->getDeviceBreakdown($startDate, $endDate);
    $browserBreakdown = $analytics->getBrowserBreakdown($startDate, $endDate);
    $topPages = $analytics->getTopPages(10, $startDate, $endDate);
    
    echo json_encode([
        'success' => true,
        'stats' => $stats,
        'deviceBreakdown' => $deviceBreakdown,
        'browserBreakdown' => $browserBreakdown,
        'topPages' => $topPages
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 