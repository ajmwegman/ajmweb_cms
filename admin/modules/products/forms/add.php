<button type="button" class="pink-button" data-bs-toggle="modal" data-bs-target="#modal">+</button>

<form action="/admin/modules/products/bin/add.php" enctype="multipart/form-data" method="post" name="menuform" id="menuform" autocomplete="off">

<input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
<input type="hidden" id="hash" name="hash" value="">

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Product toevoegen</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <ul class="nav nav-tabs" id="addProductTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="add-general-tab" data-bs-toggle="tab" data-bs-target="#add-general" type="button" role="tab">Algemeen</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="add-seo-tab" data-bs-toggle="tab" data-bs-target="#add-seo" type="button" role="tab">SEO</button>
            </li>
          </ul>
          <div class="tab-content pt-3" id="addProductTabContent">
            <div class="tab-pane fade show active" id="add-general" role="tabpanel" aria-labelledby="add-general-tab">
              <div class="row">
                <div class="form-group mt-2 col-md-6">
                  <label for="title">Productnaam</label>
                  <input type="text" name="title" class="form-control" id="menuLabel" aria-describedby="menuLabel" placeholder="Productnaam" required>
                </div>
                <div class="form-group mt-2 col-md-6">
                  <label for="category">Categorie</label>
                  <input type="text" name="category" class="form-control" id="category" placeholder="Categorie" required>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="add-seo" role="tabpanel" aria-labelledby="add-seo-tab">
              <div class="form-group mt-2">
                <label for="meta_title">Meta titel</label>
                <input type="text" name="meta_title" class="form-control" id="meta_title">
              </div>
              <div class="form-group mt-2">
                <label for="meta_description">Meta omschrijving</label>
                <textarea name="meta_description" class="form-control" id="meta_description"></textarea>
              </div>
              <div class="form-group mt-2">
                <label for="og_title">OG titel</label>
                <input type="text" name="og_title" class="form-control" id="og_title">
              </div>
              <div class="form-group mt-2">
                <label for="og_description">OG omschrijving</label>
                <textarea name="og_description" class="form-control" id="og_description"></textarea>
              </div>
              <div class="form-group mt-2">
                <label for="og_image">OG afbeelding URL</label>
                <input type="text" name="og_image" class="form-control" id="og_image">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-danger" data-bs-target="#modal" data-bs-toggle="modal" data-bs-dismiss="modal" >Annuleren</button>
          <button type="submit" data-bs-target="#modal" data-bs-toggle="modal" data-bs-dismiss="modal" class="btn btn-dark" id="add_menu_item">Toevoegen</button>
        </div>
      </div>
    </div>
  </div>

</form>
