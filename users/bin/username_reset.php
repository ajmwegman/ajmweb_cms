<?php
session_start();
$sessid = session_id();

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once("../../system/database.php");

require_once("../../src/database.class.php");
require_once("../../src/users.class.php");

$users = new users($pdo); // Maak een instantie van de users class

$new_username = $_POST['new_username']; // De nieuwe gebruikersnaam

// Controleer of alle velden zijn ingevuld en of het een geldig e-mailadres is
if (!empty($new_username) && filter_var($new_username, FILTER_VALIDATE_EMAIL)) {
    // Controleer of het nieuwe gebruikersnaam uniek is in de database
    $user = $users->getUserByUsername($new_username);

    if ($user) {
        // Als de gebruikersnaam al bestaat, geef een foutmelding
        echo "<div class='alert alert-danger' role='alert'>Dit e-mailadres is al in gebruik.</div>";
    } else {
        // Voer hier de logica uit om de gebruikersnaam bij te werken
        $userId = $users->getUserIdByHash($_SESSION['session_hash']);
        $update = $users->updateUsername($userId, $new_username);

        if ($update) {
            // Als het bijwerken van de gebruikersnaam succesvol is, geef een succesmelding
            echo "<div class='alert alert-success' role='alert'>Gebruikersnaam is succesvol bijgewerkt!</div>";
        } else {
            // Als er een fout optreedt bij het bijwerken van de gebruikersnaam, geef een foutmelding
            echo "<div class='alert alert-danger' role='alert'>Er is een fout opgetreden bij het bijwerken van de gebruikersnaam.</div>";
        }
    }
} else {
    // Geef een foutmelding als de nieuwe gebruikersnaam leeg is of geen geldig e-mailadres is
    echo "<div class='alert alert-danger' role='alert'>Voer een geldig e-mailadres in.</div>";
}
?>
