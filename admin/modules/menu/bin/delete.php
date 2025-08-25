<?php
@session_start();

$path = $_SERVER['DOCUMENT_ROOT'];

// Debug: check what's happening
error_log("Delete.php started - POST data: " . print_r($_POST, true));

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );

$db = new database( $pdo );

// error globals
$thanks = "Het item is verwijderd!";
$errormessage = '<strong> Let op! We hebben nog een paar dingen die niet in orde zijn!</strong>';

if ( isset( $_POST[ 'id' ] ) ) {

  $id = $_POST[ 'id' ];
  error_log("Processing delete for ID: " . $id);

  // Bepaal of dit een ID of hash is
  $is_hash = (strlen($id) > 20 && strpos($id, '_') !== false);
  
  if ($is_hash) {
    // Dit is een hash, probeer op hash te deleten
    error_log("Attempting delete by hash: " . $id);
    $go = $db->deletedata( "group_menu", "hash", $id );
  } else {
    // Dit is een numeriek ID, probeer op ID te deleten
    error_log("Attempting delete by ID: " . $id);
    $go = $db->deletedata( "group_menu", "id", $id );
  }

  error_log("Delete result: " . ($go ? 'true' : 'false'));

  if ( $go == true ) {
    echo '<div class="alert alert-success" role="alert">' . $thanks . '</div>';
  } else {
    echo '<div class="alert alert-danger" role="alert">' . $errormessage . '</div>';
  }
} else {
  error_log("No ID provided in POST data");
  //do nothing
}

error_log("Delete.php finished");
?>
