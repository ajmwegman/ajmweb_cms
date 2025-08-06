<?php
// Debug informatie
echo "<!-- Debug: Module = {$module}, Action = {$action}, ID = {$id} -->";

if ( $module == 'carousel' && $action == 'edit' ) {
?>
<?php require_once("edit.php"); ?>
<?php } else { ?>
	<div class="col-md-12">
		<div class="float-end">
        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="bi bi-gear"></i></button>
        </div>
		<h2>Carousel beheer</h2>
	</div>

<?php require_once("forms/add.php"); ?>
<?php require_once("forms/settings.php"); ?>
<div class="row mt-4">
<div class="col-md-12">
  <div class="card shadow">
    <div class="card-header">
      <div class="row">
        <div class="col-1"></div>
        <div class="col-4">
          <h5>Naam</h5>
        </div>
        <div class="col-4">
          <h5>Categorie</h5>
        </div>
        <div class="col-1">
          <h5>Locatie</h5>
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
        <?php include("bin/summary.php"); ?>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<script src="/admin/modules/carousel/js/menulist.js"  type="text/javascript"></script>
    