<?php
session_start();

// Verwijder alle sessievariabelen
$_SESSION = array();

// Vernietig de sessie
session_destroy();

// Stuur de gebruiker door naar de inlogpagina module
header("Location: index.php?module=login");
exit;
?>