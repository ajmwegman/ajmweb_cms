<?php
error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 );

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

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

    $bidHistoryData = $auction->getBids($lotid, 5);
    $jsonMessage = json_encode(['bids' => $bidHistoryData]);

    echo "data: $jsonMessage\n\n";

    flush();

    sleep(0.3); // Wacht 5 seconden voordat de volgende update wordt verzonden
}
?>
