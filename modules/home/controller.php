<?php
$config = Core::config();
$title = $config['site_title'] ?? 'Home';
$meta_description = $config['site_title'] ?? '';
$meta_keywords = '';
$site_location = $config['site_location'] ?? '/';
$theme = $config['theme'] ?? '';
require __DIR__ . '/view.php';
?>
