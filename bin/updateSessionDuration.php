<?php
session_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);

include("../system/database.php");

require_once("../src/database.class.php");
require_once("../src/analytics.php");

// Initialize Analytics object with PDO connection
$analytics = new AdvancedAnalytics($pdo);

// Stel de sessiestarttijd en sessie-ID in indien nodig
if (!isset($_SESSION['session_start_time'])) {
    $_SESSION['session_start_time'] = time();
}
if (!isset($_SESSION['session_id'])) {
    $_SESSION['session_id'] = session_id();
}

// Andere vereiste includes en initialisaties

// Voer de code uit om de sessieduur bij te werken op deze pagina
$analytics->updateSessionDuration($_SESSION['session_id'], time() - $_SESSION['session_start_time']);

?>
