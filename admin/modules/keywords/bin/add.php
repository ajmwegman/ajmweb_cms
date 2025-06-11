<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$path = $_SERVER['DOCUMENT_ROOT'];

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );

$db = new database($pdo);
/* 	
Table: group_keywords
Fields:
group_id
hash
keyword
replacer
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
	
  if ( empty( $_POST[ 'keyword' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_keyword . "</li>";
  } else {
    $keyword = "[".strtoupper($_POST[ 'keyword' ])."]";
  }

  if ( empty( $_POST[ 'replacer' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_replacer . "</li>";
  } else {
    $replacer = $_POST[ 'replacer' ];
  }

  if ($pass == 0 ) { 
 
	  $values = array('group_id' => $group_id, 'hash' => $hash, 'keyword' => $keyword, 'replacer' => $replacer);
	  $go = $db->insertdata("group_keywords", $values);
	  
	  if($go == true) {
		 echo '<div class="alert alert-success" role="alert">'.$keyword.' is toegevoegd!</div>';
	  } else {
		 echo '<div class="alert alert-danger" role="alert">Er is iets fout gegaan!</div>';
	  }
   }
} else {
	//do nothing
}
  ?>
