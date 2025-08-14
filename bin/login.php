<?php
declare(strict_types=1);
session_start();
$sessid = session_id();

error_reporting( E_ALL );
ini_set( "display_errors", 1 );

include( "../system/database.php" );

require_once ("../src/database.class.php");
require_once ("../src/site.class.php");
require_once ("../src/users.class.php");

$db   	 = new database($pdo);
$site  	 = new site($pdo);
$users   = new users($pdo);

$info    	= $site->getWebsiteInfo(1);

$success = '<div class="alert alert-success mt-4" role="alert">
  Uw bericht is succesvol verstuurd.
</div>';

$error = '
<div class="alert alert-danger mt-4" role="alert">
  Er is een fout opgetreden,  neem contact op met de website eigenaar.
</div>';

  // Haal de gegevens op van het POST-verzoek (vanuit het formulier)
  $email         = $_POST["email"];
  $inputPassword = $_POST["password"];

  // Voorbereid statement om de gebruiker te zoeken met het ingevoerde e-mailadres
  $sql = "SELECT * FROM site_users WHERE email=:email";
  $params = ["email" => $email];
 
    $result = $db->runQuery($sql, $params);

    if ($result) {
        
        $user = $result->fetch(PDO::FETCH_ASSOC);
    
        // Doe iets met $user
        $hashedPassword = $user["password"];
        
        if (password_verify($inputPassword, $hashedPassword)) {
        
            $newHash = sha1(uniqid() . time() . rand());
            $userId = $user['id'];
                
            $_SESSION['loggedin'] = true;
            $_SESSION['session_hash'] = $newHash;
            // Sla gebruikersrol op in de sessie
            $_SESSION['role'] = $user['role'] ?? 'user';
            
            $response = ["success" => true, "message" => "Aanmelden gelukt!"];
            
            $update = $users->updateLogin($userId, $newHash);
    
        } else {
            
            $response = ["success" => false, "message" => "Ongeldige inloggegevens."];
        }
        
    } else {
        $response = ["success" => false, "message" => "Er is een fout opgetreden"];
    }

// Stuur de JSON-response terug naar JS
header("Content-Type: application/json");
echo json_encode($response);
?>
