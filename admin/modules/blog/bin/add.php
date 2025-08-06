<?php
// add.php - Verwerkingsbestand voor het toevoegen van blogposts
include '../src/blog.class.php';
$blog = new Blog();
$blog->addPost($_POST['title'], $_POST['content'], $_POST['status'], $_POST['publication_date']);
header("Location: ../index.php");
?>