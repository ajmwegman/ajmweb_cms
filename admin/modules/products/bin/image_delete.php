<?php
session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

print_r($_REQUEST);
// Include necessary files
require_once $_SERVER['DOCUMENT_ROOT'] . "/system/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/src/database.class.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/modules/products/src/products.class.php";

// Create database connection
$db = new database($pdo);
$products = new products($pdo);

// Define messages
$thanks = "Het item is verwijderd!";
$errormessage = '<strong> Let op! We hebben nog een paar dingen die niet in orde zijn!</strong>';

// Sanitize user input
$id = (isset($_POST['hash'])) ? $_POST['hash'] : "";

// Define directories
$output_dir = $_SERVER['DOCUMENT_ROOT'] . "/temp/";
$location = $_SERVER['DOCUMENT_ROOT'] . "/product_images/";

if (!empty($id)) {
    

    // Select the filename to delete the image from the server
    $row = $products->getImageName($id);
    $image = $row['image'];

    // Delete data from the database
    $go = $db->deletedata("group_product_images", "id", $id);
    // $go = true;
    
    // Delete the image from the server
   $unlink_image = unlink($location . DIRECTORY_SEPARATOR . $image);

    if ($go == true) {
        echo '<div class="alert alert-success" role="alert">' . $thanks . '</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">' . $errormessage . '</div>';
    }
} else {
    // Do nothing
}
?>