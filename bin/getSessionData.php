<?php
declare(strict_types=1);
session_start();

$response = [
    'sessionId' => session_id(),
    'sessionStartTime' => $_SESSION['session_start_time']
];

header('Content-Type: application/json');
echo json_encode($response);
?>
