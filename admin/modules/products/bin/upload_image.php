<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$path = $_SERVER['DOCUMENT_ROOT'];
$max_image_size = 960;

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );

require_once( $path."/admin/modules/products/src/products.class.php" );

$db        = new database($pdo);
$products  = new products($pdo);	

$allowed   = array('jpg','jpeg','pjpeg','gif','png'); 

$success = '<div class="loader spinner-grow" style="width: 1rem; height: 1rem;" role="status">
			<span class="sr-only"></span>
			</div>';

$error = '<div class="alert alert-danger" role="alert">Er is iets fout gegaan!</div>';

$hash = (isset($_POST['hash'])) ? $_POST['hash'] : "";

$output_dir = $_SERVER['DOCUMENT_ROOT']."/temp/";
$location = $_SERVER['DOCUMENT_ROOT']."/product_images/";

$asErrors = array();

if(!is_dir($output_dir)) { 
	$make_dir = mkdir($output_dir);
	$chmod_new_map = chmod($output_dir, 0777);
}

if(!is_dir($location)) { 
	$make_dir = mkdir($location);
	$chmod_new_map = chmod($location, 0777);
}

if(isset($_FILES["myfile"]))
{
	$ret = array();

	$error = $_FILES["myfile"]["error"];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 
	if(!is_array($_FILES["myfile"]["name"])) //single file
	{
	 	$fileName = $_FILES["myfile"]["name"];
		
 		move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);
		$ret[]= $fileName;
		
		list($width, $height, $type, $attr) = getimagesize($output_dir.$fileName);

       // $sql = "UPDATE group_gallery SET image=:image WHERE hash=:hash";
    //    $go = $db->runQuery($sql, ['image'=>$fileName, 'hash'=>$hash]);
        
        // insert into database 
        // insert
        //$go = $db->runQuery("INSERT INTO group_product_images VALUES (hash, image)", [$fileName, $hash]);

        // Insert into database
        //$sql = "INSERT INTO group_gallery (image, hash) VALUES (:image, :hash)";
        //$go = $db->runQuery($sql, ['image' => $fileName, 'hash' => $hash]);
        //insertdata( $table, $values )
        
        
      $values = array('hash' => $hash, 'image' => $fileName);
      
	  $go = $db->insertdata("group_product_images", $values);
            
            
		$extension = $products->check_extension($fileName, $allowed);
		
		// max image
		$new_image = $products->image_resize($extension, $max_image_size, $location, $output_dir, $fileName, 90);
		
		// delete temp image
		$filePath = $output_dir. $fileName;
		
		if (file_exists($filePath)) 
		{
        	unlink($filePath);
		}
	}
	echo json_encode($ret);
 }
?>