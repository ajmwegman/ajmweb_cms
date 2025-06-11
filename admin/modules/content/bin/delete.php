<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$path = $_SERVER['DOCUMENT_ROOT'];

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );

$db = new database($pdo);

// error globals
$thanks = "De gegevens zijn met succes opgeslagen.";
$errormessage = '<strong> Let op! We hebben nog een paar dingen die niet in orde zijn!</strong>';


if(isset($_POST['id'])) {

 $id = $_POST['id'];
	
 $go = $db->deletedata("group_content", "id", $id);

 if($go == true) {
	 echo '<div class="alert alert-success" role="alert">Het item is verwijderd!</div>';
  } else {
	 echo '<div class="alert alert-danger" role="alert">Er is iets fout gegaan!</div>';
  }
}
else {
	//do nothing
}
  ?>
