<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$path = $_SERVER['DOCUMENT_ROOT'];

print_r($_POST);
require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );

$db = new database($pdo);

$success = '<div class="loader spinner-grow" style="width: 1rem; height: 1rem;" role="status">
			<span class="sr-only"></span>
			</div>';

$error = '<div class="alert alert-danger" role="alert">Er is iets fout gegaan!</div>';

if(isset($_POST['id']) && isset($_POST['values'])) {
    
    $shop_id = $_POST['id'];
    $values = $_POST['values'];

    $go = $db->deletedata("area_link", "shop_id", $shop_id);
    
    foreach($values as $key => $val) {
        
        $area_id = $val['value'];

        $values = array('area_id' => $area_id, 'shop_id' => $shop_id);
        
	    $go = $db->insertdata("area_link", $values);

    }
    
    echo $success;

} else {
    echo $error;
}

?>