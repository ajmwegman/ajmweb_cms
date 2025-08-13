<?php
$path = $_SERVER['DOCUMENT_ROOT'];
if (!isset($db)) {
    require_once($path . "/system/database.php");
    require_once($path . "/admin/src/database.class.php");
    $db = new database($pdo);
}
require_once($path . "/admin/modules/orders/src/orders.class.php");

$orders = new orders($pdo);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;
$list = $orders->getOrders($offset, $perPage);
$total = $orders->countOrders();
$totalPages = $perPage > 0 ? ceil($total / $perPage) : 1;
?>
<table class="table">
  <thead>
    <tr>
      <th>ID</th>
      <th>Klant</th>
      <th>Status</th>
      <th>Factuur</th>
      <th>Acties</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($list as $order) { ?>
    <tr>
      <td><?php echo $order['id']; ?></td>
      <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
      <td>
        <select class="form-select form-select-sm status-select" data-id="<?php echo $order['id']; ?>">
          <?php $statuses = ['new','processing','shipped','completed'];
          foreach ($statuses as $s) { ?>
            <option value="<?php echo $s; ?>" <?php if ($order['status'] === $s) echo 'selected'; ?>><?php echo ucfirst($s); ?></option>
          <?php } ?>
        </select>
      </td>
      <td><a href="<?php echo $order['invoice_pdf']; ?>" target="_blank">Download</a></td>
      <td></td>
    </tr>
  <?php } ?>
  </tbody>
</table>
<nav>
  <ul class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
      <li class="page-item<?php if ($i == $page) echo ' active'; ?>">
        <a class="page-link" href="#" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
      </li>
    <?php } ?>
  </ul>
</nav>
