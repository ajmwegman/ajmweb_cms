<?php
// autosave.php - Verwerkingsbestand voor autosave functionaliteit
include '../src/blog.class.php';
$blog = new Blog();
$blog->autosave($_POST['id'], $_POST['field'], $_POST['value']);
