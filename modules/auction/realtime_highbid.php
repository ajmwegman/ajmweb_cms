<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

require_once("../../system/database.php");
require_once ("../../src/database.class.php");
require_once("../../functions/auctions.php");

$auction = new Auction($pdo); // Create an instance of the auction class

$lotid = isset($_GET['lotid']) ? $_GET['lotid'] : '';

if (!ctype_digit($lotid)) {
    // Ongeldig lotid, stuur een foutmelding of handel dit op een andere manier af
    echo "error";
    exit();
} else {

    // Haal het hoogste bod op voor het specifieke lot (bijvoorbeeld uit de database)
$newHighestBid = $auction->getHighestBid($lotid); // Gebruik $lotid om het juiste hoogste bod op te halen

// Zet het hoogste bod om naar een JSON-bericht met het gewenste formaat
$jsonMessage = json_encode(['highestBid' => number_format($newHighestBid, 2, '.', '')]);

// Stuur het JSON-bericht als SSE-response
echo "data: $jsonMessage\n\n";
flush();
    
    sleep(0.3);
}
?>