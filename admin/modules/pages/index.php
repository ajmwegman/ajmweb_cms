<?php
// Wrapper module for page management reusing content module functionality.
$cwd = getcwd();
chdir(__DIR__ . '/../content');
require_once 'index.php';
chdir($cwd);
?>
