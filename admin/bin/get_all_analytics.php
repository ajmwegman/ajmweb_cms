<?php
include("../../system/database.php");
require_once("../src/analytics.class.php");

header('Content-Type: application/json');

try {
    $analytics = new Analytics($pdo);
    
    // Get date range and site_id from request
    $input = json_decode(file_get_contents('php://input'), true);
    $startDate = $input['startDate'] ?? date('Y-m-01');
    $endDate = $input['endDate'] ?? date('Y-m-d');
    $siteId = $input['siteId'] ?? null;
    
    // Fetch all analytics data for the date range and site
    $stats = $analytics->getEnhancedStats($startDate, $endDate, $siteId);
    $deviceBreakdown = $analytics->getDeviceBreakdown($startDate, $endDate, $siteId);
    $browserBreakdown = $analytics->getBrowserBreakdown($startDate, $endDate, $siteId);
    $topPages = $analytics->getTopPages(10, $startDate, $endDate, $siteId);
    
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