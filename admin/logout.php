<?php
session_start();

session_destroy();

if (isset($_COOKIE['remember'])) {
    unset($_COOKIE['remember']); 
    setcookie("remember", null, time() - (86400 * 30), "/"); 
    //echo 'del';
} else {
   // echo 'nope';
}

header("Location: /admin/login.php");
exit();
?>