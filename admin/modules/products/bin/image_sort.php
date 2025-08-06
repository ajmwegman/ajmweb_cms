<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$path = $_SERVER['DOCUMENT_ROOT'];

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );

$db = new database($pdo);

//var_dump($_POST);
$success = '<div class="loader spinner-grow" style="width: 1rem; height: 1rem;" role="status">
			<span class="sr-only"></span>
			</div>';

$error = '<div class="alert alert-danger" role="alert">Er is iets fout gegaan!</div>';

if ( isset( $_POST[ 'order' ] ) ) {

  $values = $_POST[ 'order' ];
  foreach ( $values as $key => $value ) {
    
	  $new_order = $key + 1; // to prevent 0 
	
	  echo "<br>".$value ."wordt: ".$new_order;
	
	  //$values = array('sort_num' => $new_order);
	  
	  $sql = "UPDATE group_product_images SET sort_num=:sort_num WHERE id=:id";
	  $go = $db->runQuery($sql, ['sort_num'=>$new_order, 'id'=>$value]);

	if($go == true) {
		 $pass[] = 0;
	} else {
		 $pass[] = 1;
	}
  } 
	
$pass = array_sum($pass);
	
if($pass === 0) {
	  echo $success;
  } else {
	  echo $error;
  }
// print_r($arr);
} else {
  echo "no newlist";
}

?>