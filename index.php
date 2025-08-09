<?php require_once("config.php"); ?>
<!DOCTYPE html>
<html lang="nl"><head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $title; ?></title>
  <meta content="<?php echo $meta_description; ?>" name="description">
  <meta content="<?php echo $meta_keywords; ?>" name="keywords">

  <!-- Favicons -- >
  <link href="themes/onepage/assets/img/favicon.png" rel="icon">
  <link href="themes/onepage/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Dosis:300,400,500,,600,700,700i|Lato:300,300i,400,400i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?php echo $site_location; ?><?php echo $theme; ?>/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $site_location; ?><?php echo $theme; ?>/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo $site_location; ?><?php echo $theme; ?>/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?php echo $site_location; ?><?php echo $theme; ?>/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="<?php echo $site_location; ?><?php echo $theme; ?>/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!--- Template Main CSS File -->
  <link href="<?php echo $site_location; ?><?php echo $theme; ?>/assets/css/style.css" rel="stylesheet">

  <!-- Optimized Transitions CSS -->
  <link href="<?php echo $site_location; ?>assets/css/transitions-optimized.css" rel="stylesheet">

    <!-- Global site tag (gtag.js) - Google Analytics -->
<style>
	.navbar-collapse { display: block !important; }
</style>
</head>

<body>
<div id="display" class="alert-fixed"></div>
<?php 
require_once($theme."/sections/header.php"); 
    
require_once($_SERVER['DOCUMENT_ROOT']."/modules/carousel.php");
    
        echo $sessid;

require_once($theme."/sections/sections.php");
require_once($_SERVER['DOCUMENT_ROOT']."/modules/gallery.php");
require_once($theme."/sections/contact.php");
require_once($theme."/sections/footer.php");
?>
  <!-- Template Main JS File -->
  <script src="<?php echo $site_location; ?><?php echo $theme; ?>/assets/js/main.js"></script>

  <!-- Transitions Optimizer JS -->
  <script src="<?php echo $site_location; ?>assets/js/transitions-optimizer.js"></script>