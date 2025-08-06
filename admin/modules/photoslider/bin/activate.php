<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$path = $_SERVER['DOCUMENT_ROOT'];

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );

require_once( $path."/admin/modules/photoslider/src/photoslider.class.php" );

$db = new database($pdo);
$photoslider = new photoslider($pdo);

$success = '<div class="loader spinner-grow" style="width: 1rem; height: 1rem;" role="status">
			<span class="sr-only"></span>
			</div>';

$error = '<div class="alert alert-danger" role="alert">Er is iets fout gegaan!</div>';

if(isset($_POST['id'])) {

 $hash = $_POST['id'];
 $value = $_POST['name'];
 $field = $_POST['field'];
	
 $sql = "UPDATE group_photoslider SET {$field}=:{$field} WHERE hash=:hash";
 $go = $db->runQuery($sql, [$field=>$value, 'hash'=>$hash]);
	  
    if($go == true) {
        
            $json_generator = $photoslider->generate_all_sliders_json();

        echo $success;
    } else {
     echo $error;
    }
}
else {
	//do nothing
}
?>