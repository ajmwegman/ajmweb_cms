<?php
// delete.php - Verwerkingsbestand voor het verwijderen van blogposts
include '../src/blog.class.php';
$blog = new Blog();
$blog->deletePost($_GET['id']);
header("Location: ../index.php");
