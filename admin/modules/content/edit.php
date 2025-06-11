<?php
require_once("src/content.class.php"); 
require_once("../admin/src/menulist.class.php"); 

if(!isset($_GET['id'])) { echo "oeps"; } else { $id = $_GET['id']; }

if ($module == 'content' && isset($_GET['action']) == 'edit') {

$content = new content($pdo);	
$menu 	 = new menu($pdo);	

$MenuLocations = $menu->getMenuLocations( $group_id );
$MenuNames = $menu->getMenuNames( $group_id );

$result = $content->getContent( $id );

  	foreach ( $result as $data => $row ) {	
		
		$id 		= $row['id'];
		$hash 		= $row['hash'];
		$group_id 	= $row['group_id'];
		$title 		= $row['title'];
		$content	= $row['content'];
		$location 	= $row['location'];
		$seo_url 	= $row['seo_url'];
		$keywords 	= $row['keywords'];
		$sortnum 	= $row['sortnum'];
		$status 	= $row['status'];

		$selectbox = selectbox("Kies menu item", 'location', $location, $MenuLocations, $MenuNames, 'class="form-select autosave" data-field="location" data-set="'.$hash.'"');
    }
//echo $title;
?>

<div class="row mt-4" id="add_space">
		
	<div class="col-md-12">
		<div class="float-end"><a href="/admin/content/" class="btn btn-dark">Terug</a></div>
		<h2>Content bewerken</h2>
	</div>
	
</div>

<div class="row mt-4" id="add_space">
		
	<div class="col-md-12">
		<?php include_once("forms/edit.php"); ?>
	</div>
	
</div>

<div class="row" id="edit_space">
		<div class="col-md-12" id="form_holder"></div>
</div>

<script src="/admin/modules/content/js/edit.js"></script>
<?php } ?>