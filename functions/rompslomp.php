<?php
/**
 * Generate invoice in Rompslomp using REST API.
 *
 * @param int $user_id    Local user identifier
 * @param int $invoice_id Local invoice identifier
 * @return array|false    Returns Rompslomp invoice data on success, false on failure
 */
function generateRompslompInvoice($user_id, $invoice_id)
{
    // Use existing PDO connection from the CMS
    global $pdo;

    if (!$pdo) {
        return false;
    }

    // ---- Load user data ----
    $stmt = $pdo->prepare('SELECT * FROM site_users WHERE id = :id');
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        return false;
    }

    // ---- Ensure Rompslomp contact ----
    $contactId = $user['rompslomp_contact_id'] ?? null;
    if (!$contactId) {
        $contactData = [
            'name'  => $user['company_name'] ?? ($user['email'] ?? 'Unknown'),
            'email' => $user['email'] ?? '',
        ];
        $response = rompslompApiPost('/contacts', $contactData);
        if (!$response || empty($response['id'])) {
            return false;
        }
        $contactId = $response['id'];
        // Save contact id locally
        $stmt = $pdo->prepare('UPDATE site_users SET rompslomp_contact_id = :cid WHERE id = :id');
        $stmt->execute(['cid' => $contactId, 'id' => $user_id]);
    }

    // ---- Load invoice data ----
    $stmt = $pdo->prepare('SELECT * FROM invoices WHERE id = :id');
    $stmt->execute(['id' => $invoice_id]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$invoice) {
        return false;
    }

    $invoiceDate = $invoice['date'] ?? date('Y-m-d');
    $dueDate = $invoice['due_date'] ?? date('Y-m-d', strtotime($invoiceDate . ' +14 days'));

    // ---- Load invoice line items ----
    $stmt = $pdo->prepare('SELECT * FROM invoice_items WHERE invoice_id = :id');
    $stmt->execute(['id' => $invoice_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$items) {
        return false;
    }

    $lines = [];
    foreach ($items as $item) {
        $lines[] = [
            'description' => $item['module_name'] ?? 'Item',
            'price'       => (float)($item['price'] ?? 0),
            'quantity'    => (int)($item['quantity'] ?? 1),
            'tax_rate'    => 21,
        ];
    }

    // ---- Create invoice on Rompslomp ----
    $payload = [
        'contact_id' => $contactId,
        'date'       => $invoiceDate,
        'due_date'   => $dueDate,
        'lines'      => $lines,
    ];

    $result = rompslompApiPost('/invoices', $payload);
    if (!$result || empty($result['id'])) {
        return false;
    }

    // ---- Save Rompslomp invoice data locally ----
    $update = $pdo->prepare('UPDATE invoices SET invoice_number = :number, rompslomp_invoice_id = :rid, pdf_url = :pdf WHERE id = :id');
    $update->execute([
        'number' => $result['invoice_number'] ?? null,
        'rid'    => $result['id'],
        'pdf'    => $result['pdf_url'] ?? null,
        'id'     => $invoice_id,
    ]);

    return $result;
}

/**
 * Helper for POST requests to the Rompslomp API.
 *
 * @param string $path
 * @param array  $data
 * @return array|false
 */
function rompslompApiPost(string $path, array $data)
{
    $token = defined('ROMPSLOMP_API_TOKEN') ? ROMPSLOMP_API_TOKEN : 'YOUR_ROMPSLOMP_API_TOKEN';
    $url = 'https://api.rompslomp.nl/v1' . $path;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    if ($response === false) {
        curl_close($ch);
        return false;
    }
    curl_close($ch);
    $decoded = json_decode($response, true);
    return is_array($decoded) ? $decoded : false;
}
?>
