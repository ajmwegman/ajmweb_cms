<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$path = $_SERVER['DOCUMENT_ROOT'];

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );

$db = new database($pdo);

$success = '<div class="loader spinner-grow" style="width: 1rem; height: 1rem;" role="status">
			<span class="sr-only"></span>
			</div>';

$error = '<div class="alert alert-danger" role="alert">Er is iets fout gegaan!</div>';

if(isset($_POST['id'])) {

 $hash = $_POST['id'];
 $active = $_POST['active'];
	
 $sql = "UPDATE group_banners SET active=:active WHERE hash=:hash";
 $go = $db->runQuery($sql, ['active'=>$active, 'hash'=>$hash]);
	  
    if($go == true) {
        echo $success;
    } else {
     echo $error;
    }
}
else {
	//do nothing
}
?>