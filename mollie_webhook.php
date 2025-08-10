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
        // Sla status op in een eenvoudig bestand zodat deze later kan worden opgehaald
        $statusFile = __DIR__ . '/uploads/payment_' . $paymentId . '.json';
        file_put_contents($statusFile, json_encode([
            'status' => $payment->status,
            'amount' => $payment->amount->value,
            'updated' => date('c'),
        ]));
    } catch (\Exception $e) {
        // Eventuele fouten loggen
        error_log($e->getMessage());
    }
}
http_response_code(200);
