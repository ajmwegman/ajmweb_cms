<?php
@session_start();

/*
Array ( 
[group_id] => 0 
[hash] => VsChOKrTKlHo 
[startdate] => 2023-07-26 
[enddate] => 2023-07-30 
[startprice] => 5 
[minUp] => 1 
[commision] => 6% 
[shippingcost] => 4,95 )
*/
error_reporting(E_ALL ^ E_DEPRECATED);
ini_set("display_errors", 1); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

$path = $_SERVER['DOCUMENT_ROOT'];

require_once($path . "/system/database.php");
require_once($path . "/admin/src/database.class.php");
require_once($path . "/admin/functions/urlsafe.php");
require_once($path . "/admin/modules/auctions/src/auction.class.php");

$db = new database($pdo);
$auction = new auction($pdo);

# error globals
$thanks = "De gegevens zijn met succes opgeslagen.";
$errormessage = '<strong> Let op! We hebben nog een paar dingen die niet in orde zijn!</strong>';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Other form validation and processing code...

    $pass = 0;
    $alert = "";

    # error vars
    $empty_date = "Er moet een datum worden toegewezen.";
    $empty_time = "Er moet een tijd worden toegewezen.";
    $empty_hash = "Er moet een Hash worden opgegeven.";
    $empty_product = "Er moet een product worden opgegeven.";
    $empty_score = "is geen getal.";
    $error_dates = "Er moeten juiste data worden opgegeven.";
    $error_prodexist = ">ProductId bestaat al in de database.";

    // Get all form values
    $startdate = $_POST['startDate'];
    $enddate = $_POST['endDate'];
    $starttime = $_POST['startTime'];
    $endtime = $_POST['endTime'];

    // Fields related to the selected product
    $productId = $_POST['searchId'];
    $productName = $_POST['searchInput'];

    // Fields for pricing and costs
    $startprice = str_replace(',', '.', $_POST['startPrice']);
    $minUp = str_replace(',', '.', $_POST['minUp']);
    $commision = str_replace(',', '.', $_POST['commision']);
    $shippingcost = str_replace(',', '.', $_POST['shippingcost']);

    $hash = $_POST['hash'];

    // Check for empty fields and handle errors
    if ($auction->productIdExists($productId)) {
        $pass = 1;
        $alert .= "<li>".$error_prodexist."</li>";
    }
    
    if (empty($startdate)) {
        $pass = 1;
        $alert .= "<li>" . $empty_date . "</li>";
    }

    if (empty($enddate)) {
        $pass = 1;
        $alert .= "<li>" . $empty_date . "</li>";
    }

    if (empty($starttime)) {
        $pass = 1;
        $alert .= "<li>" . $empty_time . "</li>";
    }

    if (empty($endtime)) {
        $pass = 1;
        $alert .= "<li>" . $empty_time . "</li>";
    }

    if (empty($productId)) {
        $pass = 1;
        $alert .= "<li>" . $empty_product . "</li>";
    }

    if (!isset($startprice) || !filter_var($startprice, FILTER_VALIDATE_FLOAT)) {
        $pass = 1;
        $alert .= "<li>Startprijs " . $empty_score . "</li>";
    }

    if (!isset($minUp) || !filter_var($minUp, FILTER_VALIDATE_FLOAT)) {
        $pass = 1;
        $alert .= "<li>Minimumbedrag " . $empty_score . "</li>";
    }

    if (!isset($commision) || !filter_var($commision, FILTER_VALIDATE_FLOAT)) {
        $pass = 1;
        $alert .= "<li>Commissie " . $empty_score . "</li>";
    }

    if ($shippingcost === '' || !is_numeric($shippingcost)) {
        $pass = 1;
        $alert .= "<li>Verzendkosten " . $empty_score . "</li>";
    }

    if (empty($hash)) {
        $pass = 1;
        $alert .= "<li>" . $empty_hash . "</li>";
    }
    
    if ($startdate > $enddate) {
        $pass = 1;
        $alert .= "<li>" . $error_dates . "</li>";
    }

    if ($pass == 0) { 
        $values = array(
            'productId' => $productId,
            'startDate' => $startdate,
            'endDate' => $enddate,
            'startTime' => $starttime,
            'endTime' => $endtime,
            'StartPrice' => $startprice,
            'MinUp' => $minUp,
            'commision' => $commision,
            'Shippingcost' => $shippingcost,
            'hash' => $hash
        );
        $go = $db->insertdata("group_auctions", $values);
        
        if ($go == true) {
            echo '<div class="alert alert-success" role="alert">'.$productName.' is toegevoegd!</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Er is iets fout gegaan!</div>';
        }
    } else {
        //do nothing
        echo "<div class=\"alert alert-danger\" role=\"alert\">Er is iets fout gegaan! {$alert}</div>";
    }
}
?>
