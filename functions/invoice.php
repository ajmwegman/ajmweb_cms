<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../src/Exception.php';
require_once __DIR__ . '/../src/PHPMailer.php';
require_once __DIR__ . '/../src/SMTP.php';
require_once __DIR__ . '/../src/site.class.php';

/**
 * Send an invoice email to the given user.
 *
 * Should be called immediately after generateRompslompInvoice().
 *
 * @param int $user_id    User identifier
 * @param int $invoice_id Invoice identifier
 * @return bool           True on success, false on failure
 */
function sendInvoiceEmail(int $user_id, int $invoice_id): bool
{
    global $pdo;

    if (!isset($pdo)) {
        return false;
    }

    // Haal gebruikersgegevens op
    $stmt = $pdo->prepare('SELECT firstname, surname, email FROM site_users WHERE id = :id');
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        return false;
    }

    // Haal factuurgegevens op
    $stmt = $pdo->prepare('SELECT invoice_number, total_amount, pdf_url FROM invoices WHERE id = :id');
    $stmt->execute(['id' => $invoice_id]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$invoice) {
        return false;
    }

    // Afzender gegevens ophalen
    $site = new site($pdo);
    $info = $site->getWebsiteInfo(1);
    $fromEmail = $info['std_mail'] ?? 'no-reply@example.com';
    $fromName  = $info['web_naam'] ?? 'Website';

    $mail = new PHPMailer(true);
    try {
        $fullName = trim($user['firstname'] . ' ' . $user['surname']);
        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($user['email'], $fullName);
        $mail->isHTML(true);

        $amount = number_format((float)$invoice['total_amount'], 2, ',', '.');
        $mail->Subject = "Your new invoice [#{$invoice['invoice_number']}] is ready";
        $mail->Body    = "Beste {$fullName},<br><br>Uw factuur #{$invoice['invoice_number']} van &euro; {$amount} is gereed. " .
            "<a href=\"{$invoice['pdf_url']}\">Download de PDF</a>" .
            "<br><br>Met vriendelijke groet,<br>{$fromName}";
        $mail->AltBody = "Beste {$fullName},\n\nUw factuur #{$invoice['invoice_number']} van EUR {$amount} is gereed. " .
            "Download de PDF: {$invoice['pdf_url']}\n\nMet vriendelijke groet,\n{$fromName}";

        $mail->send();
        $status = 'success';
        $logMessage = null;
    } catch (Exception $e) {
        $status = 'failed';
        $logMessage = $mail->ErrorInfo;
    }

    // Log het resultaat
    try {
        $log = $pdo->prepare('INSERT INTO email_logs (user_id, invoice_id, status, message, created_at) ' .
            'VALUES (:user_id, :invoice_id, :status, :message, NOW())');
        $log->execute([
            'user_id'   => $user_id,
            'invoice_id'=> $invoice_id,
            'status'    => $status,
            'message'   => $logMessage
        ]);
    } catch (PDOException $e) {
        // Logging is optioneel; fouten worden genegeerd
    }

    return $status === 'success';
}
