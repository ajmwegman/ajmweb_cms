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
$userId = $users->getUserIdByHash($_SESSION['session_hash']);
$orders = $users->getUserWonAuctions($userId);
?>
<section class="mt-5">
    <div class="container mt-5">
        <h3>Bestellingen</h3>
        <?php if (!empty($orders)): ?>
        <ul>
            <?php foreach ($orders as $order): ?>
            <li><?= htmlspecialchars($order['title'] ?? '') ?></li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p>Geen bestellingen gevonden.</p>
        <?php endif; ?>
    </div>
</section>
?>
