<?php require_once("../config.php"); 

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Als de gebruiker niet is ingelogd, stuur dan door naar een inlogpagina
    header("Location: /login.php");
    exit();
}
else {
    $action = $_GET['action'];
    
    // Aanname: $pdo is je database connectie en $userId is de ID van de gebruiker
    $users = new users($pdo);
    $userData = $users->getUserDataByUserId($_SESSION['session_hash']);
    $userId = $users->getUserIdByHash($_SESSION['session_hash']);

?>
<!DOCTYPE html>
<html lang="nl"><head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $title; ?> | aanmelden</title>
  <meta content="<?php echo $meta_description; ?>" name="description">
  <meta content="<?php echo $meta_keywords; ?>" name="keywords">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous"></script>

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

</head>

<body>
<div id="display" class="alert-fixed"></div>
<?php 
require_once("../".$theme."/sections/header.php"); 
?>
    <section class="mt-5">
        <div class="container mt-5">
            <?php
            if($action == 'address')   { require_once("forms/address.php"); }
            if($action == 'orders')    { require_once("forms/orders.php"); }
            ?>
        </div>
    </section>
<?php
require_once("../".$theme."/sections/footer.php");
?>
<?php } ?>