<?php
session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 );

if(isset($_GET['sid'])) {

	unset($_SESSION['sid']);
	unset($_SESSION['group_id']);

	//$id = $menu->getGroupId($_GET['id']);
	$sid = $_GET['sid'];
	$_SESSION['sid'] = $sid;
	
} else {
	//unset($_SESSION['group_id']);
	
	// do nothing
}

header("Location: https://". $_SERVER['HTTP_HOST']."/admin/siteconfig/");
?>