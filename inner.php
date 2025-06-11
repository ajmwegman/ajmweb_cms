<?php
session_start();
$sessid = session_id();

error_reporting( E_ALL );
ini_set( "display_errors", 1 );

$group_id = 1;
$loc_website = LOC_WEBSITE;

if(isset($_GET['url'])) { 
$url = $_GET['url'];
} else {
    echo "Pagina kan niet worden aangeroepen.";
    exit();
}
include( "system/database.php" );

$theme = 'multipage';

require_once("src/database.class.php");
require_once("src/site.class.php");

$db   	 = new database($pdo);
$site  	 = new site($pdo);

$menu 		= $site->getActiveMenuItems($group_id);
$sections 	= $site->getActiveContent($group_id);
$info    	= $site->getWebsiteInfo($group_id);
$keywords  	= $site->getKeywords($group_id );
$replacers 	= $site->getReplacers($group_id );

function build_text($text, $keywords, $replacers) {
    
    $new_text = str_replace($keywords, $replacers, $text);
    
    return $new_text;
}

function build_section($content, $input, $script) {
    
    $build_section = str_replace($input, $script, $content);
    
    return $build_section;
}

$article = $site->getSingleContent($url);
$content = build_text($article['content'], $keywords, $replacers);
$title = $article['title'];
?>
<!DOCTYPE html>
<html lang="nl">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $title." | ".$info['web_naam']; ?></title>
  <meta content="<?php echo $info['description']; ?>" name="description">
  <meta content="<?php echo $info['keywords']; ?>" name="keywords">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous"></script>

  <!-- Favicons -- >
  <link href="themes/< ?php echo $theme; ?>/assets/img/favicon.png" rel="icon">
  <link href="themes/< ?php echo $theme; ?>/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Dosis:300,400,500,,600,700,700i|Lato:300,300i,400,400i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?php echo $loc_website; ?>/themes/<?php echo $theme; ?>/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $loc_website; ?>/themes/<?php echo $theme; ?>/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo $loc_website; ?>/themes/<?php echo $theme; ?>/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?php echo $loc_website; ?>/themes/<?php echo $theme; ?>/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="<?php echo $loc_website; ?>/themes/<?php echo $theme; ?>/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?php echo $loc_website; ?>/themes/<?php echo $theme; ?>/assets/css/style.css" rel="stylesheet">

<script>
$( document ).ready( function () {

	// start ---> prepare all forms for ajax submission //
	var options = {
		target: '#display',
		success: function() {
			
			$('#display').fadeIn("slow").delay(2000).fadeOut("slow");
			
		}
	};
	
	$('#contactform').ajaxForm(options);
	// --- end --- //
	
});
</script>
<?php require("head.php"); ?>
</head>

<body>
<div id="display" class="alert-fixed"></div>
<?php 
require_once("themes/".$theme."/sections/header.php"); 
require_once("themes/".$theme."/sections/breadcrum.php");
require_once("themes/".$theme."/sections/article.php");
//require_once("themes/".$theme."/sections/contact.php");
require_once("themes/".$theme."/sections/footer.php");
    
// this if the final file at all times.
require_once("bottom.php");
?>
</body>
</html>