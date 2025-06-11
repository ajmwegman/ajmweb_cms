<?php
require_once("src/banners.class.php"); 

if(!isset($_GET['id'])) { echo "oeps"; } else { $id = $_GET['id']; }

$banner = new banner($pdo);	

$row = $banner->getBanner("id", $id );

  	//foreach ( $result as $data => $row ) {	
		
		$id 		  = $row['id'];
		$hash 		  = $row['hash'];
		$subject	  = $row['subject'];
		$url          = $row['url'];
		$startdate	  = $row['startdate'];
		$enddate	  = $row['enddate'];
		$image     	  = $row['image'];
		$description  = $row['description'];
		$advertiser   = $row['advertiser'];

?>

<div class="row mt-4" id="add_space">
		
	<div class="col-md-12">
		<div class="float-end"><a href="/admin/banners/" class="btn btn-dark">Terug</a></div>
		<h2>Banner bewerken</h2>
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

<script src="/admin/modules/banners/js/imageupload.js"  type="text/javascript"></script>
