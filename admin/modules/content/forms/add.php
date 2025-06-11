<button type="button" class="pink-button" data-bs-toggle="modal" data-bs-target="#modal">+</button>
<form action="/admin/modules/content/bin/add.php" enctype="multipart/form-data" method="post" name="menuform" id="menuform" autocomplete="off">
  <input type="hidden" id="content" name="content" value="Binnenkort meer...">
  <input type="hidden" name="seo_url" class="form-control" id="seo_url" placeholder="seo url">
  <input type="hidden" name="keywords" class="form-control" id="keywords" placeholder="Zoekwoorden">
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
          <div class="form-group mb-4">
            <label for="menuLabel">Titel</label>
            <input type="text" name="title" class="form-control" id="menuLabel" aria-describedby="menuLabel" placeholder="Vul menu naam in" required>
          </div>
          <?php echo $selectbox; ?> </div>
        <div class="modal-footer">
          <button class="btn btn-danger" data-bs-target="#modal" data-bs-toggle="modal" data-bs-dismiss="modal" >Annuleren</button>
          <button type="submit" data-bs-target="#modal" data-bs-toggle="modal" data-bs-dismiss="modal" class="btn btn-dark" id="add_menu_item">Toevoegen</button>
        </div>
      </div>
    </div>
  </div>
</form>