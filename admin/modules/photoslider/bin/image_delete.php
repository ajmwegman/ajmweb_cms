<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$path = $_SERVER['DOCUMENT_ROOT'];

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );

require_once( $path."/admin/modules/photoslider/src/photoslider.class.php" );

$db = new database($pdo);
$photoslider  = new photoslider($pdo);

$hash = (isset($_GET['hash'])) ? $_GET['hash'] : "";	

// error globals
$thanks = "Het item is verwijderd!";
$errormessage = '<strong> Let op! We hebben nog een paar dingen die niet in orde zijn!</strong>';

$output_dir = $_SERVER['DOCUMENT_ROOT']."/temp/";
$location = $_SERVER['DOCUMENT_ROOT']."/photoslider/";

// selecet filename to delete image on server

$row = $photoslider->getImage("hash", $hash );

$image = $row['image'];

// if there is an imagename in the database
// overwrite database name and delete image.
if(!empty($image)) {
    
    $filename = $location.$image;
    
    $sql = "UPDATE group_photoslider SET image=:image WHERE hash=:hash";
    $go = $db->runQuery($sql, ['image'=>'', 'hash'=>$hash]);
    
    if (file_exists($filename)) {
		@unlink($filename);
    }
    
}

$sql = "UPDATE group_photoslider SET image=:image WHERE hash=:hash";
$go = $db->runQuery($sql, ['image'=>'', 'hash'=>$hash]);

$json_generator = $photoslider->generate_all_sliders_json();
?>
