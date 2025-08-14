<button type="button" class="pink-button" data-bs-toggle="modal" data-bs-target="#modal">+</button>
<form action="/admin/modules/content/bin/add.php" enctype="multipart/form-data" method="post" name="menuform" id="menuform" autocomplete="off">
  <input type="hidden" id="content" name="content" value="Binnenkort meer...">
  <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
  <input type="hidden" id="hash" name="hash" value="">
  <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Content toevoegen</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <ul class="nav nav-tabs" id="addContentTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="add-content-general-tab" data-bs-toggle="tab" data-bs-target="#add-content-general" type="button" role="tab">Algemeen</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="add-content-seo-tab" data-bs-toggle="tab" data-bs-target="#add-content-seo" type="button" role="tab">SEO</button>
            </li>
          </ul>
          <div class="tab-content pt-3" id="addContentTabContent">
            <div class="tab-pane fade show active" id="add-content-general" role="tabpanel" aria-labelledby="add-content-general-tab">
              <div class="form-group mb-4">
                <label for="menuLabel">Titel</label>
                <input type="text" name="title" class="form-control" id="menuLabel" aria-describedby="menuLabel" placeholder="Vul menu naam in" required>
              </div>
              <?php echo $selectbox; ?>
            </div>
            <div class="tab-pane fade" id="add-content-seo" role="tabpanel" aria-labelledby="add-content-seo-tab">
              <div class="form-group mt-2">
                <label for="seo_url">SEO Url</label>
                <input type="text" name="seo_url" class="form-control" id="seo_url" placeholder="seo url">
              </div>
              <div class="form-group mt-2">
                <label for="keywords">Zoekwoorden</label>
                <input type="text" name="keywords" class="form-control" id="keywords" placeholder="Zoekwoorden">
              </div>
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
