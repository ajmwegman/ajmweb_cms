<?php
// These files are already included by config.php, so we don't need to include them again
// include( "../../system/database.php" );
// require_once( "../src/database.class.php" );
// require_once( "../src/login.class.php" );

// $db = new database( $pdo );
// $login = new login( $pdo );

// Remember me functionality
if(isset($_COOKIE['remember_me']) && (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== 'yes')) {
    $cookie_value = $_COOKIE['remember_me'];
    $parts = explode('_', $cookie_value);
    
    if (count($parts) === 2) {
        $hash = $parts[0];
        $timestamp = $parts[1];
        
        // Check if cookie is not too old (30 days)
        if (time() - $timestamp < (86400 * 30)) {
            $userinfo = $login->getUserInfo('hash', $hash);
            if ($userinfo) {
                $_SESSION['loggedin'] = 'yes';
                $_SESSION['sess_hash'] = $hash;
                
                // Refresh the cookie
                $new_cookie_value = $hash . '_' . time();
                setcookie("remember_me", $new_cookie_value, time() + (86400 * 30), "/", "", true, true);
            }
        } else {
            // Cookie is expired, remove it
            setcookie("remember_me", "", time() - 3600, "/");
        }
    }
}

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

