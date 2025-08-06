<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$path = $_SERVER['DOCUMENT_ROOT'];

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );

$db = new database($pdo);

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {

  $pass = 0;
  $alert = "";

  # error globals
  $thanks = "De gegevens zijn met succes opgeslagen.";
  $errormessage = '<strong> Let op! We hebben nog een paar dingen die niet in orde zijn!</strong>';

  # error vars
  $empty_firstname   = "Er moet een voornaam worden toegewezen.";
  $empty_lastname     = "Er moet een achternaam worden toegewezen.";
  $empty_email       = "Er moet een e-mailadres worden toegewezen.";

	
  if ( empty( $_POST[ 'firstname' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_firstname . "</li>";
  } else {
    $firstname = $_POST[ 'firstname' ];
  }

  if ( empty( $_POST[ 'lastname' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_lastname . "</li>";
  } else {
    $lastname = $_POST[ 'lastname' ];
  }

  if ( empty( $_POST[ 'email' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_email . "</li>";
  } else {
	$email = strtolower($_POST[ 'email' ]);
  }
 
    $hash = uniqid();

  if ($pass == 0 ) { 

	  $values = array('hash' => $hash, 
                      'firstname' => $firstname, 
                      'lastname' => $lastname, 
                      'emailaddress' => $email
                     );
      
	  $go = $db->insertdata("group_newslettermembers", $values);
	  
	  if($go == true) {
		 echo "<div class=\"alert alert-success\" role=\"alert\">{$email} is toegevoegd!</div>";
	  } else {
		 echo '<div class="alert alert-danger" role="alert">Er is iets fout gegaan!</div>';
	  }
  }
else {
	//do nothing
	echo "<div class=\"alert alert-danger\" role=\"alert\">{$errormessage}</div>";
    }
}
?>
