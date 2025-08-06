<?php
// Debug informatie
echo "<!-- Debug: Module = {$module}, Action = {$action}, ID = {$id} -->";

if ($module == 'banners' && $action == 'edit') { ?>

<?php require_once("edit.php"); ?>

<?php } else { ?>

<h2>Banner beheer</h2>
<?php require_once("forms/add.php"); ?>
<div class="row mt-4">
  <div class="col-md-12">
    <div class="card shadow">
    <div class="card-header">
        <div class="row">
  <div class="col-3">
    <h5>Kenmerk</h5>
  </div>
<div class="col-2">
    <h5>Start</h5>
  </div>
  <div class="col-2">
      <h5>Eind</h5>
  </div>
  <div class="col-2">
    <h5>Adverteerder</h5>
  </div>
  <div class="col-1">
    <h5>Locatie</h5>
  </div>
  <div class="col-1"><h5>Aan/uit</h5></div>
  <div class="col-1 text-end"><h5>Acties</h5></div>
</div>
        </div>
      
      <div class="card-body">
      <div id="menulist">
        <?php require("../admin/modules/banners/bin/summary.php"); ?>
      </div>
    </div>
  </div>
</div>
<?php } ?>

<script src="/admin/modules/banners/js/menulist.js"  type="text/javascript"></script>