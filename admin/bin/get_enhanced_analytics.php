<?php
include("../../system/database.php");
require_once("../src/analytics.class.php");

header('Content-Type: application/json');

try {
    $analytics = new Analytics($pdo);

    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!$data) {
        throw new Exception("No JSON data received");
    }
    
    $startDate = $data['startDate'] ?? date('Y-m-01');
    $endDate = $data['endDate'] ?? date('Y-m-d');

    // Validate dates
    if (!$startDate || !$endDate) {
        throw new Exception("Start and end dates are required");
    }

    if ($startDate > $endDate) {
        throw new Exception("Start date must be before end date");
    }

    $result = $analytics->getEnhancedVisitorData($startDate, $endDate);

    // Check if there's an error in the result
    if (isset($result['error'])) {
        throw new Exception($result['error']);
    }

    // Ensure we have the required data structure
    if (!isset($result['dates']) || !isset($result['visitors']) || !isset($result['pageViews']) || !isset($result['bounces'])) {
        // Return empty data structure if no data available
        echo json_encode([
            'dates' => [],
            'visitors' => [],
            'pageViews' => [],
            'bounces' => []
        ]);
    } else {
        echo json_encode($result);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage(),
        'dates' => [],
        'visitors' => [],
        'pageViews' => [],
        'bounces' => []
    ]);
}
?> 