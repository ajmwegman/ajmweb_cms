<form action="/admin/modules/reviews/bin/add.php" enctype="multipart/form-data" method="post" name="menuform" id="menuform" autocomplete="off">
  <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
  <input type="hidden" id="hash" name="hash" value="">
  <div class="row mt-4">
    <div class="col-md-12">
      <div class="card shadow">
        <div class="card-header"><h5>Reviews toevoegen</h5></div>
        <div class="card-body">
          <div class="row">
            <div class="form-group mt-2 col-md-5">
              <label for="menuLabel">Onderwerp</label>
              <input type="text" name="subject" class="form-control" id="menuLabel" aria-describedby="menuLabel" placeholder="Onderwerp" required>
            </div>
            <div class="form-group mt-2 col-md-3">
              <label for="location">Plaats</label>
              <input type="text" name="location" class="form-control" id="location" placeholder="Locatie" required>
            </div>
            <div class="form-group mt-2 col-md-3">
              <label for="location">Datum</label>
              <input type="date" name="reviewdate" class="form-control" id="reviewdate" placeholder="Datum" required>
            </div>
            <div class="form-group mt-2 col-md-1">
              <label for="location">Cijfer</label>
              <input type="text" name="score" class="form-control" id="score" placeholder="Cijfer" required>
            </div>
          </div>
          <div class="row">
            <div class="form-group mt-2 col-md-6">
              <label for="location">Omschrijving</label>
              <textarea name="description" class="form-control summernote" id="description" placeholder="Omschrijving" rows="6"></textarea>
            </div>
            <div class="form-group mt-2 col-md-6">
              <label for="location">Reactie</label>
              <textarea name="reaction" class="form-control summernote" id="reaction" placeholder="Reactie" rows="4"></textarea>
            </div>
          </div>
          <div class="row text-center">
            <div class="form-group mt-2">
              <button type="submit" class="btn btn-dark" id="add_menu_item">Toevoegen</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>