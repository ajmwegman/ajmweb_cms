<?php
session_start();
$sessid = session_id();

error_reporting( E_ALL );
ini_set( "display_errors", 1 );

include( "../system/database.php" );

require_once( "../src/database.class.php" );
require_once( "src/menulist.class.php" );
require_once( "src/login.class.php" );
require_once( "src/analytics.class.php" );
require_once( "functions/forms.php" );

$db = new database( $pdo );
$menu = new menu( $pdo );
$login = new login( $pdo );
$analytics = new Analytics( $pdo );

$group_id = !isset( $_SESSION[ 'group_id' ] ) ? 0 : $_SESSION[ 'group_id' ];
$sid = !isset( $_SESSION[ 'sid' ] ) ? 0 : $_SESSION[ 'sid' ];


?>