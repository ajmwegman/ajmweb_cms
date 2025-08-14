<?php
$path = $_SERVER['DOCUMENT_ROOT'];
if (!isset($db)) {
    require_once($path . "/system/database.php");
    require_once($path . "/admin/src/database.class.php");
    $db = new database($pdo);
}
require_once($path . "/src/analytics.php");

$analytics = new AdvancedAnalytics($pdo);

$start = $_GET['start'] ?? null;
$end = $_GET['end'] ?? null;
$category = $_GET['category'] ?? null;
$channelFilter = $_GET['channel'] ?? null;

// Visits per channel based on referer_url
$visitSql = "SELECT referer_url AS channel, COUNT(*) AS visits FROM analytics WHERE 1=1";
$visitParams = [];
if ($start && $end) {
    $visitSql .= " AND DATE(visit_time) BETWEEN :start AND :end";
    $visitParams[':start'] = $start;
    $visitParams[':end'] = $end;
}
if ($channelFilter) {
    $visitSql .= " AND referer_url LIKE :channel";
    $visitParams[':channel'] = '%' . $channelFilter . '%';
}
$visitSql .= " GROUP BY referer_url";
$visitStmt = $pdo->prepare($visitSql);
$visitStmt->execute($visitParams);
$visitRows = $visitStmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Revenue per channel from orders table
$orderSql = "SELECT channel, SUM(total_amount) AS revenue FROM orders WHERE 1=1";
$orderParams = [];
if ($start && $end) {
    $orderSql .= " AND DATE(created_at) BETWEEN :start AND :end";
    $orderParams[':start'] = $start;
    $orderParams[':end'] = $end;
}
if ($category) {
    $orderSql .= " AND category = :category";
    $orderParams[':category'] = $category;
}
if ($channelFilter) {
    $orderSql .= " AND channel = :channel";
    $orderParams[':channel'] = $channelFilter;
}
$orderSql .= " GROUP BY channel";
$orderStmt = $pdo->prepare($orderSql);
$orderStmt->execute($orderParams);
$orderRows = $orderStmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Combine channels
$channels = array_unique(array_merge(array_keys($visitRows), array_keys($orderRows)));
$labels = [];
$visits = [];
$revenue = [];
foreach ($channels as $c) {
    $labels[] = $c ?: 'unknown';
    $visits[] = isset($visitRows[$c]) ? (int)$visitRows[$c] : 0;
    $revenue[] = isset($orderRows[$c]) ? (float)$orderRows[$c] : 0;
}

header('Content-Type: application/json');
echo json_encode([
    'labels' => $labels,
    'visits' => $visits,
    'revenue' => $revenue,
    'stats' => $analytics->getStats($start, $end)
]);
