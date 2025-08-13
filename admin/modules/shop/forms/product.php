<?php
// Bestanden
$catFile = __DIR__ . '/../bin/categories.json';
$prodFile = __DIR__ . '/../bin/products.json';

// Categorieën laden
$categories = [];
if (file_exists($catFile)) {
    $categories = json_decode(file_get_contents($catFile), true) ?: [];
}

// Bestaande producten laden
$products = [];
if (file_exists($prodFile)) {
    $products = json_decode(file_get_contents($prodFile), true) ?: [];
}

// Product opslaan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $id = time();
    // Staffelprijzen opbouwen
    $tier_prices = [];
    if (!empty($_POST['tier_qty'])) {
        foreach ($_POST['tier_qty'] as $i => $qty) {
            $qty = (int)$qty;
            $price = isset($_POST['tier_price'][$i]) ? (float)$_POST['tier_price'][$i] : 0;
            if ($qty > 0) {
                $tier_prices[$qty] = $price;
            }
        }
    }
    // Account prijzen opbouwen
    $account_prices = [];
    if (!empty($_POST['account_id'])) {
        foreach ($_POST['account_id'] as $i => $acc) {
            $acc = trim($acc);
            $price = isset($_POST['account_price'][$i]) ? (float)$_POST['account_price'][$i] : 0;
            if ($acc !== '') {
                $account_prices[$acc] = $price;
            }
        }
    }

    $products[$id] = [
        'name_nl' => trim($_POST['prod_name_nl'] ?? ''),
        'name_en' => trim($_POST['prod_name_en'] ?? ''),
        'category' => $_POST['category'] ?? '',
        'vat' => (float)($_POST['vat'] ?? 0),
        'base_price' => (float)($_POST['base_price'] ?? 0),
        'tier_prices' => $tier_prices,
        'account_prices' => $account_prices,
    ];
    file_put_contents($prodFile, json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
?>
<div class="card mb-4">
  <div class="card-header"><h5>Product beheer</h5></div>
  <div class="card-body">
    <form method="post">
      <input type="hidden" name="add_product" value="1">
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Naam (NL)</label>
          <input type="text" name="prod_name_nl" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Naam (EN)</label>
          <input type="text" name="prod_name_en" class="form-control" required>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-md-4">
          <label class="form-label">Categorie</label>
          <select name="category" class="form-select" required>
            <option value="">-- kies --</option>
            <?php foreach ($categories as $cid => $cat): ?>
              <option value="<?php echo $cid; ?>"><?php echo htmlspecialchars($cat['name_nl']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">BTW (%)</label>
          <input type="number" step="0.01" name="vat" class="form-control" value="21">
        </div>
        <div class="col-md-4">
          <label class="form-label">Basisprijs</label>
          <input type="number" step="0.01" name="base_price" class="form-control" required>
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Staffelprijzen</label>
        <div id="tier-wrapper">
          <div class="row mb-2">
            <div class="col-md-6"><input type="number" name="tier_qty[]" class="form-control" placeholder="Aantal"></div>
            <div class="col-md-6"><input type="number" step="0.01" name="tier_price[]" class="form-control" placeholder="Prijs"></div>
          </div>
        </div>
        <button type="button" class="btn btn-sm btn-secondary" id="add-tier">+</button>
      </div>
      <div class="mb-3">
        <label class="form-label">Account prijzen</label>
        <div id="account-wrapper">
          <div class="row mb-2">
            <div class="col-md-6"><input type="text" name="account_id[]" class="form-control" placeholder="Account ID"></div>
            <div class="col-md-6"><input type="number" step="0.01" name="account_price[]" class="form-control" placeholder="Prijs"></div>
          </div>
        </div>
        <button type="button" class="btn btn-sm btn-secondary" id="add-account">+</button>
      </div>
      <button type="submit" class="btn btn-primary">Product toevoegen</button>
    </form>
    <?php if (!empty($products)): ?>
    <hr>
    <ul class="list-group">
      <?php foreach ($products as $prod): ?>
        <li class="list-group-item">
          <?php echo htmlspecialchars($prod['name_nl']); ?> (<?php echo htmlspecialchars($categories[$prod['category']]['name_nl'] ?? ''); ?>) - €<?php echo number_format($prod['base_price'],2,',','.'); ?> excl. BTW
        </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>
</div>
