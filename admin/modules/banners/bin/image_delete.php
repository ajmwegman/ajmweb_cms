<?php
@session_start();

$path = $_SERVER[ 'DOCUMENT_ROOT' ];

require_once( $path . "/system/database.php" );
require_once( $path . "/admin/src/database.class.php" );

require_once( $path."/admin/modules/banners/src/banners.class.php" );

$db = new database( $pdo );
$banner = new banner($pdo);	

// error globals
$thanks = "Het item is verwijderd!";
$errormessage = '<strong> Let op! We hebben nog een paar dingen die niet in orde zijn!</strong>';

$hash = (isset($_POST['id'])) ? $_POST['id'] : "";

$output_dir = $_SERVER['DOCUMENT_ROOT']."/temp/";
$location = $_SERVER['DOCUMENT_ROOT']."/banners/";

// selecet filename to delete image on server

$row = $banner->getBanner("hash", $hash );

$image = $row['image'];

// if there is an imagename in the database
// overwrite database name and delete image.
if(!empty($image)) {
    
    $filename = $location.$image;
    
    $sql = "UPDATE group_banners SET image=:image WHERE hash=:hash";
    $go = $db->runQuery($sql, ['image'=>'', 'hash'=>$hash]);
    
    if (file_exists($filename)) {
		@unlink($filename);
    }
}

print_r($_POST);
/*
if ( isset( $_POST[ 'id' ] ) ) {

  $id = $_POST[ 'id' ];

  $go = $db->deletedata( "group_banners", "id", $id );

  if ( $go == true ) {
    echo '<div class="alert alert-success" role="alert">' . $thanks . '</div>';
  } else {
    echo '<div class="alert alert-danger" role="alert">' . $errormessage . '</div>';
  }
} else {
  //do nothing
}*/
?>
