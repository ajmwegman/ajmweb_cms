<?php
session_start();
$sessid = session_id();

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once("../../system/database.php");

require_once("../../src/database.class.php");
require_once("../../src/users.class.php");

$users = new users($pdo); // Maak een instantie van de users class

$userId = $users->getUserIdByHash($_SESSION['session_hash']);
$new_password = $_POST['new_password']; // Het nieuwe wachtwoord
$confirm_password = $_POST['confirm_password']; // Bevestig het nieuwe wachtwoord

// Controleer of alle velden zijn ingevuld
if (!empty($new_password) && !empty($confirm_password)) {
    // Controleer of het nieuwe wachtwoord aan de criteria voldoet
    if (preg_match('/^(?=.*\d.*\d)(?=.*[^\w\d\s])(?=.*[^\w\s]).{8,}$/', $new_password)) {
        // Controleer of het nieuwe wachtwoord overeenkomt met het bevestigde wachtwoord
        if ($new_password === $confirm_password) {
            // Hash het nieuwe wachtwoord
            $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

            // Roep de updatePassword methode aan om het wachtwoord bij te werken
            try {
                $users->updatePassword($userId, $hashedPassword);
                echo "<div class='alert alert-success' role='alert'>Wachtwoord is succesvol bijgewerkt! U wordt nu uitgelogd.</div>";
            } catch (Exception $e) {
                echo "<div class='alert alert-danger' role='alert'>Er is een fout opgetreden bij het bijwerken van het wachtwoord.</div>";
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>De ingevoerde wachtwoorden komen niet overeen.</div>";
        }
    } else {
        echo "<div class='alert alert-danger' role='alert'>Het nieuwe wachtwoord moet minimaal 8 tekens bevatten, met minstens 2 cijfers en 2 vreemde tekens.</div>";
    }
} else {
    echo "<div class='alert alert-danger' role='alert'>Alle velden moeten worden ingevuld.</div>";
}
?>
