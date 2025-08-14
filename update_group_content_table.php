<?php
/**
 * Update script to extend group_content table with meta fields and status.
 * Run via browser or CLI: /update_group_content_table.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'system/database.php';
require_once 'src/database.class.php';

$db = new database($pdo);

$success = [];
$errors = [];

try {
    // meta_title
    $stmt = $pdo->prepare("SHOW COLUMNS FROM group_content LIKE 'meta_title'");
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE group_content ADD COLUMN meta_title VARCHAR(255) DEFAULT NULL");
        $success[] = "✅ Kolom meta_title toegevoegd";
    } else {
        $success[] = "ℹ️ Kolom meta_title bestaat al";
    }

    // meta_description
    $stmt = $pdo->prepare("SHOW COLUMNS FROM group_content LIKE 'meta_description'");
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE group_content ADD COLUMN meta_description TEXT DEFAULT NULL");
        $success[] = "✅ Kolom meta_description toegevoegd";
    } else {
        $success[] = "ℹ️ Kolom meta_description bestaat al";
    }

    // status column
    $stmt = $pdo->prepare("SHOW COLUMNS FROM group_content LIKE 'status'");
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE group_content ADD COLUMN status ENUM('draft','published') NOT NULL DEFAULT 'draft'");
        $success[] = "✅ Kolom status toegevoegd";
    } else {
        $pdo->exec("ALTER TABLE group_content MODIFY status ENUM('draft','published') NOT NULL DEFAULT 'draft'");
        $success[] = "ℹ️ Kolom status bijgewerkt";
    }
} catch (Exception $e) {
    $errors[] = $e->getMessage();
}

header('Content-Type: text/html; charset=utf-8');
echo "<h1>Group content update</h1>";
echo "<h3>Resultaten</h3><ul>";
foreach ($success as $msg) {
    echo "<li>{$msg}</li>";
}
echo "</ul>";
if ($errors) {
    echo "<h3>Fouten</h3><ul>";
    foreach ($errors as $err) {
        echo "<li>{$err}</li>";
    }
    echo "</ul>";
}
?>
