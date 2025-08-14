<?php
require_once __DIR__ . '/../../summernote.php';
$cwd = getcwd();
chdir(__DIR__ . '/../content');
require_once 'edit.php';
chdir($cwd);
?>
