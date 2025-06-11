<?php
$current_lang = "English";

function lang($phrase){

static $_L = array(

	/* forms */
	'CONTACT_FORM' => 'Contact form',
	'NAME' => 'Name',
	'GENDER_FORM' => 'Title',
	'GENDER_MALE' => 'Mr.',
	'GENDER_FEMALE' => 'Mrs.',
	'FIRSTNAME' => 'Firstname',
	'INSERTION' => 'Insertations',
	'INSERTION_EXAMPLE' => 'examples, the',
	'PHONE' => 'Phone',
	'PHONENUMBER' => 'Phonenumber',
	'MOBILENUMBER'=> 'Mobile',
	'STREET' => 'Street',
	'HOUSENUMBER' => 'Housenumber',
	'HOUSENUMBER_ADDON' => 'addon',
	'ZIPCODE' => 'Zipcode',
	'CITY' => 'City',	
	'COUNTRY' => 'Country',
	'SPECIAL_DETAILS' => 'Details, possibly also in the medical field',
	'WHY_DO_YOU_WANT_YOGA' => 'Why do you want yoga class',
	'NEWSLETTER' => 'Newsletter',
	'NEWSLETTER_TEXT' => 'Yes, I wish to register for the free newsletter. <br> (I can unsubscribe again at any time)',
	'LASTNAME' => 'Surname',
	'EMAIL' => 'E-mail',
	'SUBJECT' => 'Subject',
	'MESSAGE' => 'Message',
	'LANGUAGE' => 'Language',
	'READ_MORE' => 'Read more',
	'UP' => 'Back to Top',
	
	# act_mail.php
	'NB_TITLE' => 'Newsletter subscription.',
	'NO_ID' => 'No known ID',
	'FORWARD_TO_MAINPAGE' => 'You will be redirected to the mainpage.',
	'EMAIL ACTIVE' => 'Your e-mail address is activated.',
	'EMAIL_ACTVATED' => 'Your e-mail address is already activated.',
	
	/* SUBSCRIBE FORM */
	'SUBSCRIBE' => 'Subscribe',

	/* ALERTS */
	'ERROR_MESSAGE' => 'Caution! We still have a few things that are not in order!',
	'THANKS_MESSAGE' => '<strong> Thanks for your message! </ strong> <br/> We will contact you as soon as possible.',
	'THANKS_NEWSLETTER_MESSAGE' => '<strong> Thanks for signing up! </ Strong> <br/> To activate your account, we sent an activation email. After confirming your registration is active. ',
	'SUBMIT_ERROR' => 'In this way, no data can be transmitted.',
	'FILL_FISTRNAME' => 'Enter your first name.',
	'FILL_LASTNAME' => 'Please enter your last name. "',
	'FILL_EMAIL' => 'Please enter your email address.',
	'FILL_PHONE' => 'Enter your phone number.',
	'FILL_STREET' => 'Please enter your street name. ',
	'FILL_ZIPCODE' => 'Please enter your zipcode. ',
	'FILL_SUBJECT' => 'Please enter a subject. ',
	'FILL_MESSAGE' => 'Please enter a message. ',
	'CHECK_EMAIL' => 'Please enter your email address in the following format: <strong> uwnaam@voorbeeld.nl </ strong>?',
	'CHECK_NAME' => 'There are strange characters in your name.',
	'CHECK_SUBJECT' => 'There are strange characters in your subject',
	'CHECK_SPAM' => 'You have just sent a message. <br /> To prevent spam, you must wait until you can again send a message at least 1 minute',
	'CHECK_LAND_AND_ZIPCODE' => 'Check your country and postal code',
	'EMAIL_IN_USE' => 'This email address is already used.',
	'CHECK_PASSWORDS' => 'Check the passwords.',
	
	/* Buttons */
	'SUBMIT' => 'Send',
	
	/* No comma */
	'LAST' => 'Last'
);

    return (!array_key_exists($phrase,$_L)) ? $phrase : $_L[$phrase];
}