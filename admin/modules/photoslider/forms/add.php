<form action="/admin/modules/photoslider/bin/add.php" enctype="multipart/form-data" method="post" name="menuform" id="menuform" autocomplete="off">
  <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
  <input type="hidden" id="hash" name="hash" value="">
  <div class="row mt-4">
    <div class="col-md-12">
      <div class="card shadow">
        <div class="card-header"><h5>Afbeeldingen toevoegen</h5></div>
        <div class="card-body">
          <div class="row">
            <!-- Slider Select Dropdown met knop -->
            <div class="form-group mt-2 col-md-12 d-flex align-items-center">
              <div class="me-2 flex-grow-1">
                <label for="slider">Kies een slider</label>
                <select name="slider_id" id="slider" class="form-control" required>
                  <option value="">Selecteer een slider</option>
                  <?php foreach ($sliders as $slider): ?>
                    <option value="<?php echo $slider['id']; ?>"><?php echo htmlspecialchars($slider['name']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <!-- Knop om de modal te openen -->
              <button type="button" class="btn btn-info mt-4" data-bs-toggle="modal" data-bs-target="#sliderModal">
                +
              </button>
            </div>

            <div class="form-group mt-2 col-md-12">
              <label for="Onderwerp">Titel:</label>
              <input type="text" name="subject" class="form-control" id="menuLabel" aria-describedby="menuLabel" placeholder="Onderwerp" required>
            </div>
            <div class="form-group mt-2 col-md-12">
              <label for="category">Omschrijving:</label>
              <input type="text" name="category" class="form-control" id="category" placeholder="Categorie" required>
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

<!-- Modal voor het toevoegen van een nieuwe slider -->
<div class="modal fade" id="sliderModal" tabindex="-1" aria-labelledby="sliderModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sliderModalLabel">Nieuwe Slider Toevoegen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addSliderForm">
        <div class="modal-body">          <!-- Div voor weergave van meldingen -->
          <div id="display_addslider" class="mt-2"></div>
          <div class="mb-3">
            <label for="sliderName" class="form-label">Naam van de Slider</label>
            <input type="text" class="form-control" id="sliderName" name="slider_name" placeholder="Voer de naam van de slider in" required>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
          <button type="submit" class="btn btn-primary">Opslaan</button>
        </div>
      </form>
    </div>
  </div>
</div>
