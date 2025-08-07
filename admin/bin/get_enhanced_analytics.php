<?php
include("../../system/database.php");
require_once("../src/database.class.php");
require_once("../src/analytics.class.php");

$db = new database($pdo);
$analytics = new Analytics($pdo);

$data = json_decode(file_get_contents("php://input"), true);
$startDate = $data['startDate'];
$endDate = $data['endDate'];

$result = $analytics->getEnhancedVisitorData($startDate, $endDate);

header('Content-Type: application/json');
echo json_encode($result);
?> 