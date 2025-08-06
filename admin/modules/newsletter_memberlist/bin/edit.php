<?php
@session_start();

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . "/system/database.php");
require_once($path . "/admin/src/database.class.php");
require_once($path . "/admin/modules/newsletter_memberlist/src/module.class.php");

$db = new database($pdo);
$memberlist = new newsletter_memberlist($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim(strtolower($_POST['email']));

    // Basic validation
    if (empty($firstname) || empty($lastname) || empty($email)) {
        echo '<div class="alert alert-danger" role="alert">Alle velden zijn verplicht.</div>';
        exit;
    }

    // Check for duplicate email (excluding current id)
    if ($memberlist->checkDuplicateEmail($email, $id)) {
        echo '<div class="alert alert-danger" role="alert">Dit e-mailadres is al in gebruik.</div>';
        exit;
    }

    $go = $memberlist->updateMember($id, $firstname, $lastname, $email);

    if ($go) {
        echo '<div class="alert alert-success" role="alert">Gegevens succesvol bijgewerkt.</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Er is iets fout gegaan bij het bijwerken.</div>';
    }
}
?> 