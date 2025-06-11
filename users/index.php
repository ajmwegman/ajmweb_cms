<?php 
session_start();
require_once("../config.php"); 

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Als de gebruiker niet is ingelogd, stuur dan door naar een inlogpagina
    header("Location: /login.php");
    exit();
} else {
    $loggedin = true;
}

// Controleer of session_hash is ingesteld
if (isset($_SESSION['session_hash'])) {
    // Aanname: $pdo is je database connectie en $userId is de ID van de gebruiker
    $users = new users($pdo);
    $userData = $users->getUserDataByUserId($_SESSION['session_hash']);
    $userId = $users->getUserIdByHash($_SESSION['session_hash']);
} else {
    // Foutafhandeling of omleiding naar de inlogpagina
    header("Location: /login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="nl"><head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $title; ?></title>
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

    <script>
        $(document).ready(function(){
            $("#submit_password").click(function(){ // Veranderde 'submit' naar 'click'
                $.ajax({
                    type: "POST",
                    url: "/users/bin/password_reset.php",
                    data: $("#password_reset_form").serialize(),
                    success: function(response) {
                        $("#message_container").empty(); // Leeg de meldingsdiv voordat we nieuwe meldingen toevoegen
                        
                            $("#success_message").addClass("d-none"); // Verberg het succesbericht als het niet nodig is

                            // Voeg alle ontvangen meldingen toe aan de meldingsdiv
                            $("#message_container").html(response);
                            
                            // Automatisch uitloggen na 3 seconden
                            setTimeout(function(){
                                window.location.href = "/logout.php";
                            }, 3000);
                    },
                    error: function(xhr, status, error) {
                        console.error("Er is een fout opgetreden: " + error); // Toon een foutmelding als er iets misgaat
                    }
                });
            });
            
$(document).ready(function(){
    $("#submit_username").click(function(){ // Veranderde 'submit' naar 'click'
        $.ajax({
            type: "POST",
            url: "/users/bin/username_reset.php",
            data: $("#username_reset_form").serialize(),
            success: function(response) {
                $("#username_message_container").empty(); // Leeg de meldingsdiv voordat we nieuwe meldingen toevoegen
                
                // Toon de meldingen die zijn ontvangen van de server
                $("#username_message_container").html(response);
                
                // Controleer of er geen fouten zijn opgetreden en de gebruikersnaam succesvol is bijgewerkt
                if (!response.includes("alert-danger")) {
                    // Automatisch uitloggen na 3 seconden als er geen fouten zijn opgetreden
                    setTimeout(function(){
                        window.location.href = "/logout.php";
                    }, 3000);
                }
            },
            error: function(xhr, status, error) {
                console.error("Er is een fout opgetreden: " + error); // Toon een foutmelding als er iets misgaat
            }
        });
    });
});


        });
    </script>
</head>

<body>
<div id="display" class="alert-fixed"></div>
<?php 
require_once("../".$theme."/sections/header.php"); 
require_once($_SERVER['DOCUMENT_ROOT']."/users/user_nav.php");
require_once("../".$theme."/sections/footer.php");
?>