<form action="/admin/modules/banners/bin/add.php" enctype="multipart/form-data" method="post" name="menuform" id="menuform" autocomplete="off">
  <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
  <input type="hidden" id="hash" name="hash" value="">
  <div class="row mt-4">
    <div class="col-md-12">
      <div class="card shadow">
        <div class="card-header"><h5>Banners toevoegen</h5></div>
        <div class="card-body">
          <div class="row">
            <div class="form-group mt-2 col-md-4">
              <label for="Onderwerp">Naam</label>
              <input type="text" name="subject" class="form-control" id="menuLabel" aria-describedby="menuLabel" placeholder="Onderwerp" required>
            </div>
            <div class="form-group mt-2 col-md-4">
              <label for="advertiser">Adverteerder</label>
              <input type="text" name="advertiser" class="form-control" id="advertiser" placeholder="Adverteerder" required>
            </div>
            <div class="form-group mt-2 col-md-4">
              <label for="url">Doellocatie</label>
              <input type="text" name="url" class="form-control" id="url" placeholder="Doellocatie" required>
            </div>
            <div class="form-group mt-2 col-md-6">
              <label for="startdate">Startdatum</label>
              <input type="date" name="startdate" class="form-control" id="startdate" placeholder="<?php echo date("Y-m-d"); ?>" required>
            </div>
            <div class="form-group mt-2 col-md-6">
              <label for="location">Einddatum</label>
              <input type="date" name="enddate" class="form-control" id="enddate" placeholder="<?php echo date("Y-m-d"); ?>" required>
            </div>
          </div>
          <div class="row text-center">
            <div class="form-group mt-2">
              <button type="submit" class="btn btn-dark mt-2" id="add_menu_item">Toevoegen</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>