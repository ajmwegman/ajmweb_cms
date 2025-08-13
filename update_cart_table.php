<?php
/**
 * Cart Data Update Script for AJMWEB CMS
 *
 * Creates the database table needed to store shopping cart data.
 * Run via browser: /update_cart_table.php
 * Or via command line: php update_cart_table.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
require_once('system/database.php');
require_once('src/database.class.php');

$db = new database($pdo);

echo "<h1>AJMWEB CMS Cart Update Script</h1>\n";
echo "<p>Start tijd: " . date('Y-m-d H:i:s') . "</p>\n";

$errors = [];
$success = [];

try {
    // Check if cart_items table exists
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'cart_items'");
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        $sql = "CREATE TABLE cart_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            session_id VARCHAR(128) NOT NULL,
            product_id INT NOT NULL,
            product_name VARCHAR(255) NOT NULL,
            quantity INT NOT NULL DEFAULT 1,
            price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            image_url VARCHAR(255) DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        $pdo->exec($sql);
        $success[] = "✅ Tabel cart_items aangemaakt";
    } else {
        $success[] = "ℹ️ Tabel cart_items bestaat al";
    }
} catch (Exception $e) {
    $errors[] = "❌ Database error: " . $e->getMessage();
}

echo "<h2>Update Resultaten</h2>\n";
echo "<h3>✅ Succesvol uitgevoerd:</h3>\n<ul>\n";
foreach ($success as $item) {
    echo "<li>{$item}</li>\n";
}
echo "</ul>\n";

if (!empty($errors)) {
    echo "<h3>❌ Fouten:</h3>\n<ul>\n";
    foreach ($errors as $error) {
        echo "<li>{$error}</li>\n";
    }
    echo "</ul>\n";
}

echo "<p><strong>Eind tijd:</strong> " . date('Y-m-d H:i:s') . "</p>\n";
if (empty($errors)) {
    echo "<p><strong>Status:</strong> ✅ Klaar</p>\n";
} else {
    echo "<p><strong>Status:</strong> ⚠️ Met waarschuwingen</p>\n";
}
?>
