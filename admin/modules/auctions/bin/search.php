<?php
@session_start();

error_reporting(E_ALL ^ E_DEPRECATED);
ini_set("display_errors", 1);

$path = $_SERVER['DOCUMENT_ROOT'];

require_once($path . "/system/database.php");
require_once($path . "/admin/src/database.class.php");
require_once($path . "/admin/modules/auctions/src/auction.class.php");

// Assuming $pdo is defined and set properly in your database.php or elsewhere
$db = new database($pdo);
$auction = new auction($pdo);

if (isset($_GET['searchQuery'])) {
    $searchQuery = $_GET['searchQuery'];
    $list = $auction->getActiveProductInfo($searchQuery);
} else {
    $list = $auction->getActiveProductInfo();
}

// Prepare the search results in an array
$searchResults = array();
foreach ($list as $row => $link) {
    $searchResults[] = array(
        'productId' => $link['id'], // Assuming you have an 'id' field for the productId
        'name' => $link['title'], // Assuming you have a 'title' field for the product name
    );
}

// Convert the search results array to JSON format
$jsonData = json_encode($searchResults);

// Set the Content-Type header to application/json
header('Content-Type: application/json');

// Output the JSON data
echo $jsonData;
?>
