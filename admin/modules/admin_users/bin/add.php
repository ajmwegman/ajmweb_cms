<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$path = $_SERVER['DOCUMENT_ROOT'];

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );
require_once( $path."/admin/functions/urlsafe.php" );

$db = new database($pdo);

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {

  $pass = 0;
  $alert = "";

  # error globals
  $thanks = "De gegevens zijn met succes opgeslagen.";
  $errormessage = '<strong> Let op! We hebben nog een paar dingen die niet in orde zijn!</strong>';

  # error vars
  $empty_username    = "Er moet een gebruikersnaam worden toegewezen.";
  $empty_firstname   = "Er moet een voornaam worden toegewezen.";
  $empty_surname     = "Er moet een achternaam worden toegewezen.";
  $empty_email       = "Er moet een e-mailadres worden toegewezen.";
  $empty_password    = "Er moet een wachtwoord worden opgegeven.";
  $empty_status      = "Er moet een status worden opgegeven.";
  $empty_hash        = "Er moet een Hash worden opgegeven.";

  if ( empty( $_POST[ 'username' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_username . "</li>";
  } else {
    $username = $_POST[ 'username' ];
  }
	
  if ( empty( $_POST[ 'firstname' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_firstname . "</li>";
  } else {
    $firstname = $_POST[ 'firstname' ];
  }

  if ( empty( $_POST[ 'surname' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_surname . "</li>";
  } else {
    $surname = $_POST[ 'surname' ];
  }

  if ( empty( $_POST[ 'email' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_email . "</li>";
  } else {
	$email = strtolower($_POST[ 'email' ]);
  }
    
  if ( empty( $_POST[ 'password' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_password . "</li>";
  } else {
	$password = password_hash($_POST[ 'password' ], PASSWORD_BCRYPT);
  }
 
    
  if ( empty( $_POST[ 'hash' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_hash . "</li>";
  } else {
	$hash = $_POST[ 'hash' ];
  }

  if ($pass == 0 ) { 

	  $values = array('hash' => $hash, 
                      'username' => $username, 
                      'firstname' => $firstname, 
                      'surname' => $surname, 
                      'email' => $email,
                      'password' => $password,
                      'user_level' => '1',
                      'status' => 'y'
                     );
      
	  $go = $db->insertdata("group_login", $values);
	  
	  if($go == true) {
		 echo "<div class=\"alert alert-success\" role=\"alert\">{$username} is toegevoegd!</div>";
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
