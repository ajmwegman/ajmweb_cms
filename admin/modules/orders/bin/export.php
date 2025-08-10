<?php
$path = $_SERVER['DOCUMENT_ROOT'];
if (!isset($db)) {
    require_once($path . "/system/database.php");
    require_once($path . "/admin/src/database.class.php");
    $db = new database($pdo);
}
require_once($path . "/admin/modules/orders/src/orders.class.php");

$orders = new orders($pdo);
$carrier = isset($_GET['carrier']) ? $_GET['carrier'] : 'postnl';
$list = $orders->getOrdersForCarrier($carrier);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $carrier . '_orders.csv"');

$fh = fopen('php://output', 'w');
fputcsv($fh, ['id', 'customer_name', 'address', 'zip', 'city', 'country']);
foreach ($list as $row) {
    fputcsv($fh, [
        $row['id'],
        $row['customer_name'],
        $row['address'],
        $row['zip'],
        $row['city'],
        $row['country']
    ]);
}
fclose($fh);
