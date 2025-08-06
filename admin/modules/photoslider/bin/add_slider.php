<?php
@session_start();

error_reporting(E_ALL ^ E_DEPRECATED);
ini_set("display_errors", 1);

$path = $_SERVER['DOCUMENT_ROOT'];

require_once($path . "/system/database.php");
require_once($path . "/admin/src/database.class.php");

$db = new database($pdo);

$success = '<div class="alert alert-success" role="alert">Nieuwe Slider is toegevoegd</div>';

$error = '<div class="alert alert-danger" role="alert">Er is iets fout gegaan!</div>';

// Controleer of de 'slider_name' aanwezig is in de POST
if (isset($_POST['slider_name'])) {

    $sliderName = trim($_POST['slider_name']);

    // Controleer of de slidernaam niet leeg is
    if (!empty($sliderName)) {

        // SQL query om een nieuwe slider toe te voegen
        $sql = "INSERT INTO group_photoslider_names (name) VALUES (:name)";
        $go = $db->runQuery($sql, ['name' => $sliderName]);

        // Controleer of de query succesvol was
        if ($go == true) {
            echo $success;
        } else {
            echo $error;
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">De naam van de slider mag niet leeg zijn!</div>';
    }
} else {
    // Als er geen POST-gegevens zijn ontvangen, wordt er niets gedaan
    echo '<div class="alert alert-warning" role="alert">Geen slider naam ontvangen!</div>';
}
?>
