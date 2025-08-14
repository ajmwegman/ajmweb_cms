<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use Mollie\Api\MollieApiClient;

$mollie = new MollieApiClient();
$mollie->setApiKey('test_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

$paymentId = $_POST['id'] ?? null;
if ($paymentId) {
    try {
        $payment = $mollie->payments->get($paymentId);

        if ($payment->isPaid()) {
            $orderId = $payment->metadata->order_id ?? null;
            if ($orderId) {
                $stmt = $pdo->prepare("UPDATE orders SET status='betaald', paid_at=NOW() WHERE id=?");
                $stmt->execute([$orderId]);
            }
        }
    } catch (\Exception $e) {
        error_log($e->getMessage());
    }
}
http_response_code(200);
