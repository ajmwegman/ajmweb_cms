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
require_once("../../functions/csrf.php");

$db    = new database($pdo);
$site  = new site($pdo);
$user  = new users($pdo);

$info     = $site->getWebsiteInfo($group_id);

print_r($_SESSION);

// Controleer of de hash in de sessie aanwezig is (gebruiker ingelogd)
if (isset($_SESSION['session_hash'])) {
    $hash = $_SESSION['session_hash'];
    $user_id = $user->getUserIdByHash($hash);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
            $response = array(
                "status" => "error",
                "message" => "Ongeldige CSRF-token."
            );
            echo json_encode($response);
            exit;
        }
        // Ontvang de product-ID en de favorietenstatus (aan/uit) via POST
        $product_id = $_POST['product_id'];
        $favorite_status = $_POST['favorite_status'];

        // Controleer of er al een record bestaat voor dit product en gebruiker
        $stmt = $pdo->prepare("SELECT * FROM group_products_favorites WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $existing_record = $stmt->fetch();

        if ($favorite_status === 'on') {
            // Voeg het product toe aan favorieten
            if (!$existing_record) {
                $stmt = $pdo->prepare("INSERT INTO group_products_favorites (user_id, product_id) VALUES (?, ?)");
                if ($stmt->execute([$user_id, $product_id])) {
                    // Geef een succesbericht als JSON terug
                    $response = array(
                        "status" => "success",
                        "message" => "Product is aan favorieten toegevoegd."
                    );
                    echo json_encode($response);
                } else {
                    // Geef een foutbericht als JSON terug
                    $response = array(
                        "status" => "error",
                        "message" => "Fout bij het toevoegen aan favorieten."
                    );
                    echo json_encode($response);
                }
            }
        } elseif ($favorite_status === 'off' && $existing_record) {
            // Verwijder het product uit favorieten als het record bestaat
            $stmt = $pdo->prepare("DELETE FROM group_products_favorites WHERE user_id = ? AND product_id = ?");
            if ($stmt->execute([$user_id, $product_id])) {
                // Geef een succesbericht als JSON terug
                $response = array(
                    "status" => "success",
                    "message" => "Product is verwijderd uit favorieten."
                );
                echo json_encode($response);
            } else {
                // Geef een foutbericht als JSON terug
                $response = array(
                    "status" => "error",
                    "message" => "Fout bij het verwijderen uit favorieten."
                );
                echo json_encode($response);
            }
        }
    }
} else {
    // Geef een foutbericht als JSON terug omdat de gebruiker niet is ingelogd
    $response = array(
        "status" => "error",
        "message" => "Je moet ingelogd zijn om favorieten toe te voegen of te verwijderen."
    );
    echo json_encode($response);
}
?>
