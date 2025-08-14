<?php
@session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . "/system/database.php";
require_once $path . "/src/users.class.php";

$usersClass = new users($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = (int)($_POST['user_id'] ?? 0);
    $role = $_POST['role'] ?? '';

    if ($usersClass->updateUserRole($userId, $role)) {
        echo '<div class="alert alert-success" role="alert">Rol bijgewerkt.</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Ongeldige rol.</div>';
    }
}
?>
