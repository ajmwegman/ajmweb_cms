<?php
session_start();

error_reporting(E_ALL ^ E_DEPRECATED);
ini_set("display_errors", 1);

require_once __DIR__ . "/../system/database.php";
require_once __DIR__ . "/../src/database.class.php";

function logMessage(string $message): void {
    $logFile = __DIR__ . '/billing_log.txt';
    file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . "] " . $message . PHP_EOL, FILE_APPEND);
}

$today = date('Y-m-d');

try {
    $sql = "SELECT sm.*, s.user_id FROM site_modules sm INNER JOIN sites s ON sm.site_id = s.id WHERE sm.is_active = 1 AND sm.auto_renew = 1 AND sm.end_date = :today";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['today' => $today]);
    $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($modules as $module) {
        try {
            $pdo->beginTransaction();

            $update = $pdo->prepare("UPDATE site_modules SET end_date = DATE_ADD(end_date, INTERVAL 1 MONTH) WHERE id = :id AND end_date = :today AND is_active = 1 AND auto_renew = 1");
            $update->execute(['id' => $module['id'], 'today' => $today]);

            if ($update->rowCount() === 0) {
                $pdo->rollBack();
                logMessage("Skipped module {$module['id']} - already processed.");
                continue;
            }

            $invoiceStmt = $pdo->prepare("INSERT INTO invoices (user_id, site_id, created_at, status) VALUES (:user_id, :site_id, NOW(), 'open')");
            $invoiceStmt->execute([
                'user_id' => $module['user_id'],
                'site_id' => $module['site_id']
            ]);
            $invoiceId = $pdo->lastInsertId();

            $itemStmt = $pdo->prepare("INSERT INTO invoice_items (invoice_id, description, price) VALUES (:invoice_id, :description, :price)");
            $itemStmt->execute([
                'invoice_id' => $invoiceId,
                'description' => $module['module_name'] ?? 'Module ' . $module['id'],
                'price' => $module['price'] ?? 0
            ]);

            $transactionStmt = $pdo->prepare("INSERT INTO transactions (user_id, invoice_id, status, created_at) VALUES (:user_id, :invoice_id, 'success', NOW())");
            $transactionStmt->execute([
                'user_id' => $module['user_id'],
                'invoice_id' => $invoiceId
            ]);

            if (function_exists('generateRompslompInvoice')) {
                try {
                    generateRompslompInvoice($module['user_id'], $invoiceId);
                } catch (Exception $e) {
                    logMessage("Invoice API failed for module {$module['id']}: " . $e->getMessage());
                }
            }

            $pdo->commit();
            logMessage("Successfully renewed module {$module['id']} for site {$module['site_id']}.");
        } catch (Exception $e) {
            $pdo->rollBack();
            logMessage("Error processing module {$module['id']}: " . $e->getMessage());
        }
    }
} catch (Exception $e) {
    logMessage('General error: ' . $e->getMessage());
}

?>
