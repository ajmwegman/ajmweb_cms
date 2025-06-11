<?php
session_start();

//error_reporting( E_ALL ^ E_DEPRECATED );

//ini_set( "display_errors", 1 );

require_once("../../system/database.php");
require_once("../../src/database.class.php");
require_once("../../src/auction.class.php");
require_once("../../src/site.class.php");

$auction = new Auction($pdo); // Create an instance of the auction class
$site    = new site($pdo); // Create an instance of the auction class

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['session_hash'])) {
    $asErrors[] = $empty_login;
} else {
    $hash = $_SESSION['session_hash'];
}

//echo $hash;
    $user = $site->getUserByHash($hash);
    
    $userId = $user['id'];
    $productId = $_GET['product_id'];

$isFav = $auction->isFav($userId, $productId);

header('Content-Type: application/json');
echo json_encode($isFav);
?>