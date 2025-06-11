<?php
session_start();
$sessid = session_id();

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once("../../system/database.php");

require_once("../../src/database.class.php");
require_once("../../src/users.class.php");

$users = new users($pdo); // Maak een instantie van de users class

$userid = filter_var($_POST['userid'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$company_name = filter_var($_POST['company_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$kvk_number = filter_var($_POST['kvk_number'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$vat_number = filter_var($_POST['vat_number'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$phone_number = filter_var($_POST['phone_number'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$street = filter_var($_POST['street'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$postal_code = filter_var($_POST['postal_code'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$city = filter_var($_POST['city'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$country = filter_var($_POST['country'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);


if ($userid !== false && $phone_number && $street && $postal_code && $city && $country) {
    // Gegevens zijn geldig, voer de update uit
    $result = $users->insertOrUpdateUserData($userid, $company_name, $kvk_number, $vat_number, $phone_number, $street, $postal_code, $city, $country);
    echo htmlspecialchars($result, ENT_QUOTES, 'UTF-8'); // Ontsnap gegevens voor weergave
} else {
    // Ongeldige gegevens ingediend, toon een foutmelding
    echo "Ongeldige invoer.";
}
?>