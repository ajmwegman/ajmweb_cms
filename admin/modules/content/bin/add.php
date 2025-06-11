<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$path = $_SERVER['DOCUMENT_ROOT'];

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );
require_once( $path."/admin/modules/content/functions/urlsafe.php" );

$db = new database($pdo);

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
  $empty_article = "Er moet een tekst worden opgegeven.";

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
	
	if ( empty( $_POST[ 'content' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_article . "</li>";
  } else {
    $article = $_POST[ 'content' ];
  }

  $seo_url = empty($_POST['keywords']) ? urlsafe($_POST[ 'title' ]) : urlsafe($_POST[ 'seo_url' ]);
  $location = empty($_POST['location']) ? $_POST[ 'location' ] : $_POST[ 'location' ];
  $keywords = !isset($_POST['keywords']) ? str_replace(" ", ", ", $_POST['title']) :  $_POST['keywords'];
	
  if ($pass == 0 ) { 
 
	  $values = array(
		  'hash' => $hash
		  , 'group_id' => $group_id
		  , 'title' => $title
		  , 'content' => $article
		  , 'location' => $location
		  , 'seo_url' => $seo_url
		  , 'keywords' => $keywords
	  );
	  $go = $db->insertdata("group_content", $values);
	  
	  if($go == true) {
		 echo '<div class="alert alert-success" role="alert">'.$title.' is toegevoegd!</div>';
	  } else {
		 echo '<div class="alert alert-danger" role="alert">Er is iets fout gegaan!</div>';
	  }
  } else {
	echo "<div class=\"alert alert-danger\" role=\"alert\">Er is iets fout gegaan! {$alert}</div>";
  }
}
  ?>
