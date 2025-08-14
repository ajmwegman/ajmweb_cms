<?php
require_once("src/products.class.php"); 

if(!isset($_GET['id'])) { echo "oeps"; } else { $id = $_GET['id']; }

$products = new products($pdo);	

// Debug informatie
echo "<!-- Debug: ID = {$id} -->";

$result = $products->getProductInfo( $id );

if (!$result || empty($result)) {
    echo '<div class="alert alert-danger">Product niet gevonden voor ID: ' . htmlspecialchars($id) . '</div>';
    echo '<a href="/admin/products/" class="btn btn-secondary">Terug naar overzicht</a>';
    exit;
}

foreach ( $result as $data => $row ) {	

$id 		   = $row['id'];
$productCode   = $row['productCode'];
$price         = $row['price'];
$hash 		   = $row['hash'];
$title	       = $row['title'];
$seoTitle	   = $row['seoTitle'];
$sort_num      = $row['sort_num'];
$image     	   = $row['image'];
$description   = $row['description'];
$category      = $row['category'];
$stock         = $row['stock'];
$btw         = $row['btw'];
$meta_title      = $row['meta_title'] ?? '';
$meta_description = $row['meta_description'] ?? '';
$og_title        = $row['og_title'] ?? '';
$og_description  = $row['og_description'] ?? '';
$og_image        = $row['og_image'] ?? '';
}

$img = $products->getImageName($id );
?>
<div id="menuList"></div>
<div class="row mt-4" id="add_space">
		
	<div class="col-md-12">
		<div class="float-end"><a href="/admin/products/" class="btn btn-dark">Terug</a></div>
		<h2>Product bewerken</h2>
	</div>
	
</div>

<div class="row mt-4">
		
	<div class="col-md-12">
		<?php include_once("forms/edit.php"); ?>
	</div>
	
</div>

<!-- Modal -->
<div class="modal fade" id="dialogModal" tabindex="-1" aria-labelledby="dialogModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="dialogModalLabel">Let op!</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-center dialogmessage"></p>
      </div>
      <div class="modal-footer">
        <input type="hidden" class="RowId" value="" id="RowId">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
        <button type="button" class="btn btn-danger image-delete btn-ok" data-bs-dismiss="modal">Verwijderen</button>
      </div>
    </div>
  </div>
</div>

<script src="/admin/modules/products/js/imageupload.js"  type="text/javascript"></script>
<script>
   //afbeeldingen sorteren   

    var grid = document.getElementById('imageContainer');
    Sortable.create(grid, {
        animation: 450,
        onUpdate: function(evt) {
            // Verzamelen van de nieuwe volgorde
            var order = [];
            var items = grid.querySelectorAll(".sortable-item");  // Verander .sortable-item naar de juiste klasse
            items.forEach(function(item, index){
                order.push(item.getAttribute("data-set")); // Haal het unieke id uit het data-set attribuut
            });

            // Versturen van de nieuwe volgorde via AJAX
            $.ajax({
                url: '/admin/modules/products/bin/image_sort.php', // jouw PHP-bestand
                type: 'POST',
                data: { order: order }, // Noteer de veranderde data structuur
                success: function(response) {
                    console.log("Succes: ", response);
                },
                error: function(error){
                    console.log("Fout: ", error);
                }
            });
        }
    });
</script>

