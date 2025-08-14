<?php
session_start();

error_reporting( E_ALL ^ E_DEPRECATED );

ini_set( "display_errors", 1 );

require_once("../../system/database.php");
require_once("../../src/database.class.php");
require_once("../../src/auction.class.php");
require_once("../../src/site.class.php");
require_once("../../functions/csrf.php");

$auction = new Auction($pdo); // Create an instance of the auction class
$site    = new site($pdo); // Create an instance of the auction class

$asErrors = array();
$pass = 1;

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['session_hash'])) {
    $asErrors[] = $empty_login;
} else {
    $hash = $_SESSION['session_hash'];
}

if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    $response = array(
        'status' => 'error',
        'message' => 'Ongeldige CSRF-token.'
    );
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

$productId = $_POST['product_id'];

if (count($asErrors) === 0) {
    
    //echo $hash;
    $user = $site->getUserByHash($hash);
    
    $userid = $user['id'];
    
    // Everything is OK, so place the bid.
    $result = $auction->addFav($userid, $productId);
    
    $response = array(
        'status' => $result,
        'message' => 'Aan favorieten toegevoegd.',
        'hash' => $hash,
        'userid' => $userid,
        'productId' => $productId
    );
} else {
    if (!empty($asErrors)) {
        $response = array(
            'status' => 'error',
            'message' => implode("<br>", $asErrors)
        );
    }
}

// Convert the response array to JSON and send it as the response.
header('Content-Type: application/json');/*
// Geef een succesbericht als JSON terug
$response = array(
    "status" => "success",
    "Product" => $productId,
    "userId" => $hash,
    "message" => "Product is aan favorieten toegevoegd."
);*/
echo json_encode($response);
?>