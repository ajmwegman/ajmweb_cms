<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Mollie\Api\MollieApiClient;

$mollie = new MollieApiClient();
$mollie->setApiKey('test_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

$orderId = $_GET['order_id'] ?? ($_SESSION['order_id'] ?? null);
$order = null;
$status = 'onbekend';

if ($orderId) {
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();
    if ($order) {
        $status = $order['status'];
        if ($status !== 'betaald' && !empty($order['payment_id'])) {
            try {
                $payment = $mollie->payments->get($order['payment_id']);
                if ($payment->isPaid()) {
                    $pdo->prepare("UPDATE orders SET status='betaald', paid_at=NOW() WHERE id=?")->execute([$orderId]);
                    $status = 'betaald';
                }
            } catch (Exception $e) {
                $status = $order['status'];
            }
        }
    }
}
?>
<section class="mt-5">
  <div class="container">
    <?php if ($status === 'betaald'): ?>
      <h1>Bedankt voor uw bestelling!</h1>
      <p>Uw betaling is ontvangen.</p>
    <?php else: ?>
      <h1>Betaling in behandeling</h1>
      <p>De status van uw bestelling is: <?php echo htmlspecialchars($status); ?></p>
    <?php endif; ?>
  </div>
</section>
<script>
  localStorage.removeItem('cart');
</script>
