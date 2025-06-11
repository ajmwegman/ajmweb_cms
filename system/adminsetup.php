<?php
error_reporting(E_ALL);

ini_set("display_errors", 1);

require_once("database.php");
require_once($_SERVER['DOCUMENT_ROOT']."/src/database.class.php");

$db   	   = new database($pdo);

/* VARIABELEN */

/* program */
$program = "WSMX";
$version = "10.0.2";

# Gallery Toegestane Formaten
$allowed = array('jpg','jpeg','pjpeg','gif','png'); 

// Mappen
$mini_map  = "item_images/mini_image/";
$thumb_map = "item_images/thumbs/";
$image_map = "item_images/images/";
$large_map = "item_images/images_large/";
$temp_map  = "item_images/temp/";

// Instellingen
$forms = 1;           				// Standaard aantal file uploads
$max_size = 4096000;        			// Maximale bestands grootte, 0 voor ongelimiteerd
$max_sized = $max_size / 1000;			// Maximale bestands grootte, in Kb.
$max_mini = 100;				// mini thumb
$max_thumb = 150;				// Maximale hoogte dan wel breedte van de thumb.
$max_med = 295;					// Maximale hoogte dan wel breedte van de medium img.
$max_img = 640; 				// Maximale hoogte dan wel breedte van de maximum imgage.
$cookieDuration = 3600*24*365;  // standaard cookie tijd
       
$lang = 'nl';

/* language */
if(!isset($_COOKIE['lang'])) {
	
	setcookie('lang', 'nl', time()+$cookieDuration, '/');
		
	require_once($_SERVER['DOCUMENT_ROOT']."/lang/nl.php");

} else {
	$lang = $_COOKIE['lang'];
	
	require_once($_SERVER['DOCUMENT_ROOT']."/lang/".$lang.".php");

}

?>