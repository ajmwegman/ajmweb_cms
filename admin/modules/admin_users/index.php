<?php
if ($module == 'admin_users' && isset($_GET['action']) == 'edit') { ?>

<?php require_once("edit.php"); ?>

<?php } else { ?>

<h2>Gebruiker beheer</h2>
<?php require_once("forms/add.php"); ?>
<div class="row mt-4">
  <div class="col-md-12">
    <div class="card shadow">
    <div class="card-header">
        <div class="row">
  <div class="col-2">
    <h5>Gebruikersnaam</h5>
  </div>
  <div class="col-2">
    <h5>Voornaam</h5>
  </div>
  <div class="col-3">
    <h5>Achternaam</h5>
  </div>
  <div class="col-3">
    <h5>E-mailadres</h5>
  </div>
  <div class="col-1"><h5>Aan/uit</h5></div>
  <div class="col-1 text-end"><h5>Acties</h5></div>
</div>
        </div>
      
      <div class="card-body">
      <div id="menulist">
        <?php require("bin/summary.php"); ?>
      </div>
    </div>
  </div>
</div>
<? } ?>

<script src="/admin/modules/admin_users/js/menulist.js"  type="text/javascript"></script>