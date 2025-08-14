<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . '/system/database.php';

// Fetch categories
$categories = $pdo->query("SELECT id, name FROM product_categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// Fetch products with first image/variant
$sql = "SELECT p.id, p.name, p.price, c.name AS category, i.image_url, i.variant
        FROM products p
        LEFT JOIN product_categories c ON p.category_id = c.id
        LEFT JOIN product_images i ON p.id = i.product_id AND i.sort_order = 0
        WHERE p.active = 1";
$products = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container py-5">
  <div class="row">
    <aside class="col-md-3 mb-4">
      <h4 class="mb-3">Zoek</h4>
      <input type="text" id="search" class="form-control mb-4" placeholder="Zoek...">
      <h5>Categorieën</h5>
      <?php foreach ($categories as $cat): ?>
      <div class="form-check">
        <input class="form-check-input category-filter" type="checkbox" value="<?= htmlspecialchars($cat['name']) ?>" id="cat<?= $cat['id'] ?>">
        <label class="form-check-label" for="cat<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></label>
      </div>
      <?php endforeach; ?>
    </aside>
    <div class="col-md-9">
      <div class="row" id="product-list">
        <?php foreach ($products as $p): ?>
        <div class="col-md-6 mb-4 product-card" data-category="<?= htmlspecialchars($p['category']) ?>">
          <div class="card h-100">
            <img src="<?= htmlspecialchars($p['image_url'] ?: 'https://via.placeholder.com/300x200') ?>" class="card-img-top" alt="<?= htmlspecialchars($p['name']) ?>">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($p['name']) ?></h5>
              <p class="card-text mb-4">&euro;<?= number_format($p['price'], 2, ',', '.') ?></p>
              <button class="btn btn-primary btn-cart btn-interactive w-100 mb-2" data-id="<?= $p['id'] ?>" data-name="<?= htmlspecialchars($p['name']) ?>" data-price="<?= $p['price'] ?>" data-image="<?= htmlspecialchars($p['image_url']) ?>" data-variant="<?= htmlspecialchars($p['variant']) ?>">In winkelmand</button>
              <button class="btn btn-outline-secondary btn-fav btn-interactive w-100" data-id="<?= $p['id'] ?>">Favoriet</button>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
