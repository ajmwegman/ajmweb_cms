<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$path = $_SERVER['DOCUMENT_ROOT'];
$max_image_size = 960;

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );

require_once( $path."/admin/modules/gallery/src/gallery.class.php" );

$db      = new database($pdo);
$gallery  = new gallery($pdo);	

$allowed = array('jpg','jpeg','pjpeg','gif','png'); 

$success = '<div class="loader spinner-grow" style="width: 1rem; height: 1rem;" role="status">
			<span class="sr-only"></span>
			</div>';

$error = '<div class="alert alert-danger" role="alert">Er is iets fout gegaan!</div>';

$hash = (isset($_POST['hash'])) ? $_POST['hash'] : "";

$output_dir = $_SERVER['DOCUMENT_ROOT']."/temp/";
$location = $_SERVER['DOCUMENT_ROOT']."/gallery/";

// selecet filename to delete image on server
$row = $gallery->getImage("hash", $hash );

$image = $row['image'];

// if there is an imagename in the database
// overwrite database name and delete image.
if(!empty($image)) {
    
    $filename = $location.$image;
    
    $sql = "UPDATE group_gallery SET image=:image WHERE hash=:hash";
    $go = $db->runQuery($sql, ['image'=>'', 'hash'=>$hash]);
    
    if (file_exists($filename)) {
		@unlink($filename);
    }
}


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

	$error =$_FILES["myfile"]["error"];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 
	if(!is_array($_FILES["myfile"]["name"])) //single file
	{
	 	$fileName = $_FILES["myfile"]["name"];
		
 		move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);
		$ret[]= $fileName;
		
		list($width, $height, $type, $attr) = getimagesize($output_dir.$fileName);

        $sql = "UPDATE group_gallery SET image=:image WHERE hash=:hash";
        $go = $db->runQuery($sql, ['image'=>$fileName, 'hash'=>$hash]);
        
		$extension = $gallery->check_extension($fileName, $allowed);
		
		// max image
		$gallery = $gallery->image_resize($extension, $max_image_size, $location, $output_dir, $fileName, 90);
		
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