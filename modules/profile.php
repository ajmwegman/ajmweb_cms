<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: /login.php");
    exit();
}
require_once($_SERVER['DOCUMENT_ROOT'] . "/system/database.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/src/database.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/src/users.class.php");
$users = new users($pdo);
$userData = $users->getUserDataByUserId($_SESSION['session_hash']);
?>
<section class="mt-5">
    <div class="container mt-5">
        <h3>Profiel</h3>
        <p>E-mailadres: <?= htmlspecialchars($userData['email'] ?? '') ?></p>
        <h4 class="mt-4">Factuuradres</h4>
        <p><?= htmlspecialchars($userData['street'] ?? '') ?>, <?= htmlspecialchars($userData['postal_code'] ?? '') ?> <?= htmlspecialchars($userData['city'] ?? '') ?></p>
        <?php if (!empty($userData['shipping_street'])): ?>
        <h4 class="mt-4">Verzendadres</h4>
        <p><?= htmlspecialchars($userData['shipping_street']) ?>, <?= htmlspecialchars($userData['shipping_postal_code']) ?> <?= htmlspecialchars($userData['shipping_city']) ?></p>
        <?php endif; ?>
    </div>
</section>
?>
