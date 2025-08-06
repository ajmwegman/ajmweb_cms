<?php
// index.php - Overzicht van blogposts met zoek- en filtermogelijkheden

include 'src/blog.class.php';
$blog = new Blog();
$posts = $blog->getAllPosts(); // Ophalen van alle blogposts

?>
<h1>Blog Overzicht</h1>
<table>
    <tr><th>Titel</th><th>Status</th><th>Publicatiedatum</th><th>Acties</th></tr>
    <?php foreach ($posts as $post): ?>
        <tr>
            <td><?= $post['title'] ?></td>
            <td><?= $post['status'] ?></td>
            <td><?= $post['publication_date'] ?></td>
            <td>
                <a href="edit.php?id=<?= $post['post_id'] ?>">Bewerken</a> | 
                <a href="bin/edit.php?id=<?= $post['post_id'] ?>" onclick="return confirm('Weet je zeker dat je deze post wilt verwijderen?');">Verwijderen</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<a href="edit.php">Nieuwe blogpost toevoegen</a>
