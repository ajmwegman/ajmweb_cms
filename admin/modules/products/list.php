<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . '/system/database.php';

$sql = "SELECT p.id, p.name, p.price, p.active, c.name AS category
        FROM products p
        LEFT JOIN product_categories c ON p.category_id = c.id
        ORDER BY p.id DESC";
$products = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Producten</h2>
<a href="?module=products&action=edit" class="btn btn-primary mb-3">Nieuw product</a>
<table class="table table-striped">
  <thead>
    <tr><th>ID</th><th>Naam</th><th>Categorie</th><th>Prijs</th><th>Actief</th><th>Acties</th></tr>
  </thead>
  <tbody>
  <?php foreach ($products as $p): ?>
    <tr>
      <td><?= htmlspecialchars($p['id']) ?></td>
      <td><?= htmlspecialchars($p['name']) ?></td>
      <td><?= htmlspecialchars($p['category']) ?></td>
      <td>&euro;<?= number_format($p['price'], 2, ',', '.') ?></td>
      <td><?= $p['active'] ? 'Ja' : 'Nee' ?></td>
      <td>
        <a href="?module=products&action=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-primary">Bewerk</a>
        <a href="?module=products&action=delete&id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Verwijder product?');">Verwijder</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
