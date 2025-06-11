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
  $empty_subject     = "Er moet een titel worden toegewezen.";
  $empty_date        = "Er moet een datum worden toegewezen.";
  $empty_location    = "Er moet een locatie of plaats worden toegewezen.";
  $empty_score       = "Er moet een cijfer worden toegewezen.";
  $empty_reaction    = "Er moet een reactie worden opgegeven.";
  $empty_description = "Er moet een omschrijving worden opgegeven.";
  $empty_hash        = "Er moet een Hash worden opgegeven.";

  if ( empty( $_POST[ 'subject' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_subject . "</li>";
  } else {
    $subject = $_POST[ 'subject' ];
    $seo_url = urlsafe($subject);
  }
	
  if ( empty( $_POST[ 'reviewdate' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_date . "</li>";
  } else {
    $reviewdate = $_POST[ 'reviewdate' ];
  }

  if ( empty( $_POST[ 'location' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_location . "</li>";
  } else {
    $location = $_POST[ 'location' ];
  }

  if ( empty( $_POST[ 'score' ] ) || !is_numeric($_POST['score']) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_score . "</li>";
  } else {
	$score = strtolower($_POST[ 'score' ]);
  }
    
  if ( empty( $_POST[ 'reaction' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_reaction . "</li>";
  } else {
	$reaction = $_POST[ 'reaction' ];
  }
    
  if ( empty( $_POST[ 'description' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_description . "</li>";
  } else {
	$description = $_POST[ 'description' ];
  }
    
  if ( empty( $_POST[ 'hash' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_hash . "</li>";
  } else {
	$hash = $_POST[ 'hash' ];
  }

  if ($pass == 0 ) { 

	  $values = array('hash' => $hash, 
                      'subject' => $subject, 
                      'location' => $location, 
                      'seo_url' => $seo_url, 
                      'score' => $score,
                      'description' => $description,
                      'reaction' => $reaction,
                      'reviewdate' => $reviewdate,
                      'modified' => date("Y-m-d h:i:s") ,
                      'active' => 'y'
                     );
      
	  $go = $db->insertdata("group_reviews", $values);
	  
	  if($go == true) {
		 echo '<div class="alert alert-success" role="alert">'.$subject.' is toegevoegd!</div>';
	  } else {
		 echo '<div class="alert alert-danger" role="alert">Er is iets fout gegaan!</div>';
	  }
  }
else {
	//do nothing
	echo "<div class=\"alert alert-danger\" role=\"alert\">Er is iets fout gegaan! {$alert}</div>";
    }
}
?>
