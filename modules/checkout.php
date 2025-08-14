<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Mollie\Api\MollieApiClient;

$mollie = new MollieApiClient();
$mollie->setApiKey('test_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

$userId = $_SESSION['user_id'] ?? null;
$errors = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create_payment') {
    $email = trim($_POST['email'] ?? '');
    $cartJson = $_POST['cartData'] ?? '[]';
    $cart = json_decode($cartJson, true) ?: [];

    if (!$email) {
        $errors[] = 'E-mail is verplicht';
    }
    if (!$cart) {
        $errors[] = 'Winkelwagen is leeg';
    }

    if (!$errors) {
        $billing = [
            'street' => trim($_POST['billing_street'] ?? ''),
            'postal_code' => trim($_POST['billing_postal'] ?? ''),
            'city' => trim($_POST['billing_city'] ?? ''),
            'country' => trim($_POST['billing_country'] ?? ''),
        ];
        $shipping = [
            'street' => trim($_POST['shipping_street'] ?? ''),
            'postal_code' => trim($_POST['shipping_postal'] ?? ''),
            'city' => trim($_POST['shipping_city'] ?? ''),
            'country' => trim($_POST['shipping_country'] ?? ''),
        ];

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO orders (user_id,email,status,total_amount,billing_address,shipping_address,created_at) VALUES (?,?,?,?,?,?,NOW())");
            $stmt->execute([
                $userId,
                $email,
                'pending',
                $total,
                json_encode($billing),
                json_encode($shipping)
            ]);
            $orderId = $pdo->lastInsertId();
            $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id,product_id,name,price,quantity) VALUES (?,?,?,?,?)");
            foreach ($cart as $item) {
                $itemStmt->execute([
                    $orderId,
                    $item['id'] ?? null,
                    $item['name'] ?? '',
                    $item['price'] ?? 0,
                    $item['quantity'] ?? 1
                ]);
            }
            $payment = $mollie->payments->create([
                'amount' => [
                    'currency' => 'EUR',
                    'value' => number_format($total, 2, '.', ''),
                ],
                'description' => 'Order #' . $orderId,
                'redirectUrl' => sprintf('https://%s/modules/success.php?order_id=%s', $_SERVER['HTTP_HOST'], $orderId),
                'webhookUrl' => sprintf('https://%s/mollie_webhook.php', $_SERVER['HTTP_HOST']),
                'metadata' => [
                    'order_id' => $orderId,
                    'user_id' => $userId,
                ],
            ]);
            $pdo->prepare("UPDATE orders SET payment_id=? WHERE id=?")->execute([$payment->id, $orderId]);
            $pdo->commit();
            $_SESSION['order_id'] = $orderId;
            header('Location: ' . $payment->getCheckoutUrl());
            exit;
        } catch (\Exception $e) {
            $pdo->rollBack();
            $errors[] = $e->getMessage();
        }
    }
}
?>
<section class="mt-5">
  <div class="container">
    <h1>Checkout</h1>
    <?php if ($errors): ?>
    <div class="alert alert-danger">
      <ul>
        <?php foreach ($errors as $error): ?>
        <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>
    <form method="post" id="checkoutForm">
      <input type="hidden" name="action" value="create_payment">
      <input type="hidden" name="cartData" id="cartData">
      <div class="mb-3">
        <label>E-mail</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <h2>Factuuradres</h2>
      <div class="mb-3">
        <label>Straat</label>
        <input type="text" name="billing_street" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Postcode</label>
        <input type="text" name="billing_postal" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Stad</label>
        <input type="text" name="billing_city" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Land</label>
        <input type="text" name="billing_country" class="form-control" required>
      </div>
      <h2>Afleveradres</h2>
      <div class="mb-3">
        <label>Straat</label>
        <input type="text" name="shipping_street" class="form-control">
      </div>
      <div class="mb-3">
        <label>Postcode</label>
        <input type="text" name="shipping_postal" class="form-control">
      </div>
      <div class="mb-3">
        <label>Stad</label>
        <input type="text" name="shipping_city" class="form-control">
      </div>
      <div class="mb-3">
        <label>Land</label>
        <input type="text" name="shipping_country" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary">Betaal</button>
    </form>
  </div>
</section>
<script>
  document.getElementById('checkoutForm').addEventListener('submit', function(){
    document.getElementById('cartData').value = localStorage.getItem('cart') || '[]';
  });
</script>
