<?php
@session_start();

$path = $_SERVER[ 'DOCUMENT_ROOT' ];

require_once( $path . "/system/database.php" );
require_once( $path . "/admin/src/database.class.php" );

$db = new database( $pdo );

// error globals
$thanks = "Het item is verwijderd!";
$errormessage = '<strong> Let op! We hebben nog een paar dingen die niet in orde zijn!</strong>';

if ( isset( $_POST[ 'id' ] ) ) {

  $id = $_POST[ 'id' ];

  $go = $db->deletedata( "group_carousel", "id", $id );

  if ( $go == true ) {
    echo '<div class="alert alert-success" role="alert">' . $thanks . '</div>';
  } else {
    echo '<div class="alert alert-danger" role="alert">' . $errormessage . '</div>';
  }
} else {
  //do nothing
}
?>
