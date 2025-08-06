<?php
@session_start();

error_reporting(E_ALL ^ E_DEPRECATED);
ini_set("display_errors", 1);

$module = "photoslider";
$path = $_SERVER['DOCUMENT_ROOT'];

require_once($path."/system/database.php");
require_once($path."/admin/src/database.class.php");

$db = new database($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $pass = 0;
  $alert = "";
  $sortnum = 1; // De nieuwe entry moet altijd sortnum 1 krijgen.

  // Error globals
  $thanks = "De gegevens zijn met succes opgeslagen.";
  $errormessage = '<strong> Let op! We hebben nog een paar dingen die niet in orde zijn!</strong>';

  // Error vars
  $empty_subject = "Er moet een titel worden toegewezen.";
  $empty_category = "Er moet een adverteerder worden toegewezen.";
  $empty_hash = "Er moet een Hash worden opgegeven.";
  $empty_slider_id = "Er moet een Slider worden opgegeven.";

  if (empty($_POST['subject'])) {
    $pass = 1;
    $alert .= "<li>" . $empty_subject . "</li>";
  } else {
    $subject = $_POST['subject'];
  }

  if (empty($_POST['category'])) {
    $pass = 1;
    $alert .= "<li>" . $empty_category . "</li>";
  } else {
    $category = $_POST['category'];
  }

  if (empty($_POST['hash'])) {
    $pass = 1;
    $alert .= "<li>" . $empty_hash . "</li>";
  } else {
    $hash = $_POST['hash'];
  }

  if (empty($_POST['slider_id'])) {
    $pass = 1;
    $alert .= "<li>" . $empty_slider_id . "</li>";
  } else {
    $slider_id = $_POST['slider_id'];
  }

  if ($pass == 0) {
    // Stap 1: Verhoog alle sortnum waarden met 1 voor de gegeven slider_id
    $db->execute("UPDATE group_photoslider SET sortnum = sortnum + 1 WHERE slider_id = ?", array($slider_id));

    // Stap 2: Voeg de nieuwe record in met sortnum 1
    $values = array(
      'slider_id' => $slider_id,
      'hash' => $hash,
      'subject' => $subject,
      'category' => $category,
      'sortnum' => $sortnum,
      'modified' => date("Y-m-d h:i:s"),
      'active' => 'n'
    );

    $go = $db->insertdata("group_photoslider", $values);

    if ($go == true) {
      echo '<div class="alert alert-success" role="alert">' . $subject . ' is toegevoegd!</div>';
    } else {
      echo '<div class="alert alert-danger" role="alert">Er is iets fout gegaan!</div>';
    }
  } else {
    // Error boodschap tonen
    echo "<div class=\"alert alert-danger\" role=\"alert\">Er is iets fout gegaan! {$alert}</div>";
  }
}
?>
