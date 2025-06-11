<?php
//error_reporting( E_ALL ^ E_DEPRECATED );
//ini_set( "display_errors", 1 );

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

require_once("../../system/database.php");
require_once ("../../src/database.class.php");
require_once("../../src/auction.class.php");

$auction = new Auction($pdo); // Create an instance of the auction class

$lotid = isset($_GET['lotid']) ? $_GET['lotid'] : '';

if (!ctype_digit($lotid)) {
    // Ongeldig lotid, stuur een foutmelding of handel dit op een andere manier af
    echo "error";
    exit();
}

  $auctionData = $auction->getAuctionData( $lotid );

  $endDate      = $auctionData[ 'endDate' ];
  $endTime      = $auctionData[ 'endTime' ];

$endDateTimeString = $endDate.$endTime; // De eindtijd van de veiling
$endTime = strtotime($endDateTimeString); // Converteer naar timestamp

while (true) {
    
    $currentTime = time();
    $timeLeft = $endTime - $currentTime;

    $sleepInterval = 0.5; // Vaker controleren, bijvoorbeeld elke 0.5 seconde
    /*
    // Bepaal de sleep interval
    if ($timeLeft <= 180) { // Laatste 3 minuten
        
    } else {
        $sleepInterval = 1; // Minder vaak controleren, bijvoorbeeld elke 30 seconden
    }*/

    // Get a list of latest bids
    $bidHistoryData = $auction->getBids($lotid, 5);
    $newHighestBid = $auction->getHighestBid($lotid);

    // Create an array with both bid history and highest bid data
    $sseData = [
        'bids' => $bidHistoryData,
        'highestBid' => number_format($newHighestBid, 2, '.', '')
    ];

    if (empty($bidHistoryData)) {
        $sseData = [
        'bids' => '',
        'sleepInterval' => $sleepInterval,
        'endTime' => $endTime,
        'highestBid' => number_format(0, 2, '.', '')
        ];
    }

    // Convert the array to JSON
    $jsonSSEMessage = json_encode($sseData);

    // Send the JSON data as a single SSE message with the same event name
    echo "data: $jsonSSEMessage\n\n";
    
    sleep($sleepInterval);
}
?>