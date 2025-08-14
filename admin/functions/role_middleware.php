<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Standaard vereiste rol is 'admin'
$requiredRole = $requiredRole ?? 'admin';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== $requiredRole) {
    header('Location: /admin/login.php');
    exit;
}
?>
