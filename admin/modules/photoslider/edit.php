<?php
require_once("src/photoslider.class.php"); 

if(!isset($_GET['id'])) { echo "oeps"; } else { $id = $_GET['id']; }

$photoslider = new photoslider($pdo);	

// Debug informatie
echo "<!-- Debug: ID = {$id} -->";

$row = $photoslider->getImage("id", $id );

if (!$row) {
    echo '<div class="alert alert-danger">Photoslider item niet gevonden voor ID: ' . htmlspecialchars($id) . '</div>';
    echo '<a href="/admin/photoslider/" class="btn btn-secondary">Terug naar overzicht</a>';
    exit;
}

//foreach ( $result as $data => $row ) {	

$id 		  = $row['id'];
$hash 		  = $row['hash'];
$subject	  = $row['subject'];
$sortnum      = $row['sortnum'];
$image     	  = $row['image'];
$category     = $row['category'];

?>
<div id="menuList"></div>
<div class="row mt-4" id="add_space">
		
	<div class="col-md-12">
		<div class="float-end"><a href="/admin/photoslider/" class="btn btn-dark">Terug</a></div>
		<h2>Photoslider bewerken</h2>
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
<button type="button" class="btn btn-danger image-delete btn-ok" 
    data-bs-dismiss="modal" 
    data-message="Weet je zeker dat je deze afbeelding wilt verwijderen?" 
    data-set="<?php echo $row['hash']; ?>"
    id="btn<?php echo $row['id']; ?>">
    Verwijderen
</button>      </div>
    </div>
  </div>
</div>

<script src="/admin/modules/photoslider/js/imageupload.js"  type="text/javascript"></script>
