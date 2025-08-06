<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$path = $_SERVER['DOCUMENT_ROOT'];

//print_r($_POST);

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );

require_once( $path."/admin/modules/photoslider/src/photoslider.class.php" );

$db = new database($pdo);
$photoslider = new photoslider($pdo);

// error globals
$thanks = "Het item is verwijderd!";
$errormessage = '<strong> Let op! We hebben nog een paar dingen die niet in orde zijn!</strong>';

if ( isset( $_POST[ 'id' ] ) ) {

  $id = $_POST[ 'id' ];

  $go = $db->deletedata( "group_photoslider", "id", $id );

  if ( $go == true ) {
    echo '<div class="alert alert-success" role="alert">' . $thanks . '</div>';
      
    $json_generator = $photoslider->generate_all_sliders_json();
      
  } else {
    echo '<div class="alert alert-danger" role="alert">' . $errormessage . '</div>';
  }
} else {
  //do nothing
}
?>
