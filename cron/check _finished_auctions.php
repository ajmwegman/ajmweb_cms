<?php
session_start();

error_reporting( E_ALL ^ E_DEPRECATED );

ini_set( "display_errors", 1 );

require_once("../system/database.php");
require_once("../src/database.class.php");
require_once("../src/auction.class.php");
require_once("../src/site.class.php");

$auction = new Auction($pdo); // Create an instance of the auction class
$site    = new site($pdo); // Create an instance of the auction class

// Voer de controle uit op beëindigde veilingen
$result = $auction->checkFinishedAuctions();

if ($result === true) {
    $response = array(
        'status' => 'success',
        'message' => 'Controle op beëindigde veilingen succesvol uitgevoerd.'
    );
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Er is een fout opgetreden bij het uitvoeren van de controle op beëindigde veilingen.'
    );
}

// Convert the response array to JSON and send it as the response.
//header('Content-Type: application/json');
//echo json_encode($response);
var_dump($response);
?>
