<?php
error_reporting(E_ALL &~ E_DEPRECATED);
ini_set("display_errors", 1);

$group_id = 1;

include( "system/database.php" );


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require_once ("src/database.class.php");
require_once ("src/site.class.php");
require_once ('src/Exception.php');
require_once ('src/PHPMailer.php');
require_once ('src/SMTP.php');
require_once ("functions/csrf.php");

$site  	 = new site($pdo);

$info    	= $site->getWebsiteInfo($group_id);

$recaptcha_secret = '6Lcbis8iAAAAAF8BJiG0v4kVwF7TpWw2zGAtrxgB';

$success = '<div class="alert alert-success mt-4" role="alert">
  Uw bericht is succesvol verstuurd.
</div>';

$error = '
<div class="alert alert-danger mt-4" role="alert">
  Er is een fout opgetreden,  neem contact op met de website eigenaar.
</div>';

// Server side validation
function isValid() {
    // This is the most basic validation for demo purpose. Replace this with your own server side validation
    if($_POST['name'] != "" && $_POST['email'] != "" && $_POST['message'] != "") {
        return true;
    } else {
        return false;
    }
}

// Sanitizing the data, kind of done via error messages first. Twice is better!
function clean_var($variable) {
    $variable = strip_tags(stripslashes(trim(rtrim($variable))));
  return $variable;
}

$error_output = '';
$success_output = '';

// Build POST request to get the reCAPTCHA v3 score from Google
$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
$recaptcha_response = $_POST['recaptcha_response'];

if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    $error_output = 'Ongeldige CSRF-token.';
} elseif(isValid()) {

    $message = $_POST['message'];
    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $phone   = '';
    $subject   = $_POST['subject'];
    
    // Make and decode POST request
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    // Take action based on the score returned
    if ($recaptcha->success == true && $recaptcha->score >= 0.5 && $recaptcha->action == 'contact') {
        // This is a human. Insert the message into database OR send a mail
        $success_output = "Uw bericht is succesvol verstuurd";
        
        //Construct the message.
	    $subject = clean_var($subject);
		
		$message = clean_var($message) . "<br /><br />";
		$message .= "Afzender: " . clean_var($name) . "<br />";
		$message .= "E-mail: " . clean_var($email) . "<br />";
	    $message .= "Telefoon: " . clean_var($phone) . "<br />";
        
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

    } else {
        // Score less than 0.5 indicates suspicious activity. Return an error
        $error_output = "Er ging iets mis, probeer het later nog eens.";
    }
} else {
    // Server side validation failed
    $error_output = "Vul alle verplichte velde in.";
}

$output = array(
    'error'     =>  $error_output,
    'success'   =>  $success_output
);

// Output needs to be in JSON format
//echo json_encode($output);

?>