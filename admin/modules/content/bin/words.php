<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$path = $_SERVER[ 'DOCUMENT_ROOT' ];

require_once( $path . "/system/database.php" );
require_once( $path . "/admin/src/database.class.php" );
require_once( $path . "/admin/modules/content/src/content.class.php" );

$db = new database( $pdo );
$content = new content( $pdo );

$group_id = !empty( $_SESSION[ "group_id" ] ) ? $_SESSION[ "group_id" ] : '';
$keyword = !empty( $_GET[ "k" ] ) ? $_GET[ "k" ] : '';

//echo "<br>group_id: ". $group_id;
//echo "<br>keyword: ". str_replace("[]", "", $keyword);
$list = $content->getKeywords( $group_id, $keyword );

//print_r($list);
echo json_encode( $list ); // returns ['jayden', 'sam', 'alvin', 'david']

?>
