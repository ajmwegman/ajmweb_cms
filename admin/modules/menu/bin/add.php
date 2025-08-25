<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$path = $_SERVER['DOCUMENT_ROOT'];

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );

$db = new database($pdo);

/* 	
Table: group_menu
Fields:
group_id
title
sortnum
location
*/

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {

  $pass = 0;
  $alert = "";

  # error globals
  $thanks = "De gegevens zijn met succes opgeslagen.";
  $errormessage = '<strong> Let op! We hebben nog een paar dingen die niet in orde zijn!</strong>';

  # error vars
  $empty_group_id = "Er moet een groep worden toegewezen.";
  $empty_hash = "Er moet een hash worden toegewezen.";
  $empty_title = "Er moet een naam worden opgegeven.";

  if ( empty( $_POST[ 'group_id' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_group_id . "</li>";
  } else {
    $group_id = $_POST[ 'group_id' ];
  }
	
  if ( empty( $_POST[ 'hash' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_hash . "</li>";
  } else {
    $hash = $_POST[ 'hash' ];
  }

  if ( empty( $_POST[ 'title' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_title . "</li>";
  } else {
    $title = $_POST[ 'title' ];
  }

  if ( empty( $_POST[ 'location' ] ) ) {
    $location = strtolower($_POST[ 'title' ]);
  } else {
	$location = strtolower($_POST[ 'location' ]);
  }

  if ($pass == 0 ) { 
 
	  // Check if hash already exists to prevent duplicates
	  $check_sql = "SELECT COUNT(*) as count FROM group_menu WHERE hash = ?";
	  $check_stmt = $pdo->prepare($check_sql);
	  $check_stmt->execute([$hash]);
	  $hash_exists = $check_stmt->fetchColumn();
	  
	  if ($hash_exists > 0) {
		  echo '<div class="alert alert-warning" role="alert">Dit menu item bestaat al (hash: ' . $hash . '). Probeer opnieuw.</div>';
	  } else {
		  $values = array('group_id' => $group_id, 'hash' => $hash, 'title' => $title, 'location' => $location);
		  $result = $db->insertdata("group_menu", $values);
		  
		  if($result !== false) {
			 // De insertdata methode retourneert nu de nieuwe ID
			 $new_id = $result;
			 echo '<div class="alert alert-success" role="alert">'.$title.' is toegevoegd!</div>';
			 
			 // Voeg de nieuwe ID toe aan de response voor JavaScript
			 if ($new_id) {
				 echo '<script>window.newMenuItemId = "'.$new_id.'";</script>';
				 echo '<div id="newMenuItemId" data-id="'.$new_id.'" style="display:none;"></div>';
			 }
		  } else {
			 echo '<div class="alert alert-danger" role="alert">Er is iets fout gegaan!</div>';
		  }
	  }
  }
} else {
	//do nothing
}
  ?>
