<?php
// Simple router for products admin module
$action = isset($action) ? $action : ($_GET['action'] ?? 'list');

switch ($action) {
    case 'edit':
        require __DIR__ . '/edit.php';
        break;
    case 'delete':
        require __DIR__ . '/delete.php';
        break;
    default:
        require __DIR__ . '/list.php';
        break;
}
?>
