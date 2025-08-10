<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use Mollie\Api\MollieApiClient;

$mollie = new MollieApiClient();
// Gebruik een test API key van Mollie. Vervang deze met een live key in productie.
$mollie->setApiKey('test_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

$userId = $_SESSION['user_id'] ?? null;
$errors = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create_payment') {
    $email = trim($_POST['email'] ?? '');
    $amount = trim($_POST['amount'] ?? '10.00');

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

    if (!$email) {
        $errors[] = 'E-mail is verplicht';
    }

    if (!$errors) {
        try {
            $payment = $mollie->payments->create([
                'amount' => [
                    'currency' => 'EUR',
                    'value' => number_format((float)$amount, 2, '.', ''),
                ],
                'description' => 'Onepage checkout order',
                'redirectUrl' => sprintf('https://%s/checkout.php?status=return', $_SERVER['HTTP_HOST']),
                'webhookUrl' => sprintf('https://%s/mollie_webhook.php', $_SERVER['HTTP_HOST']),
                'metadata' => [
                    'email' => $email,
                    'billing' => $billing,
                    'shipping' => $shipping,
                    'user_id' => $userId,
                ],
            ]);

            $_SESSION['payment_id'] = $payment->id;
            header('Location: ' . $payment->getCheckoutUrl());
            exit;
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}

if (($_GET['status'] ?? '') === 'return' && isset($_SESSION['payment_id'])) {
    try {
        $payment = $mollie->payments->get($_SESSION['payment_id']);
        if ($payment->isPaid()) {
            $message = 'Betaling geslaagd. Bedankt!';
        } else {
            $message = 'Betaling status: ' . htmlspecialchars($payment->status);
        }
    } catch (\Exception $e) {
        $statusFile = __DIR__ . '/uploads/payment_' . $_SESSION['payment_id'] . '.json';
        if (file_exists($statusFile)) {
            $data = json_decode(file_get_contents($statusFile), true);
            $message = 'Betaling status: ' . htmlspecialchars($data['status'] ?? 'onbekend');
        } else {
            $errors[] = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2em; }
        .error { color: red; }
        .field { margin-bottom: 1em; }
        .hidden { display: none; }
    </style>
    <script>
    function toggleShipping() {
        const useBilling = document.getElementById('use_billing').checked;
        document.getElementById('shipping_fields').style.display = useBilling ? 'none' : 'block';
    }
    </script>
</head>
<body onload="toggleShipping()">
<h1>Onepage Checkout</h1>
<?php if ($userId): ?>
<p>Ingelogd als gebruiker <?php echo htmlspecialchars($userId); ?></p>
<?php else: ?>
<p>Gast afrekenen</p>
<?php endif; ?>
<?php if ($errors): ?>
    <div class="error">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?php if ($message): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>
<form method="post">
    <input type="hidden" name="action" value="create_payment">
    <div class="field">
        <label>E-mail<br><input type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? '') ?>"></label>
    </div>
    <h2>Factuuradres</h2>
    <div class="field">
        <label>Straat<br><input type="text" name="billing_street" required value="<?php echo htmlspecialchars($_POST['billing_street'] ?? '') ?>"></label>
    </div>
    <div class="field">
        <label>Postcode<br><input type="text" name="billing_postal" required value="<?php echo htmlspecialchars($_POST['billing_postal'] ?? '') ?>"></label>
    </div>
    <div class="field">
        <label>Stad<br><input type="text" name="billing_city" required value="<?php echo htmlspecialchars($_POST['billing_city'] ?? '') ?>"></label>
    </div>
    <div class="field">
        <label>Land<br><input type="text" name="billing_country" required value="<?php echo htmlspecialchars($_POST['billing_country'] ?? '') ?>"></label>
    </div>
    <div class="field">
        <label><input type="checkbox" id="use_billing" name="use_billing" value="1" <?php echo isset($_POST['use_billing']) ? 'checked' : '' ?> onchange="toggleShipping()"> Verzenden naar factuuradres</label>
    </div>
    <div id="shipping_fields">
        <h2>Afleveradres</h2>
        <div class="field">
            <label>Straat<br><input type="text" name="shipping_street" value="<?php echo htmlspecialchars($_POST['shipping_street'] ?? '') ?>"></label>
        </div>
        <div class="field">
            <label>Postcode<br><input type="text" name="shipping_postal" value="<?php echo htmlspecialchars($_POST['shipping_postal'] ?? '') ?>"></label>
        </div>
        <div class="field">
            <label>Stad<br><input type="text" name="shipping_city" value="<?php echo htmlspecialchars($_POST['shipping_city'] ?? '') ?>"></label>
        </div>
        <div class="field">
            <label>Land<br><input type="text" name="shipping_country" value="<?php echo htmlspecialchars($_POST['shipping_country'] ?? '') ?>"></label>
        </div>
    </div>
    <div class="field">
        <label>Bedrag (EUR)<br><input type="number" step="0.01" name="amount" value="<?php echo htmlspecialchars($_POST['amount'] ?? '10.00') ?>"></label>
    </div>
    <button type="submit">Betaal</button>
</form>
</body>
</html>
