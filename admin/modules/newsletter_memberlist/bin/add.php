<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$path = $_SERVER['DOCUMENT_ROOT'];

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );
require_once( $path."/admin/modules/newsletter_memberlist/src/module.class.php" );

$db = new database($pdo);
$memberlist = new newsletter_memberlist($pdo);

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
  $duplicate_email   = "Dit e-mailadres is al geregistreerd.";

	
  if ( empty( $_POST[ 'firstname' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_firstname . "</li>";
  } else {
    $firstname = trim($_POST[ 'firstname' ]);
  }

  if ( empty( $_POST[ 'lastname' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_lastname . "</li>";
  } else {
    $lastname = trim($_POST[ 'lastname' ]);
  }

  if ( empty( $_POST[ 'email' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_email . "</li>";
  } else {
	$email = strtolower(trim($_POST[ 'email' ]));
  }

  // Check for duplicate email
  if (!empty($email) && $memberlist->checkDuplicateEmail($email)) {
    $pass = 1;
    $alert .= "<li>" . $duplicate_email . "</li>";
  }
 
  if ($pass == 0 ) { 
	  $go = $memberlist->addMember($firstname, $lastname, $email);
	  
	  if($go == true) {
		 echo "<div class=\"alert alert-success\" role=\"alert\">{$email} is toegevoegd!</div>";
	  } else {
		 echo '<div class="alert alert-danger" role="alert">Er is iets fout gegaan!</div>';
	  }
  } else {
	echo "<div class=\"alert alert-danger\" role=\"alert\">{$errormessage}<ul>{$alert}</ul></div>";
  }
}
?>
