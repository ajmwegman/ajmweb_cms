<?php
@session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 );

$path = $_SERVER['DOCUMENT_ROOT'];

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );
require_once( $path."/admin/modules/keywords/src/keywords.class.php" );
require_once( $path."/admin/functions/forms.php" );

$db = new database( $pdo );
$keywords = new keywords( $pdo );

require_once("summary.php");
?>
<script src="/admin/modules/keywords/js/js.js"></script>