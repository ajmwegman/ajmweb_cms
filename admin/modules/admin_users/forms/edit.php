  <div class="row mt-4">
    <div class="col-md-12">
      <div class="card shadow">
        <div class="card-body">
          <div class="row">
            <div class="form-group mt-2 col-md-6">
              <label for="username">Gebruikersnaam</label>
<?php echo input("text", 'username', $username, "username".$id, 'class="form-control autosave" data-field="username" data-set="'.$hash.'"'); ?>
            </div>
            <div class="form-group mt-2 col-md-6">
              <label for="password">Wachtwoord</label>
<?php echo input("password", 'password', '', "password".$id, 'class="form-control autosave" data-field="password" data-set="'.$hash.'"'); ?>
            </div>
            </div>
                        <div class="row">

              
            <div class="form-group mt-2 col-md-4">
              <label for="firstname">Voornaam</label>
<?php echo input("text", 'firstname', $firstname, "firstname".$id, 'class="form-control autosave" data-field="firstname" data-set="'.$hash.'"'); ?>
            </div>
            <div class="form-group mt-2 col-md-4">
              <label for="surname">Achternaam</label>
<?php echo input("text", 'surname', $surname, "surname".$id, 'class="form-control autosave" data-field="surname" data-set="'.$hash.'"'); ?>
            </div>
            
            <div class="form-group mt-2 col-md-4">
                <label for="email">E-mailadres</label>
                <?php echo input("email", 'email', $email, "email".$id, 'class="form-control autosave" data-field="email" data-set="'.$hash.'"'); ?>
            </div>
              
          </div>
          <div class="row">
        <!--
            <div class="form-group mt-2 col-md-6">
              <label for="reaction">user_level</label>
              </div>
              <div class="form-group mt-2 col-md-6">
              <label for="reaction">Status</label>
              </div>
        -->
          </div>
        </div>
      </div>
    </div>
  </div>