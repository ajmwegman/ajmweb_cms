<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . '/system/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id) {
    $stmt = $pdo->prepare('DELETE FROM products WHERE id=?');
    $stmt->execute([$id]);
}
header('Location: ?module=products&action=list');
exit;
?>
