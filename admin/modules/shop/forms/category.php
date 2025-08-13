<?php
// Pad naar JSON bestand
$catFile = __DIR__ . '/../bin/categories.json';

// Bestaande categorieën laden
$categories = [];
if (file_exists($catFile)) {
    $json = file_get_contents($catFile);
    $categories = json_decode($json, true) ?: [];
}

// Nieuwe categorie opslaan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $id = time();
    $categories[$id] = [
        'name_nl' => trim($_POST['name_nl'] ?? ''),
        'name_en' => trim($_POST['name_en'] ?? ''),
    ];
    file_put_contents($catFile, json_encode($categories, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
?>
<div class="card mb-4">
  <div class="card-header"><h5>Categorie beheer</h5></div>
  <div class="card-body">
    <form method="post">
      <input type="hidden" name="add_category" value="1">
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Naam (NL)</label>
          <input type="text" name="name_nl" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Naam (EN)</label>
          <input type="text" name="name_en" class="form-control" required>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Categorie toevoegen</button>
    </form>
    <?php if (!empty($categories)): ?>
    <hr>
    <ul class="list-group">
      <?php foreach ($categories as $cat): ?>
        <li class="list-group-item">NL: <?php echo htmlspecialchars($cat['name_nl']); ?> - EN: <?php echo htmlspecialchars($cat['name_en']); ?></li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>
</div>
