<?php
@session_start();

error_reporting(E_ALL ^ E_DEPRECATED);
ini_set("display_errors", 1);

$path = $_SERVER['DOCUMENT_ROOT'];

// Importeer de benodigde bestanden voor de databaseverbinding
require_once($path . "/system/database.php");
require_once($path . "/admin/src/database.class.php");

// Gebruik de bestaande PDO-verbinding
$db = new database($pdo);

try {
    // Haal alle sliders op uit de database met de bestaande PDO-verbinding
    $sql = "SELECT id, name FROM group_photoslider_names ORDER BY name ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $sliders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Controleer of de query resultaten heeft opgeleverd
    if ($sliders && is_array($sliders) && count($sliders) > 0) {
        // Genereer en echo de opties voor de dropdown
        foreach ($sliders as $slider) {
            echo '<option value="' . htmlspecialchars($slider['id']) . '">' . htmlspecialchars($slider['name']) . '</option>';
        }
    } else {
        echo '<option value="">Geen sliders gevonden</option>';
    }
} catch (PDOException $e) {
    // Toon een foutmelding als er iets misgaat
    echo '<div class="alert alert-danger" role="alert">Er is iets fout gegaan bij het laden van de sliders: ' . htmlspecialchars($e->getMessage()) . '</div>';
}
?>
