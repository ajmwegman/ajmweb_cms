<?php
include("../../system/database.php");
require_once("../src/database.class.php");
require_once("../src/analytics.class.php");

try {
    $db = new database($pdo);
    $analytics = new Analytics($pdo);

    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!$data) {
        throw new Exception("No JSON data received");
    }
    
    $startDate = $data['startDate'] ?? date('Y-m-01');
    $endDate = $data['endDate'] ?? date('Y-m-d');

    $result = $analytics->getEnhancedVisitorData($startDate, $endDate);

    header('Content-Type: application/json');
    echo json_encode($result);
    
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'error' => $e->getMessage(),
        'dates' => [],
        'visitors' => [],
        'pageViews' => [],
        'bounces' => []
    ]);
}
?> 