<?php
declare(strict_types=1);
session_start();
$sessid = session_id();

error_reporting( E_ALL );
ini_set( "display_errors", 1 );

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$group_id = 1;

include( "../system/database.php" );

require_once ("../src/database.class.php");
require_once ("../src/site.class.php");
require_once ('../src/Exception.php');
require_once ('../src/PHPMailer.php');
require_once ('../src/SMTP.php');
require_once ("../functions/csrf.php");

$db   	 = new database($pdo);
$site  	 = new site($pdo);

$info    	= $site->getWebsiteInfo($group_id);

$success = '<div class="alert alert-success mt-4" role="alert">
  Uw bericht is succesvol verstuurd.
</div>';

$error = '
<div class="alert alert-danger mt-4" role="alert">
  Er is een fout opgetreden,  neem contact op met de website eigenaar.
</div>';

$name    = isset($_POST['name']) ? $_POST['name'] : '';
$email   = isset($_POST['email']) ? $_POST['email'] : '';
$phone   = isset($_POST['phone']) ? $_POST['phone'] : '';
$subject = isset($_POST['subject']) ? $_POST['subject'] : '';
$message = isset($_POST['message']) ? $_POST['message'] : '';

if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    echo '<div class="alert alert-danger mt-4" role="alert">Ongeldige CSRF-token.</div>';
    exit;
}

if(empty($name) || empty($email) || empty($subject) || empty($message)  ) {
    
    $error = '
<div class="alert alert-danger mt-4" role="alert">
  Niet alle velden zijn ingevuld.
</div>';
    
    echo $error;
}
else {

    //PHPMailer Object
    $mail = new PHPMailer(true); //Argument true in constructor enables exceptions

    //From email address and name
    $mail->From = $info['std_mail'];
    $mail->FromName = $info['web_naam'];

    //To address and name
    $mail->addAddress($info['std_mail'], $name);
    //$mail->addAddress("recepient1@example.com"); //Recipient name is optional

    //Address to which recipient will reply
    $mail->addReplyTo($email, "Reply");

    //CC and BCC
    //$mail->addCC("cc@example.com");
    //$mail->addBCC($info['std_mail']);

    //Send HTML or Plain Text email
    $mail->isHTML(true);

    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->AltBody = strip_tags($message);

    try {
        $mail->send();
        echo $success;
    } catch (Exception $e) {
        //echo "Mailer Error: " . $mail->ErrorInfo;
        echo $error;
    }
}