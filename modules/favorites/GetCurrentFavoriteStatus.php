<?php
session_start();
$sessid = session_id();

error_reporting(E_ALL);
ini_set("display_errors", 1);

$group_id = 1;

include("../../system/database.php");

require_once("../../src/database.class.php");
require_once("../../src/site.class.php");
require_once("../../src/users.class.php");

$db    = new database($pdo);
$site  = new site($pdo);
$user  = new users($pdo);

$info     = $site->getWebsiteInfo($group_id);

// Controleer of de hash in de sessie aanwezig is (gebruiker ingelogd)
if (isset($_SESSION['session_hash'])) {
    $hash = $_SESSION['session_hash'];
    $user_id = $user->getUserIdByHash($hash);
    
    // Ontvang het product_id uit de POST-data
    $product_id = $_POST['product_id'];

  // Controleer de favorietenstatus in de database
  $stmt = $pdo->prepare("SELECT COUNT(*) AS isFavorite FROM group_products_favorites WHERE user_id = ? AND product_id = ?");
  $stmt->execute([$user_id, $product_id]);
  $result = $stmt->fetch();

  // Bepaal of het product in favorieten staat (1 als wel, 0 als niet)
  $isFavorite = $result['isFavorite'];

  // Retourneer de status als JSON
  $response = array(
      "status" => "success",
      "isFavorite" => $isFavorite
  );
} else {
    $response = array(
      "status" => "error",
      "isFavorite" => "Je moet ingelogt zijn."
  );
    
}

header('Content-Type: application/json');
echo json_encode($response);
?>