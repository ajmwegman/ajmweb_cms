<button type="button" class="pink-button" data-bs-toggle="modal" data-bs-target="#modal">+</button>
<form action="/admin/modules/menu/bin/add.php" enctype="multipart/form-data" method="post" name="menuform" id="menuform" autocomplete="off">
  <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
  <input type="hidden" id="hash" name="hash" value="">
  <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Menu item toevoegen</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="menuLabel">Naam</label>
            <input type="text" name="title" class="form-control" id="menuLabel" aria-describedby="menuLabel" placeholder="Vul menu naam in" required>
          </div>
          <div class="form-group mt-4">
            <label for="location">Locatie</label>
            <input type="text" name="location" class="form-control" id="location" placeholder="locatie">
            <small>Als er niet naar een specifieke doelpagina wordt verwezen leeg laten.</small> </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-danger" data-bs-target="#modal" data-bs-toggle="modal" data-bs-dismiss="modal" >Annuleren</button>
          <button type="submit" data-bs-target="#modal" data-bs-toggle="modal" data-bs-dismiss="modal" class="btn btn-dark" id="add_menu_item">Toevoegen</button>
        </div>
      </div>
    </div>
  </div>
</form>
