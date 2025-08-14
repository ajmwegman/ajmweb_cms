<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . '/system/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $description = $_POST['description'] ?? '';
    $active = isset($_POST['active']) ? 1 : 0;
    $variant = $_POST['variant'] ?? null;
    $image_url = $_POST['image_url'] ?? null;

    if ($id) {
        $stmt = $pdo->prepare("UPDATE products SET name=?, price=?, category_id=?, description=?, active=? WHERE id=?");
        $stmt->execute([$name, $price, $category_id, $description, $active, $id]);
        $pdo->prepare("DELETE FROM product_images WHERE product_id=?")->execute([$id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO products (name, price, category_id, description, active) VALUES (?,?,?,?,?)");
        $stmt->execute([$name, $price, $category_id, $description, $active]);
        $id = $pdo->lastInsertId();
    }

    if ($image_url) {
        $stmt = $pdo->prepare("INSERT INTO product_images (product_id, image_url, variant) VALUES (?,?,?)");
        $stmt->execute([$id, $image_url, $variant]);
    }

    header('Location: ?module=products&action=list');
    exit;
}

$product = ['name' => '', 'price' => '', 'category_id' => '', 'description' => '', 'active' => 1, 'variant' => '', 'image_url' => ''];
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    $img = $pdo->prepare("SELECT * FROM product_images WHERE product_id=? ORDER BY sort_order ASC LIMIT 1");
    $img->execute([$id]);
    $imgRow = $img->fetch(PDO::FETCH_ASSOC);
    if ($imgRow) {
        $product['variant'] = $imgRow['variant'];
        $product['image_url'] = $imgRow['image_url'];
    }
}

$categories = $pdo->query("SELECT id, name FROM product_categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>
<h2><?= $id ? 'Product bewerken' : 'Nieuw product' ?></h2>
<form method="post">
  <div class="mb-3">
    <label class="form-label">Naam</label>
    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Prijs</label>
    <input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars($product['price']) ?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Categorie</label>
    <select name="category_id" class="form-select">
      <option value="">--</option>
      <?php foreach ($categories as $c): ?>
      <option value="<?= $c['id'] ?>" <?= $c['id'] == $product['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Beschrijving</label>
    <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
  </div>
  <div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="active" id="active" <?= $product['active'] ? 'checked' : '' ?>>
    <label class="form-check-label" for="active">Actief</label>
  </div>
  <hr>
  <h5>Variant</h5>
  <div class="mb-3">
    <label class="form-label">Variant naam</label>
    <input type="text" name="variant" class="form-control" value="<?= htmlspecialchars($product['variant']) ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Afbeelding URL</label>
    <input type="text" name="image_url" class="form-control" value="<?= htmlspecialchars($product['image_url']) ?>">
  </div>
  <button type="submit" class="btn btn-primary">Opslaan</button>
  <a href="?module=products&action=list" class="btn btn-secondary">Annuleren</a>
</form>
