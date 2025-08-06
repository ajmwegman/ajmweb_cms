<?php

if(isset($_COOKIE['rememberme']) && $_COOKIE['rememberme'] == 'yes' ) {
    
   $_SESSION['loggedin'] = 'yes';
    
    setcookie("rememberme", 'yes', time()+86400*14);
}

if (!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] != 'yes') {
    header("Location: /admin/login.php");
} else {
    
    // run  user specific data
}


?>