<?php
if ($module == 'auctions' && isset($_GET['action']) == 'edit') { ?>


<?php require_once("edit.php"); ?>

<?php } else { ?>

<h2>Veiling beheer</h2>
<?php require_once("forms/add.php"); ?>
<div class="row mt-4">
  <div class="col-md-12">
    <div class="card shadow">
    <div class="card-header">
        <div class="row">
  <div class="col-5">
    <h5>Product</h5>
  </div>
  <div class="col-2">
    <h5>Start</h5>
  </div>
  <div class="col-2">
    <h5>Eind</h5>
  </div>
  <div class="col-1">
    <h5>Bod</h5>
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

<script src="/admin/modules/auctions/js/menulist.js"  type="text/javascript"></script>
<script src="/admin/modules/auctions/js/search.js"  type="text/javascript"></script>