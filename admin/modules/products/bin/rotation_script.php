<?php
@session_start();

print_r($_POST);
error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$path = $_SERVER['DOCUMENT_ROOT'];

$imageLocation = $_SERVER['DOCUMENT_ROOT'].'/product_images/'; // Pas dit aan aan je systeem

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );

require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/modules/products/src/products.class.php";

$db = new database($pdo);
$product = new products($pdo);

$success = '<div class="loader spinner-grow" style="width: 1rem; height: 1rem;" role="status">
			<span class="sr-only"></span>
			</div>';

$error = '<div class="alert alert-danger" role="alert">Er is iets fout gegaan!</div>';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // In je PHP-script dat het AJAX-verzoek verwerkt
    $imageId = $_POST['imageId'];
    $angle = $_POST['angle'];

    // Aanroepen van de rotateImage-functie
    $rotate = $product->rotateImage($imageId, $angle, $imageLocation);

    if ($rotate) {
        echo "Afbeelding succesvol geroteerd";
    } else {
        echo $error;
    }
}
?>