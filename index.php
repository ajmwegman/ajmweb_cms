<?php
session_start();

require_once __DIR__ . '/src/core.php';
$config = require __DIR__ . '/config/app.php';
Core::init($config);

$module = isset($_GET['module']) ? preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['module']) : 'home';
$controller = __DIR__ . '/modules/' . $module . '/controller.php';

if (file_exists($controller)) {
    require $controller;
} else {
    http_response_code(404);
    echo 'Module not found';
}
?>
