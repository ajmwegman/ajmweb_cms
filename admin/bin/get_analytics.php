<?php
// Verbinding maken met de database (vereist een db-verbinding)
include("../../system/database.php");

require_once("../src/database.class.php");
require_once("../src/analytics.class.php");

$db = new database($pdo);
$analytics = new analytics($pdo);

// Ontvang start- en einddatum van de AJAX-aanvraag
$data = json_decode(file_get_contents("php://input"), true);
$startDate = $data['startDate'];
$endDate = $data['endDate'];

// Roep de getVisitorCountsByDay()-functie aan om gegevens op te halen
$result = $analytics->getVisitorCountsByDay($startDate, $endDate);

// Geef de resultaten terug als JSON
header('Content-Type: application/json');
echo json_encode($result);
?>