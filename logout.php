<?php
session_start();

// Verwijder alle sessievariabelen
$_SESSION = array();

// Vernietig de sessie
session_destroy();

// Stuur de gebruiker door naar de inlogpagina
header("Location: login.php");
exit;
?>