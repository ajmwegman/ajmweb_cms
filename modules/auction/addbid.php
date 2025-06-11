<?php
session_start();

error_reporting( E_ALL ^ E_DEPRECATED );

ini_set( "display_errors", 1 );

require_once("../../system/database.php");
require_once("../../src/database.class.php");
require_once("../../src/auction.class.php");
require_once("../../src/site.class.php");

$auction = new Auction($pdo); // Create an instance of the auction class
$site    = new site($pdo); // Create an instance of the auction class

$asErrors = array();
$pass = 1;

$empty_low = "Er is reeds een hoger bod geplaatst.";
$empty_login = "Aanmelden is vereist.";
$expired_auction = "De veiling is afgelopen. U kunt geen bod meer plaatsen.";

/* Checks */
$bid = isset($_POST['bid']) ? $_POST['bid'] : '';
$lotid = isset($_POST['lotid']) ? $_POST['lotid'] : '';

$checkBid = $auction->checkBid($bid, $lotid);

$auctionData = $auction->getAuctionData($lotid);

$endDate = $auctionData['endDate'];
$endTime = $auctionData['endTime'];

$minimal_raise = intval($auctionData['minUp']);

$startPrice = intval($auctionData['startPrice']);

$currentDateTime = date('Y-m-d H:i:s');
$endDateTime = $endDate . ' ' . $endTime;

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['session_hash'])) {
    $asErrors[] = $empty_login;
} else {
    $hash = $_SESSION['session_hash'];
}

if ($bid < $startPrice) {
    $asErrors[] = "Het bod moet hoger zijn dan het startbedrag.";
    $pass = 0;
}

if ($currentDateTime > $endDateTime) {
    $asErrors[] = $expired_auction;
}

if (empty($bid) || empty($lotid)) {
    $asErrors[] = "Er is geen geldig bod uitgebracht.";
    $pass = 0;
}

if ($checkBid !== true && $pass === 1) {
    $asErrors[] = $empty_low;
}

// Haal het hoogste bod op
$highestBid = $auction->getHighestBid($lotid);

// Controleer of het bod hoger is dan het hoogste bod
if ($bid <= $highestBid) {
    $asErrors[] = "Het bod moet hoger zijn dan het hoogste bod.";
    $pass = 0;
}

// Controleer of het bod minimaal het verhoogde bedrag hoger is dan het vorige bod
if ($highestBid !== false && $bid < $highestBid + $minimal_raise) {
    $asErrors[] = "Het bod moet minimaal met € ".$minimal_raise." worden verhoogd.";
    $pass = 0;
}

$response = array(
    'status' => 'error',
    'message' => 'Er is een onverwachte fout opgetreden.'
);

if (count($asErrors) === 0) {
    // Je huidige logica om het bod te plaatsen gaat hier
    // Als alles goed gaat, moet je de status van de reactie op 'success' instellen
    $response['status'] = 'success';
    $response['message'] = 'Bod geplaatst!';
    
    $user = $site->getUserByHash($hash);
    
    $userid = $user['id'];
    
    $result = $auction->addBid($bid, $lotid, $userid);
} else {
    // Als er fouten zijn, stel de foutmelding in op de reactie
    $response['message'] = implode("<br>", $asErrors);
}

// Convert the response array to JSON and send it as the response.
header('Content-Type: application/json');
echo json_encode($response);
?>
