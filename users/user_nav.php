<section class="mt-5">
<div class="container mt-5">
    <div class="message"></div>
    <h3>Dashboard</h3>
    <?php
    if($userData === 'address_form_first') {
        require_once($_SERVER['DOCUMENT_ROOT']."/users/forms/address.php");
    } else {
    ?>
    <div class="row row-cols-1 row-cols-md-4 g-4 custom-card-container">

      <!-- Card 1 -->
      <div class="col">
        <a id="passwordCard" class="card text-center custom-card" data-bs-toggle="modal" data-bs-target="#passwordModal">
          <div class="card-body">
            <i class="bi bi-lock"></i>
            <h5 class="card-title">Wachtwoord aanpassen</h5>
          </div>
        </a>
      </div>

      <!-- Card 2 -->
      <div class="col">
        <a id="usernameCard" class="card text-center custom-card" data-bs-toggle="modal" data-bs-target="#usernameModal">
          <div class="card-body">
            <i class="bi bi-person bi-2x"></i>
            <h5 class="card-title">Username aanpassen</h5>
          </div>
        </a>
      </div>

      <!-- Card 4 -->
      <div class="col">
        <a href="page.php?action=orders" class="card text-center custom-card">
          <div class="card-body">
            <i class="bi bi-receipt"></i>
            <h5 class="card-title">Gewonnen veilingen</h5>
          </div>
        </a>
      </div>

      <!-- Card 6 -->
      <div class="col">
        <a href="page.php?action=address" class="card text-center custom-card">
          <div class="card-body">
            <i class="bi bi-book"></i>
            <h5 class="card-title">Adresboek</h5>
          </div>
        </a>
      </div>
    </div>
  </div>
    
    <div class="container">
        <div class="row mt-5">
            <div class="col">
                <h2>Mijn Biedingen</h2>
                <?php include("forms/bids.php"); ?>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col">
                <h2>Mijn Favorieten</h2>
                <?php include("forms/favorites.php"); ?>
            </div>
        </div>
    </div>
    <?php } ?>
    </section>

<!-- Modal 1 -->
<div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="passwordModalLabel">Wachtwoord Aanpassen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <p>Een nieuw wachtwoord moet aan de volgende eisen voldoen:
          <ul>
              <li>Minimaal 8 tekens (.{8,})</li>
              <li>Minstens 2 cijfers ((?=.*\d.*\d))</li>
              <li>Minstens 2 vreemde tekens ((?=.*[^\w\d\s])(?=.*[^\w\s]))</li>
         </ul>
          </p>       <br>

        <form id="password_reset_form">
          <div class="mb-3">
            <label for="new_password" class="form-label">Nieuw Wachtwoord</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
          </div>
          <div class="mb-3">
            <label for="confirm_password" class="form-label">Bevestig Wachtwoord</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
          </div>
          <div id="message_container"></div> <!-- Voeg deze div toe voor de meldingen -->
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Sluiten</button>
        <button type="button" class="btn btn-success" id="submit_password">Wachtwoord Aanpassen</button>
      </div>
    </div>
  </div>
</div>




<!-- Modal 2 -->
<div class="modal fade" id="usernameModal" tabindex="-1" role="dialog" aria-labelledby="usernameModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="usernameModalLabel">Gebruikersnaam Aanpassen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <p>Voer hieronder uw nieuwe gebruikersnaam in. Na het aanpassen wordt u automatisch uitgelogd.</p>
        <!-- Formulier voor het resetten van de gebruikersnaam -->
        <form id="username_reset_form">
          <div class="form-group">
            <label for="new_username">Nieuwe Gebruikersnaam</label>
            <input type="email" class="form-control mb-2" id="new_username" name="new_username" placeholder="Voer nieuwe gebruikersnaam in" required>
          </div>
          <!-- Voeg eventuele andere velden toe die je nodig hebt voor het resetten van de gebruikersnaam -->
        </form>
        <!-- Berichtencontainer voor feedback -->
        <div id="username_message_container"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Sluiten</button>
        <button type="button" class="btn btn-success" id="submit_username">Gebruikersnaam Resetten</button>
      </div>
    </div>
  </div>
</div>



