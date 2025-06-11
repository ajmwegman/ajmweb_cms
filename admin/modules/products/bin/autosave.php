<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$path = $_SERVER['DOCUMENT_ROOT'];

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );

function seo_friendly_title($string) {
    // Verwijder speciale tekens maar behoud enkele koppeltekens
    $string = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
    
    // Vervang meerdere koppeltekens door een enkel koppelteken
    $string = preg_replace('/-+/', '-', $string);
    
    // Zet de string om naar lowercase
    $string = strtolower($string);
    
    // Verwijder aan het begin en eind van de string eventuele koppeltekens
    $string = trim($string, '-');
    
    return $string;
}


$db = new database($pdo);

$success = '<div class="loader spinner-grow" style="width: 1rem; height: 1rem;" role="status">
			<span class="sr-only"></span>
			</div>';

$error = '<div class="alert alert-danger" role="alert">Er is iets fout gegaan!</div>';

if ( isset( $_POST[ 'id' ] ) ) {

	$id    = $_POST[ 'id' ];
	$field = $_POST[ 'field' ];
	$value = $_POST[ 'value' ];

    
    if ($field == 'title') { // Verondersteld dat 'title' het veld is waar je de SEO-titel wilt opslaan
        $seo_title = seo_friendly_title($value);
        
        $sql = "UPDATE group_products SET seoTitle=:seoTitle WHERE hash=:hash";
	    $go = $db->runQuery($sql, ['seoTitle'=>$seo_title, 'hash'=>$id]);
    }
    
	$sql = "UPDATE group_products SET {$field}=:{$field} WHERE hash=:hash";
	$go = $db->runQuery($sql, [$field=>$value, 'hash'=>$id]);
    
    $sql2 = "UPDATE group_products SET modified = NOW() WHERE hash=:hash";
	$go2 = $db->runQuery($sql2, ['hash'=>$id]);

	if($go == true) {
		 echo $success;
	} else {
		 echo $error;
	}
} else {
  echo "no newlist";
}

?>