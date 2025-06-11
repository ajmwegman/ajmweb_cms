<?php
if ($module == 'gallery' && isset($_GET['action']) == 'edit') { ?>

<?php require_once("edit.php"); ?>

<?php } else { ?>

<h2>Gallerij beheer</h2>
<?php require_once("forms/add.php"); ?>
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
              <div class="col-1"><h5>Aan/uit</h5></div>
              <div class="col-1 text-end"><h5>Acties</h5></div>
            </div>
        </div>
      
      <div class="card-body">
      <div id="menulist">
        <?php include("bin/summary.php"); ?>
      </div>
    </div>
  </div>
</div>
<? } ?>

<script src="/admin/modules/gallery/js/menulist.js"  type="text/javascript"></script>