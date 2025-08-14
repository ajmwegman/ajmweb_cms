<input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
<input type="hidden" id="hash" name="hash" value="">
<div class="row mt-4">
  <div class="col-md-12">
    <div class="card shadow">
      <div class="card-body">
        <ul class="nav nav-tabs" id="productTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">Algemeen</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab">SEO</button>
          </li>
        </ul>
        <div class="tab-content pt-3" id="productTabContent">
          <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
            <div class="row">
              <div class="mb-3">
                <label for="title" class="form-label">Titel</label>
                <?php echo input("text", 'title', $title, "title".$id, 'class="form-control autosave" data-field="title" data-set="'.$hash.'"'); ?>
              </div>
              <div class="mb-3">
                <label for="productCode" class="form-label">Productcode</label>
                <?php echo input("text", 'productCode', $productCode, "productCode".$id, 'class="form-control autosave" data-field="productCode" data-set="'.$hash.'"'); ?>
              </div>
              <div class="mb-3">
                <label for="category" class="form-label">Categorie</label>
                <?php echo input("text", 'category', $category, "category".$id, 'class="form-control autosave" data-field="category" data-set="'.$hash.'"'); ?>
              </div>
              <div class="mb-3">
                <div class="form-group mt-2">
                  <label for="description">Omschrijving</label>
                  <?php
                  $extra = 'class="form-control autosave summernote" data-field="description" data-set="' . $hash . '"';
                  echo textarea( 'description', $description, $extra );
                  ?>
                </div>
              </div>
            </div> <!-- /row -->

            <div class="container mt-2">
              <div id="cat_image" class="row">
                <?php require_once("../admin/modules/products/bin/cat_image.php"); ?>
              </div>

              <div class="row mb-3">
                <label for="image" class="mt-2">Afbeelding</label>
                <div class="card card-body text-center mt-2">
                  <a class="btn btn-primary mt-4" id="single_cat_upload" data-set="<?php echo $hash; ?>">Selecteer een afbeelding</a>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="mb-3 col-4">
                <label for="price" class="form-label">Prijs</label>
                <?php echo input("text", 'price', $price, "price".$id, 'class="form-control autosave" data-field="price" data-set="'.$hash.'"'); ?>
              </div>
              <div class="mb-3 col-4">
                <label for="btw" class="form-label">BTW</label>
                <div class="input-group">
                  <?php echo input("text", 'btw', $btw, "btw".$id, 'class="form-control autosave" data-field="btw" data-set="'.$hash.'"'); ?>
                  <div class="input-group-append"><span class="input-group-text">%</span></div>
                </div>
              </div>
              <div class="mb-3 col-4">
                <label for="stock" class="form-label" col-4>Voorraad</label>
                <?php echo input("text", 'stock', $stock, "stock".$id, 'class="form-control autosave" data-field="stock" data-set="'.$hash.'"'); ?>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
            <div class="mb-3">
              <label for="meta_title" class="form-label">Meta titel</label>
              <?php echo input("text", 'meta_title', $meta_title, "meta_title".$id, 'class="form-control autosave" data-field="meta_title" data-set="'.$hash.'"'); ?>
            </div>
            <div class="mb-3">
              <label for="meta_description" class="form-label">Meta omschrijving</label>
              <?php echo textarea('meta_description', $meta_description, 'class="form-control autosave" data-field="meta_description" data-set="'.$hash.'"'); ?>
            </div>
            <div class="mb-3">
              <label for="og_title" class="form-label">OG titel</label>
              <?php echo input("text", 'og_title', $og_title, "og_title".$id, 'class="form-control autosave" data-field="og_title" data-set="'.$hash.'"'); ?>
            </div>
            <div class="mb-3">
              <label for="og_description" class="form-label">OG omschrijving</label>
              <?php echo textarea('og_description', $og_description, 'class="form-control autosave" data-field="og_description" data-set="'.$hash.'"'); ?>
            </div>
            <div class="mb-3">
              <label for="og_image" class="form-label">OG afbeelding URL</label>
              <?php echo input("text", 'og_image', $og_image, "og_image".$id, 'class="form-control autosave" data-field="og_image" data-set="'.$hash.'"'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
