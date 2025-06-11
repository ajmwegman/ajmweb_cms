<?php
session_start();
$sessid = session_id();

error_reporting( E_ALL );
ini_set( "display_errors", 1 );

include( "../../system/database.php" );

require_once( "../src/database.class.php" );
require_once( "../src/login.class.php" );
require_once( "../template/mails/password_new.php" );

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../assets/vendor/PHPMailer/src/PHPMailer.php';
require '../assets/vendor/PHPMailer/src/Exception.php';


//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

$db = new database( $pdo );
$login = new login( $pdo );

# error globals
$success = "We hebben het wachtwoord aangepast.";
$error = '<strong> Let op! We hebben nog een paar dingen die niet in orde zijn!</strong>';

$empty_fields         = 'Vul het wachtwoord en controle wachtwoord in.';
$empty_hash         = 'Geen referentie.';
$error_password_check = 'Wachtwoorden zijn niet gelijk.';

/* standard mail settings */
$subject    = "Het wachtwoord is aangepast.";
$std_sender = "Webstore Manager";
$message    = $mail_body;

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {

    $pass = 0;
    $alert = "";

    $password = trim( $_POST[ 'password' ] );
    $password1 = trim( $_POST[ 'password1' ] );
    
    if ( empty( $_POST['hash'] ) ) {
        $pass = 1;
        $alert .= "<br>" . $empty_hash;
    } else {
        $hash = $_POST['hash'];
        $pass = 0;
    }
    
    if ( empty( $password ) || empty( $password1 ) ) {
        $pass = 1;
        $alert .= "<br>" . $empty_fields;
    } else {
        $pass = 0;
    }
    
    if ( $password != $password1 ) {
        $pass = 1;
        $alert .= "<br>" . $error_password_check;
    } else {
        $pass = 0;
    }

    if ( $pass == 0 ) {

        $user = $login->getLoginHash( $hash );

        $uid       = $user['id'];
        $email     = $user['email'];
        $firstname = $user['firstname'];
        $surname   = $user['surname'];

        $name = $firstname." ".$surname;

        //update
        $new_pass = password_hash($password, PASSWORD_BCRYPT);
        
        $sql = "UPDATE group_login SET password=:password WHERE id=:id";
        $result = $db->runQuery($sql, ['password'=>$new_pass, 'id'=>$uid]);
        
        // delete no need edit password anymore
        //$delete = $db->deletedata('group_password', 'uid', $uid);
        
        //send mail
        $keywords = array("[FIRSTNAME]");
        $replacers = array($firstname);
        $body = str_replace($keywords, $replacers, $message);

        //Recipients
        $mail->setFrom('info@huysontruiming.nl', 'Webstore Manager');
        $mail->addAddress($email, $name);     //Add a recipient

        $mail->addReplyTo('info@huysontruiming.nl', 'Webstore Manager');

        //Content
        $mail->isHTML(true);                   //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);

        $mail->send();
        echo '<div class="alert alert-success" role="alert">' . $success . '</div>';
    ?>

        <script>
         setTimeout(function(){
            window.location.href = '/admin/login.php';
         }, 2000);
        </script>
        
        <?php
    } else {
        echo '<div class="alert alert-danger" role="alert">';
        echo $error . "<br>";
        echo $alert;
        echo '</div>';
    }
}
?>