<?php 
session_start();
$sessid = session_id();

error_reporting(E_ALL);
ini_set("display_errors", 1);
$group_id = 1;

include("../system/database.php");

require_once("../src/database.class.php");
require_once("../src/site.class.php");

$db    = new database($pdo);
$site   = new site($pdo);

$info      = $site->getWebsiteInfo(1);

$success = '<div class="alert alert-success mt-4" role="alert">
  Uw bericht is succesvol verstuurd.
</div>';

$error = '
<div class="alert alert-danger mt-4" role="alert">
  Er is een fout opgetreden,  neem contact op met de website eigenaar.
</div>';

$db = new database($pdo);    

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Controleer op lege waarden en of het een geldig e-mailadres is
  if (empty($email) || empty($password)) {
    $response = array("success" => false, "message" => "Vul zowel het e-mailadres als het wachtwoord in.");
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response = array("success" => false, "message" => "Ongeldig e-mailadres.");
  } else {

    $sql = "SELECT * FROM site_users WHERE email=:email";
    $go = $db->countQuery($sql, ['email'=>$email]);

    if($go == true) {
        $response = array("success" => false, "message" => "Dit e-mailadres is al geregistreerd.");
    } else {
        // Hash het ingevoerde wachtwoord voordat we het opslaan
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $timestamp = time();

        $values = array('email' => $email, 'password' => $hashedPassword, 'regdate' => date("Y-m-d"));
      
        $go = $db->insertdata("site_users", $values);
        $response = array("success" => true, "message" => "Succesvol geregistreerd!");
        
        $_SESSION['loggedin'] = true;
    }
  }
} else {
  $response = array("success" => false, "message" => "Ongeldig verzoek.");
}

// Stuur de JSON-response terug naar JS
header("Content-Type: application/json");
echo json_encode($response);
?>
