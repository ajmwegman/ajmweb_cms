<?php
require_once("config.php");

$content = $site->getSingleContent("contact");
$article = (!$content) ? "Binnenkort meer..." :  nl2br($content['content']);
?>
<!DOCTYPE html>
<html lang="nl">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $info['web_naam']; ?></title>
  <meta content="<?php echo $info['description']; ?>" name="description">
  <meta content="<?php echo $info['keywords']; ?>" name="keywords">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous"></script>

  <!-- Favicons -- >
  <link href="favicon.png" rel="icon">
  <link href="apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Dosis:300,400,500,,600,700,700i|Lato:300,300i,400,400i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?php echo $site_location.$theme; ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $site_location.$theme; ?>assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo $site_location.$theme; ?>assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?php echo $site_location.$theme; ?>assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="<?php echo $site_location.$theme; ?>assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?php echo $site_location.$theme; ?>assets/css/main.css" rel="stylesheet">

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
</head>

<body>
<div id="display" class="alert-fixed"></div>
<?php 
require_once($theme."sections/header.php"); 
require_once($theme."sections/jumbotron_small.php"); 
require_once($theme."sections/contact.php");
require_once($theme."sections/footer.php");
?>