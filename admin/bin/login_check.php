<?php
/*
if(isset($_COOKIE['remember']) ) {
    
   $_SESSION['loggedin'] = 'yes';
   $_SESSION['sess_hash'] = $_COOKIE['remember'];
    
    setcookie("remember", $_SESSION['sess_hash'], time()+86400*14, "/");
}

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['sess_hash'])) {
    
    header("Location: /admin/login.php");
} else {
    
    $hash = $_SESSION['sess_hash'];
    $userinfo = $login->getUserInfo('hash', $hash);
    
    $firstname = $userinfo['firstname'];
}*/

// Controleer of de gebruiker al ingelogd is in de sessie
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['sess_hash'])) {
    header("Location: /admin/login.php");
    exit; // Voorkomt verdere uitvoering na doorverwijzing
}

// Haal de sessie hash op en voer validatie uit
$hash = $_SESSION['sess_hash'];
if (empty($hash)) {
    // Behandel het geval waar de sessie hash ontbreekt of leeg is
    header("Location: /admin/login.php");
    exit;
}

// Gebruikersinformatie ophalen
$userinfo = $login->getUserInfo('hash', $hash);

// Controleer of gebruikersinformatie succesvol is opgehaald
if (!$userinfo) {
    // Foutafhandeling als gebruikersinformatie niet kan worden opgehaald
    echo "Er is een fout opgetreden.";
    exit;
}

// XSS Bescherming
$firstname = htmlspecialchars($userinfo['firstname'], ENT_QUOTES, 'UTF-8');

?>

