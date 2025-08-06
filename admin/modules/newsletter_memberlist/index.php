<h2>Nieuwbriefleden beheer</h2>
<?php require_once("forms/add.php"); ?>

<!-- Search Form -->
<div class="row mt-4">
  <div class="col-md-12">
    <div class="card shadow">
      <div class="card-header">
        <h5>Zoeken</h5>
      </div>
      <div class="card-body">
        <div class="input-group">
          <input type="text" class="form-control" id="searchInput" placeholder="Zoek op voornaam, achternaam of e-mailadres...">
          <button type="button" class="btn btn-primary" id="searchButton">
            <i class="bi bi-search"></i> Zoeken
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Results Section -->
<div class="row mt-4">
  <div class="col-md-12">
    <div class="card shadow">
      <div class="card-header">
        <div class="row">
          <div class="col-3">
            <h5>Voornaam</h5>
          </div>
          <div class="col-3">
            <h5>Achternaam</h5>
          </div>
          <div class="col-4">
            <h5>E-mailadres</h5>
          </div>
          <div class="col-1">
            <h5>Aan/uit</h5>
          </div>
          <div class="col-1 text-end">
            <h5>Acties</h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div id="menulist">
          <?php require("bin/summary.php"); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="/admin/modules/newsletter_memberlist/js/menulist.js"  type="text/javascript"></script>