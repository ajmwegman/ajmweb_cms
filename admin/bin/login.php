<?php
session_start();
$sessid = session_id();

error_reporting( E_ALL );
ini_set( "display_errors", 1 );

include( "../../system/database.php" );

require_once( "../src/database.class.php" );
require_once( "../src/login.class.php" );

$db = new database( $pdo );
$login = new login( $pdo );

# error globals
$success = "Aanmelden is gelukt, een ogenblik geduld...";
$error  = '<strong> Let op! We hebben nog een paar dingen die niet in orde zijn!</strong>';
$error_pass  = 'Wachtwoord of gebruikersnaam onjuist.';

$empty_fields = "Vul zowel gebruikersnaam als wachtwoord in.";
$error_password = "Wachtwoord onjuist.";

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {

    $pass = 0;
    $alert = "";
    
    if(empty($_POST['username']) || empty($_POST['password'])) {
        $pass = 1;
        $alert .= "<li>" . $empty_fields . "</li>";
    }   else {
        $username = $_POST['username'];
        $password = $_POST['password'];
    }
    
    $pass_check = $login->checkLogin($username, $password);
    
    if($pass_check != true) {
        $pass = 1;
        $alert .= $error_password;
    } 
    
     if ($pass == 0 ) { 
        echo '<div class="alert alert-success" role="alert">'.$success.'</div>';

         $userinfo = $login->getUserInfo('username', $username);

        $_SESSION['loggedin'] = 'yes';
        $_SESSION['sess_hash'] = $userinfo['hash'];
        // Sla gebruikersrol op in de sessie
        $role = ($userinfo['user_level'] == '1') ? 'admin' : 'user';
        $_SESSION['role'] = $role;
         
        if(isset($_POST['remember']) && $_POST['remember'] == 'on') {
             // Set a secure remember me cookie
             $cookie_value = $userinfo['hash'] . '_' . time();
             setcookie("remember_me", $cookie_value, time() + (86400 * 30), "/", "", true, true); // 30 days, secure, httpOnly
             
             // Also store in database for additional security (optional)
             // $login->storeRememberToken($userinfo['id'], $cookie_value);
        }
        ?>
        <script>
         setTimeout(function(){
            window.location.href = '/admin/index.php';
         }, 2000);
        </script>
    <?php
	  } else {
		 echo '<div class="alert alert-danger" role="alert">'.$error_password.'</div>';
	  }

} else {
    echo '<div class="alert alert-danger" role="alert">
    '.$error.'<br>
    '.$alert.'</div>';

}

?>