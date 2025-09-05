<?php
require_once __DIR__ . '/../../src/site.class.php';

$config = Core::config();
$title = $config['site_title'] ?? 'Home';
$meta_description = $config['site_title'] ?? '';
$meta_keywords = '';
$site_location = $config['site_location'] ?? '/';
$theme = $config['theme'] ?? '';

// Session identifier
$sessid = session_id();

// Set up site data
$pdo = Core::db()->getPdo();
$site = new site($pdo);

$domain = str_replace('www.', '', $_SERVER['HTTP_HOST']);
$configRow = $site->getConfig($domain);
$web_naam = $configRow['web_naam'] ?? '';
$groupData = $site->getGroupId($web_naam);
$group_id = $groupData['group_id'] ?? 0;

$menu = $site->getActiveMenuItems($group_id);
if (!is_array($menu)) {
    $menu = [];
}

$sections = $site->getActiveContent($group_id);
if (!is_array($sections)) {
    $sections = [];
}

$info = $site->getWebsiteInfo($group_id);

require __DIR__ . '/view.php';
?>
