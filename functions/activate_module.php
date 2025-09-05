<?php
function activateModuleForSite(PDO $pdo, int $user_id, int $site_id, int $module_id): bool {
    try {
        $pdo->beginTransaction();

        $startDate = new DateTime();
        $endDate = (clone $startDate)->modify('+1 month');

        // Check if module already active for site
        $stmt = $pdo->prepare('SELECT id FROM site_modules WHERE site_id = :site_id AND module_id = :module_id');
        $stmt->execute([
            'site_id' => $site_id,
            'module_id' => $module_id
        ]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // Update existing record
            $stmt = $pdo->prepare('UPDATE site_modules SET start_date = :start_date, end_date = :end_date, is_active = 1, auto_renew = 1 WHERE id = :id');
            $stmt->execute([
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'id' => $existing['id']
            ]);
            $siteModuleId = $existing['id'];
        } else {
            // Insert new record
            $stmt = $pdo->prepare('INSERT INTO site_modules (site_id, module_id, start_date, end_date, is_active, auto_renew) VALUES (:site_id, :module_id, :start_date, :end_date, 1, 1)');
            $stmt->execute([
                'site_id' => $site_id,
                'module_id' => $module_id,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d')
            ]);
            $siteModuleId = $pdo->lastInsertId();
        }

        // Create invoice
        $stmt = $pdo->prepare('INSERT INTO invoices (user_id, site_id, created_at, total) VALUES (:user_id, :site_id, NOW(), 0)');
        $stmt->execute([
            'user_id' => $user_id,
            'site_id' => $site_id
        ]);
        $invoiceId = $pdo->lastInsertId();

        // Fetch module info for invoice item
        $stmt = $pdo->prepare('SELECT name, price FROM modules WHERE id = :module_id');
        $stmt->execute(['module_id' => $module_id]);
        $module = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$module) {
            throw new Exception('Module not found');
        }

        // Insert invoice item
        $stmt = $pdo->prepare('INSERT INTO invoice_items (invoice_id, description, price, quantity, module_id, site_module_id) VALUES (:invoice_id, :description, :price, 1, :module_id, :site_module_id)');
        $stmt->execute([
            'invoice_id' => $invoiceId,
            'description' => $module['name'],
            'price' => $module['price'],
            'module_id' => $module_id,
            'site_module_id' => $siteModuleId
        ]);

        // Update invoice total
        $stmt = $pdo->prepare('UPDATE invoices SET total = total + :amount WHERE id = :invoice_id');
        $stmt->execute([
            'amount' => $module['price'],
            'invoice_id' => $invoiceId
        ]);

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        return false;
    }
}
?>
