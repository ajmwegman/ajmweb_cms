<?php
$path = $_SERVER['DOCUMENT_ROOT'];
if (!isset($db)) {
    require_once($path . "/system/database.php");
    require_once($path . "/admin/src/database.class.php");
    $db = new database($pdo);
}
require_once($path . "/admin/modules/orders/src/orders.class.php");

$orders = new orders($pdo);
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$order = $orders->getOrder($id);
if (!$order) {
    echo "<p>Bestelling niet gevonden</p>";
    exit;
}
?>
<h3>Order #<?php echo $order['id']; ?></h3>
<ul>
  <li>Klant: <?php echo htmlspecialchars($order['customer_name']); ?></li>
  <li>Status: <?php echo htmlspecialchars($order['status']); ?></li>
  <li>Totaal: <?php echo htmlspecialchars($order['total']); ?></li>
  <li>Factuur: <a href="<?php echo $order['invoice_pdf']; ?>" target="_blank">Download</a></li>
</ul>
