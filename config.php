<?php
session_start();
$sessid = session_id();

error_reporting( E_ALL );
ini_set( "display_errors", 1 );

include( "system/database.php" );

# locations
$site_location = 'https://'.$_SERVER['HTTP_HOST'].'/';
$theme = 'themes/onepage';

# keys api code:<br>
define('IPSTACK_API_KEY', 'b91847fc6cd44e34bbb21a2561823080');


#includes
require_once("src/database.class.php");
require_once("src/site.class.php");
require_once("src/users.class.php");
require_once("src/analytics.php");

$db   	        = new database($pdo);
$site  	        = new site($pdo);
$analytics      = new AdvancedAnalytics($pdo);

// stats bijhouden
$analytics->startSession(); // Roep dit aan het begin van elke pagina aan
$analytics->recordVisit();

$config         = $site->getConfig(str_replace("www.", "", $_SERVER['HTTP_HOST']));

$shop_id        = $config['id'];
$web_naam       = $config['web_naam'];

$configsite     = $site->getConfigSite ($shop_id);
$logo           = $configsite['logo'];
$shop_type      = $configsite['shop_type'];
$location       = $configsite['location'];

$bgDefault      = $configsite['kleur1'];
$bgHighlight    = $configsite['kleur2'];
$colDefault     = $configsite['kleur3'];
$colHighlight 	= $configsite['kleur4'];

$getGroupId     = $site->getGroupId($web_naam);
$group_id       = $getGroupId['group_id'];

$menu 	     	= $site->getActiveMenuItems($group_id);
$sections   	= $site->getActiveContent($group_id);
$info           = $site->getWebsiteInfo($group_id);
$keywords   	= $site->getKeywords($group_id );
$replacers   	= $site->getReplacers($group_id );
$area           = $site->getArea($shop_id );
$banners        = $site->getBanners();

$title          = $site->build_text( $getGroupId[ 'title' ], $keywords, $replacers );
$meta_description    = $site->build_text( $getGroupId[ 'description' ], $keywords, $replacers );
$meta_keywords       = $site->build_text( $getGroupId[ 'keywords' ], $keywords, $replacers );

$webtitle       = $logo . " ". $shop_type ." <span>". $location ."</span>";
$loc_images     = "";

$reviews = $site->getReviews("DESC", 6);
$numreviews = $site->getReviews("DESC", 9999);
?>