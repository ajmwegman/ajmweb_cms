<?php
// edit.php - Formulierpagina voor het bewerken/toevoegen van blogposts

include 'src/blog.class.php';
$blog = new Blog();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $status = $_POST['status'];
    $publication_date = $_POST['publication_date'];
    $blog->savePost($title, $content, $status, $publication_date);
    header("Location: index.php");
}

$post = isset($_GET['id']) ? $blog->getPostById($_GET['id']) : null;
?>
<form method="post">
    <label>Titel: <input type="text" name="title" value="<?= $post['title'] ?? '' ?>"></label><br>
    <label>Inhoud: <textarea name="content"><?= $post['content'] ?? '' ?></textarea></label><br>
    <label>Status: 
        <select name="status">
            <option value="concept" <?= ($post['status'] ?? '') === 'concept' ? 'selected' : '' ?>>Concept</option>
            <option value="gepubliceerd" <?= ($post['status'] ?? '') === 'gepubliceerd' ? 'selected' : '' ?>>Gepubliceerd</option>
        </select>
    </label><br>
    <label>Publicatiedatum: <input type="date" name="publication_date" value="<?= $post['publication_date'] ?? '' ?>"></label><br>
    <button type="submit">Opslaan</button>
</form>
