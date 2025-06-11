<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$path = $_SERVER['DOCUMENT_ROOT'];

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );
require_once( $path."/admin/modules/theme/src/theme.class.php" );

$db = new database($pdo);
$themeConfig = new themeConfig($pdo);


// Upload image
if ($_FILES['file']) {
    $thumbnailUrl = $themeConfig->uploadImage($_FILES['file']);
    echo json_encode(array('thumbnailUrl' => $thumbnailUrl));
}
?>
