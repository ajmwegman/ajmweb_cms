<?php
// edit.php - Verwerkingsbestand voor het bewerken van blogposts
include '../src/blog.class.php';
$blog = new Blog();
$blog->updatePost($_POST['id'], $_POST['title'], $_POST['content'], $_POST['status'], $_POST['publication_date']);
header("Location: ../index.php");
?>