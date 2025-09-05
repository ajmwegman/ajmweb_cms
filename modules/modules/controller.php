<?php
$config = Core::config();
$title = 'Modules';
$meta_description = $config['site_title'] ?? '';
$meta_keywords = '';
$site_location = $config['site_location'] ?? '/';
$theme = $config['theme'] ?? '';

$modulesDir = dirname(__DIR__);
$availableModules = array_filter(scandir($modulesDir), function($item) use ($modulesDir) {
    return $item[0] !== '.'
        && is_dir($modulesDir . '/' . $item)
        && file_exists($modulesDir . '/' . $item . '/controller.php')
        && !in_array($item, ['home', 'modules']);
});

require __DIR__ . '/view.php';
?>
