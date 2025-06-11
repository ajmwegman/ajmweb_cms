<?php
session_start();
$sessid = session_id();

error_reporting( E_ALL );
ini_set( "display_errors", 1 );

include( "../../system/database.php" );

require_once( "../src/database.class.php" );
require_once( "../src/login.class.php" );
require_once( "../template/mails/password_recovery.php" );

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
$success = "We hebben een herstel link naar het e-mailadres verstuurd.";
$error = '<strong> Let op! We hebben nog een paar dingen die niet in orde zijn!</strong>';
$error_mail = 'E-mailadres onbekend.';

$empty_fields = "Vul een juist e-mailadres in.";

/* standard mail settings */
$subject = "Uw wachtwoord herstellen.";
$std_mail = "ajmwegman@gmail.com";
$std_sender = "Webstore Manager";
$message = $mail_body;

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {

  $pass = 0;
  $alert = "";

  $email = trim( $_POST[ 'email' ] );

  if ( empty( $_POST[ 'email' ] ) || !filter_var( $_POST[ 'email' ], FILTER_VALIDATE_EMAIL ) ) {
    $pass = 1;
    $alert .= "<br>" . $empty_fields;
  } else {
    $pass = 0;
  }

  $mailcheck = $login->checkMailExist( $email );

  if ( $mailcheck != true ) {
    $pass = 1;
    $alert .= "<br>" . $error_mail;
  } else {
    $pass = 0;
  }

  if ( $pass == 0 ) {

    $user = $login->getLogin( $email );

    $uid = $user['id'];
    $email = $user['email'];
    $firstname = $user['firstname'];
    $surname = $user['surname'];
      
    $name = $firstname." ".$surname;
      
    $recover_link = $login->mailRecoveryLink( $uid, 'https://'.$_SERVER['HTTP_HOST'].'/admin/reset.php?ref=');

    //send mail
    $keywords = array("[FIRSTNAME]", "[LINK]");
    $replacers = array($firstname, $recover_link);
    $body = str_replace($keywords, $replacers, $message);
      
    //Recipients
    $mail->setFrom('info@huysontruiming.nl', 'Webstore Manager');
    $mail->addAddress($email, $name);     //Add a recipient

    $mail->addReplyTo('info@huysontruiming.nl', 'Huysontruiming.nl');
  
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->AltBody = strip_tags($body);

    $mail->send();

    echo '<div class="alert alert-success" role="alert">' . $success . '</div>';
    
  } else {
    echo '<div class="alert alert-danger" role="alert">';
    echo $error . "<br>";
    echo $alert;
    echo '</div>';
  }

}
?>