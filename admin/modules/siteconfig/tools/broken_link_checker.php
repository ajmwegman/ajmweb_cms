<?php
header('Content-Type: application/json');
$url = $_POST['url'] ?? '';
$status = '';
if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($ch);
    if (curl_errno($ch)) {
        $status = 'error';
    } else {
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    }
    curl_close($ch);
}
if ($status === '') {
    $status = 'invalid';
}
echo json_encode(['status' => $status]);
