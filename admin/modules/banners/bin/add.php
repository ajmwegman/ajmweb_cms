<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$module = "banners";
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
  $empty_subject     = "Er moet een titel worden toegewezen.";
  $empty_startdate   = "Er moet een start datum worden toegewezen.";
  $empty_enddate     = "Er moet een eind datum worden toegewezen.";
  $empty_advertiser  = "Er moet een averteerder worden toegewezen.";
  $empty_url         = "Er moet een doellocatie worden toegewezen.";
  $empty_hash        = "Er moet een Hash worden opgegeven.";

  if ( empty( $_POST[ 'subject' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_subject . "</li>";
  } else {
    $subject = $_POST[ 'subject' ];
  }
	
  if ( empty( $_POST[ 'advertiser' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_advertiser . "</li>";
  } else {
    $advertiser = $_POST[ 'advertiser' ];
  }
	
  if ( empty( $_POST[ 'startdate' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_startdate . "</li>";
  } else {
    $startdate = $_POST[ 'startdate' ];
  }

  if ( empty( $_POST[ 'enddate' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_enddate . "</li>";
  } else {
    $enddate = $_POST[ 'enddate' ];
  }


  if ( empty( $_POST[ 'url' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_url . "</li>";
  } else {
	$url = $_POST[ 'url' ];
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
                      'advertiser' => $advertiser, 
                      'url' => $url, 
                      'startdate' => $startdate,
                      'enddate' => $enddate,
                      'modified' => date("Y-m-d h:i:s") ,
                      'active' => 'n'
                     );
      
	  $go = $db->insertdata("group_banners", $values);
	  
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
