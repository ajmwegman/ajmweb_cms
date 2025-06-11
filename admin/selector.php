<?php
session_start();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 );

if(isset($_GET['id'])) {

	unset($_SESSION['group_id']);
	unset($_SESSION['sid']);

	//$id = $menu->getGroupId($_GET['id']);
	$id = $_GET['id'];
	$_SESSION['group_id'] = $id;
	
} else {
	//unset($_SESSION['group_id']);
	
	// do nothing
}

header("Location: https://". $_SERVER['HTTP_HOST']."/admin/menu/");
?>