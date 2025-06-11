<?php
echo "<h2>" . $menu->getGroupName( $group_id ) . "</h2>";

require_once("../admin/template/navtabs.php"); ?>

<h2>Sleutelwoord Beheer</h2>
<div class="row mt-4">
  <div class="col-md-12">
    <form action="/admin/modules/keywords/bin/add.php" enctype="multipart/form-data" method="post" name="menuform" id="menuform" autocomplete="off">
      <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
      <input type="hidden" id="hash" name="hash" value="">
      <div class="card shadow">
        <h5 class="card-header">Menu item toevoegen</h5>
        <div class="card-body">
			<div class="row">
          <div class="col-5 form-group mt-2">
            <label for="menuLabel">Sleutelwoord</label>
            <input type="text" name="keyword" class="form-control" id="menuLabel" aria-describedby="menuLabel" placeholder="Vul keyword in" required>
            <small>Voorbeeld: [KEYWORD]</small> 
		  </div>
          <div class="col-5 form-group mt-2">
            <label for="location">Vervanger</label>
            <input type="text" name="replacer" class="form-control" id="replacer" placeholder="replacer" required>
            <small>Voorbeeld: sleutel</small>
		  </div>
          <div class="col-2 form-group mt-4">
            <button type="submit" class="btn btn-dark mt-2" id="add_menu_item">Toevoegen</button>
          </div>
				</div>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="row mt-4">
  <div class="col-md-12">
    <div class="card shadow">
        <div class="card-header">
        	<div class="row">
		<div class="col-5"><h5>Sleutelwoord</h5></div>
		<div class="col-5"><h5>Vervanger</h5></div>
		<div class="col-2 text-end"><h5>Acties</h5></div>
	</div>
        </div>
        <div class="card-body">
      <div id="menulist">
        <?php require("../admin/modules/keywords/bin/summary.php"); ?>
      </div>
    </div>
    </div>
  </div>
</div>
<script src="/admin/modules/keywords/js/js.js"></script>