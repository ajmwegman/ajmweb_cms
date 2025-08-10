<?php
header('Content-Type: application/json');
$path = $_SERVER['DOCUMENT_ROOT'];
if (!isset($db)) {
    require_once($path . "/system/database.php");
    require_once($path . "/admin/src/database.class.php");
    $db = new database($pdo);
}
require_once($path . "/admin/modules/orders/src/orders.class.php");

$orders = new orders($pdo);
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$status = isset($_POST['status']) ? $_POST['status'] : '';
$success = false;
if ($id && $status !== '') {
    $success = $orders->updateStatus($id, $status);
}
echo json_encode(['success' => $success]);
