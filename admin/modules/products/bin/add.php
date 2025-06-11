<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$module = "products";
$path = $_SERVER['DOCUMENT_ROOT'];

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );

function seo_friendly_title($string) {
    // Verwijder speciale tekens
    $string = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
    
    // Zet de string om naar lowercase
    $string = strtolower($string);
    
    // Verwijder aan het begin en eind van de string eventuele koppeltekens
    $string = trim($string, '-');
    
    return $string;
}

$db = new database($pdo);

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {

  $pass = 0;
  $alert = "";
  $sort_num = 0;

  # error globals
  $thanks = "De gegevens zijn met succes opgeslagen.";
  $errormessage = '<strong> Let op! We hebben nog een paar dingen die niet in orde zijn!</strong>';

  # error vars
  $empty_title     = "Er moet een titel worden toegewezen.";
  $empty_category  = "Er moet een averteerder worden toegewezen.";
  $empty_hash      = "Er moet een Hash worden opgegeven.";

  if ( empty( $_POST[ 'title' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_title . "</li>";
  } else {
    $title = $_POST[ 'title' ];
  }
	
  if ( empty( $_POST[ 'category' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_category . "</li>";
  } else {
    $category = $_POST[ 'category' ];
  }
	 
  if ( empty( $_POST[ 'hash' ] ) ) {
    $pass = 1;
    $alert .= "<li>" . $empty_hash . "</li>";
  } else {
	$hash = $_POST[ 'hash' ];
  }

  if ($pass == 0 ) { 

      $seoTitle = seo_friendly_title($title);
      
	  $values = array('hash' => $hash, 
                      'title' => $title, 
                      'seoTitle' => $seoTitle, 
                      'category' => $category, 
                      'sort_num' => $sort_num,
                      'active' => 'n',
                      'modified' => date("Y-m-d h:i:s")

                     );
      
	  $go = $db->insertdata("group_products", $values);
	  
	  if($go == true) {
		 echo '<div class="alert alert-success" role="alert">'.$title.' is toegevoegd!</div>';
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
