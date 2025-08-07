<?php
// Verbinding maken met de database (vereist een db-verbinding)
include("../../system/database.php");

require_once("../src/analytics.class.php");

try {
    $analytics = new Analytics($pdo);

    // Ontvang start- en einddatum van de AJAX-aanvraag
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!$data) {
        throw new Exception("No JSON data received");
    }
    
    $startDate = $data['startDate'] ?? date('Y-m-01');
    $endDate = $data['endDate'] ?? date('Y-m-d');

    // Validate dates
    if (!$startDate || !$endDate) {
        throw new Exception("Start and end dates are required");
    }

    if ($startDate > $endDate) {
        throw new Exception("Start date must be before end date");
    }

    // Roep de getVisitorCountsByDay()-functie aan om gegevens op te halen
    $result = $analytics->getVisitorCountsByDay($startDate, $endDate);

    // Check if there's an error in the result
    if (isset($result['error'])) {
        throw new Exception($result['error']);
    }

    // Geef de resultaten terug als JSON
    header('Content-Type: application/json');
    echo json_encode($result);
    
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'error' => $e->getMessage(),
        'dates' => [],
        'counts' => []
    ]);
}
?>