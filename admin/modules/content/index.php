<?php
require_once("src/content.class.php"); 
require_once("../admin/src/menulist.class.php"); 

$content = new content($pdo);	
$menu = new menu($pdo);	

$MenuLocations = $menu->getMenuLocations( $group_id );
$MenuNames = $menu->getMenuNames( $group_id );

echo "<h2>" . $menu->getGroupName( $group_id ) . "</h2>";

require_once("../admin/template/navtabs.php");

if ($module == 'content' && isset($_GET['action']) == 'edit') { ?>

<?php require_once("edit.php"); ?>

<?php } else { 

$selectbox = selectbox("Kies menu item", 'location', '', $MenuLocations, $MenuNames, 'class=form-select');
?>
<h2 class="mt-4">Content Beheer</h2>

<div class="row mt-4" id="add_space">

		<div class="card shadow">
            <div class="card-header">
            <div class="row">
  <div class="col-8"><h5>Titel</h5></div>
  <div class="col-2"><h5>Aan/Uit</h5></div>
  <div class="col-2 text-end"><h5>Acties</h5></div>
</div>
            </div>
		<div class="card-body">
            <div id="menulist">
                <?php require("../admin/modules/content/bin/summary.php"); ?>
            </div>
		</div>
		</div>
</div>

<!-- Button trigger modal -->
<?php include("forms/add.php"); ?>        

    

<?php } ?>

<script src="/admin/modules/content/js/js.js"></script>