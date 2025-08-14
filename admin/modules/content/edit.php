<?php
require_once("src/content.class.php"); 
require_once("../admin/src/menulist.class.php"); 

// Get ID from URL rewriting or GET parameters
$id = $_GET['id'] ?? 0;
$id = (int)$id;

if(!$id) { 
    echo '<div class="alert alert-danger">Geen geldig ID opgegeven!</div>';
    echo '<a href="/admin/content/" class="btn btn-secondary">Terug naar overzicht</a>';
    exit();
} else { 
    echo "<!-- Debug: ID = $id -->";
}

if ($module == 'content' && (isset($_GET['action']) && $_GET['action'] == 'edit' || isset($_GET['page']) && $_GET['page'] == 'edit')) {

$content = new content($pdo);	
$menu 	 = new menu($pdo);	

$MenuLocations = $menu->getMenuLocations( $group_id );
$MenuNames = $menu->getMenuNames( $group_id );

$result = $content->getContent( $id );

if ($result && is_array($result)) {
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

                $meta_title     = $row['meta_title'] ?? '';
                $meta_description = $row['meta_description'] ?? '';
                $og_title       = $row['og_title'] ?? '';
                $og_description = $row['og_description'] ?? '';
                $og_image       = $row['og_image'] ?? '';


		$selectbox = selectbox("Kies menu item", 'location', $location, array_combine($MenuLocations, $MenuNames), 'class="form-select autosave" data-field="location" data-set="'.$hash.'"');
    }
} else {
    echo '<div class="alert alert-danger">Content item niet gevonden!</div>';
    echo '<a href="/admin/content/" class="btn btn-secondary">Terug naar overzicht</a>';
    exit();
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