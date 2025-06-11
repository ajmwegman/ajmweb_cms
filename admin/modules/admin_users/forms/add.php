<form action="/admin/modules/admin_users/bin/add.php" enctype="multipart/form-data" method="post" name="menuform" id="menuform" autocomplete="off">
  <input type="hidden" id="hash" name="hash" value="">
  <div class="row mt-4">
    <div class="col-md-12">
      <div class="card shadow">
        <div class="card-header">
          <h5>Gebruikers toevoegen</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="form-group mt-2 col-md-6">
              <label for="menuLabel">Gebruikersnaam</label>
              <input type="text" autocomplete="off" name="username" class="form-control" id="menuLabel" aria-describedby="menuLabel" placeholder="Gebruikersnaam" required>
            </div>
            <div class="form-group mt-2 col-md-6">
              <label for="location">Wachtwoord</label>
              <input type="text" name="password" class="form-control" id="password" placeholder="Wachtwoord" required>
            </div>
          </div>
          <div class="row mt-2">
            <div class="form-group mt-2 col-md-4">
              <label for="location">Voornaam</label>
              <input type="text" name="firstname" class="form-control" id="firstname" placeholder="Voornaam" required>
            </div>
            <div class="form-group mt-2 col-md-4">
              <label for="location">Achternaam</label>
              <input type="text" name="surname" class="form-control" id="surname" placeholder="Achternaam" required>
            </div>
            <div class="form-group mt-2 col-md-4">
              <label for="location">E-mailadres</label>
              <input type="email" name="email" class="form-control" id="email" placeholder="E-mailadres" required>
            </div>
          </div>
            <!--
          <div class="row mt-2">
            <div class="form-group mt-2 col-md-6">
              <label for="location">Gebruikers rechten</label>
              user_level radio 
            </div>
            <div class="form-group mt-2 col-md-6">
              <label for="location">Activeren</label>
              status radio 
            </div>
          </div>
            -->
          <div class="row text-center">
            <div class="form-group mt-4">
              <button type="submit" class="btn btn-dark" id="add_menu_item">Toevoegen</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
