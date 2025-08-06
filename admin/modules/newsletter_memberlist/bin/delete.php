<?php
@session_start();

$path = $_SERVER[ 'DOCUMENT_ROOT' ];

require_once( $path . "/system/database.php" );
require_once( $path . "/admin/src/database.class.php" );
require_once( $path . "/admin/modules/newsletter_memberlist/src/module.class.php" );

$db = new database( $pdo );
$memberlist = new newsletter_memberlist( $pdo );

// error globals
$thanks = "Het item is verwijderd!";
$errormessage = '<strong> Let op! We hebben nog een paar dingen die niet in orde zijn!</strong>';

if ( isset( $_POST[ 'id' ] ) ) {

  $id = $_POST[ 'id' ];

  $go = $memberlist->deleteMember( $id );

  if ( $go == true ) {
    echo '<div class="alert alert-success" role="alert">' . $thanks . '</div>';
  } else {
    echo '<div class="alert alert-danger" role="alert">' . $errormessage . '</div>';
  }
} else {
  //do nothing
}
?>
