<?php
$current_lang = 'Nederlands';

function lang($phrase){

static $_L = array(

	/* forms */
	'CONTACT_FORM' => 'Contact formulier',
	'NAME' => 'Naam',
	'GENDER_FORM' => 'Aanhef',
	'GENDER_MALE' => 'de heer',
	'GENDER_FEMALE' => 'mevrouw',
	'FIRSTNAME' => 'Voornaam',
	'INSERTION' => 'Tussenenvoegsel',
	'INSERTION_EXAMPLE' => '(bijv. de, van der, etc)',
	'PHONE' => 'Telefoon',
	'PHONENUMBER' => 'Telefoonnummer',
	'MOBILENUMBER'=> 'Mobiel',
	'STREET' => 'Straat',
	'HOUSENUMBER' => 'Huisnummer',
	'HOUSENUMBER_ADDON' => 'Toevoeging',
	'ZIPCODE' => 'Postcode',
	'CITY' => 'Woonplaats',	
	'COUNTRY' => 'Land',
	'SPECIAL_DETAILS' => 'Bijzonderheden, eventueel ook op medisch vlak',
	'WHY_DO_YOU_WANT_YOGA' => 'Waarom wil je yogales',
	'NEWSLETTER' => 'Nieuwsbrief',
	'NEWSLETTER_TEXT' => 'Ja, ik meld mij aan voor de gratis nieuwsbrief. <br>(Ik kan mij op elk moment weer afmelden)',
	'LASTNAME' => 'Achternaam',
	'EMAIL' => 'E-mailadres',
	'SUBJECT' => 'Onderwerp',
	'MESSAGE' => 'Bericht',
	'LANGUAGE' => 'Taal',
	'READ_MORE' => 'Lees meer',
	'UP' => 'Naar boven',
	
	# act_mail.php
	'NB_TITLE' => 'Nieuwsbrief aanmelding.',
	'NO_ID' => 'Geen ID bekend',
	'FORWARD_TO_MAINPAGE' => 'Uw wordt doorverwezen naar de hoofdpagina.',
	'EMAIL ACTIVE' => 'Uw e-mailadres is geactiveerd.',
	'EMAIL_ACTVATED' => 'Uw e-mailadres is reeds geactiveerd',
	
	/* SUBSCRIBE FORM */
	'SUBSCRIBE' => 'Inschrijven',

	/* ALERTS */
	'ERROR_MESSAGE' 	=> 'Let op! We hebben nog een paar dingen die niet in orde zijn!',
	'THANKS_MESSAGE' 	=> '<strong>Bedankt voor uw bericht!</strong><br/>We nemen zo spoedig mogelijk contact met u op.',
	'THANKS_NEWSLETTER_MESSAGE' => '<strong>Bedankt voor uw inschrijving!</strong><br/>Om uw account te activeren hebben wij een activatie mail gestuurd. na bevestiging is uw inschrijving actief.',
	'SUBMIT_ERROR' 		=> 'Op deze wijze kunnen geen gegevens verzonden worden.',
	'FILL_FIRSTNAME' 	=> 'Vul uw voornaam in.',
	'FILL_LASTNAME' 	=> 'Vul uw achternaam in.',
	'FILL_EMAIL' 		=> 'Vul uw e-mailadres in.',
	'FILL_PHONE' 		=> 'Vul uw telefoonnummer in.',
	'FILL_STREET' 		=> 'Vul uw straatnaam in.',
	'FILL_CITY' 		=> 'Vul uw woonplaats in.',
	'FILL_ZIPCODE' 		=> 'Vul uw postcode in.',
	'FILL_SUBJECT' 		=> 'Vul een onderwerp in.',
	'FILL_MESSAGE' 		=> 'Vul een bericht in.',
	'CHECK_EMAIL' 		=> 'Vul uw e-mail adres in het volgende formaat in: <strong>uwnaam@voorbeeld.nl</strong>?',
	'CHECK_NAME' 		=> 'Er staan vreemde tekens in uw naam',
	'CHECK_SUBJECT' 	=> 'Er staan vreemde tekens in uw onderwerp',
	'CHECK_SPAM' 		=> 'U heeft zojuist een bericht gestuurd. <br />Om spam te voorkomen dient u minimaal 1 minuut te wachten tot u weer een bericht kan zenden',	
	'CHECK_LAND_AND_ZIPCODE' => 'Controlleer uw land en postcode',
	'EMAIL_IN_USE' 		=> 'Dit e-mailadres is al ingebruik.',
	'CHECK_PASSWORDS' 	=> 'Controleer de wachtwoorden.',
	
	/* buttons */
	'SUBMIT' => 'Verzenden',
	
	/* geen komma meer */
	'LAST' => 'Laatste'
);

    return (!array_key_exists($phrase,$_L)) ? $phrase : $_L[$phrase];
}