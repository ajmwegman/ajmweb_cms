<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 );

$path = $_SERVER['DOCUMENT_ROOT'];

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );
require_once( $path."/admin/functions/forms.php" );

require_once( $path."/admin/modules/carousel/src/carousel.class.php" );

$db = new database( $pdo );
$carousel = new carousel( $pdo );

require_once("summary.php");
?>
<script src="/admin/modules/carousel/js/menulist.js"></script>