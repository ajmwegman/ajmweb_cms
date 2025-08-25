<?php
@session_start();

$path = $_SERVER['DOCUMENT_ROOT'];

require_once( $path."/system/database.php" );

if ( isset( $_SESSION[ 'group_id' ] ) ) {

  $group_id = $_SESSION[ 'group_id' ];
  
  // Simple direct query using the existing $pdo connection
  $sql = "SELECT * FROM group_menu WHERE group_id = ? ORDER BY sortnum ASC";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$group_id]);
  $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
//echo '<pre>';
//    print_r($list);
?>


<div id="menuList" class="list-group">

	<?php
  	foreach ( $list as $row => $link ) {
?>
      <div class="row g-2 pb-2 mt-1" id="row<?php echo $link[ 'id' ]; ?>" data-id="<?php echo $link[ 'id' ]; ?>" style="background-color: #F1F1F1;">
		  <div class="col-5">	
			<input type="text" name="title[]" value="<?php echo htmlspecialchars($link['title']); ?>" id="title<?php echo $link['id']; ?>" class="form-control autosave" data-field="title" data-set="<?php echo $link['hash']; ?>">
		</div>
		  <div class="col-5">	
			<input type="text" name="location[]" value="<?php echo htmlspecialchars($link['location']); ?>" id="location<?php echo $link['id']; ?>" class="form-control autosave" data-field="location" data-set="<?php echo $link['hash']; ?>">
		</div>
		  <div class="col-2 text-end">	
            <div class="form-check-inline form-switch">
                <input class="form-check-input switchbox mt-2" type="checkbox" data-set="<?php echo $link['hash']; ?>" <?php echo ($link['status'] == 'y') ? 'checked' : ''; ?>>
            </div>
		  <button value="<?php echo $link['id']; ?>" 
			 data-hash="<?php echo $link['hash']; ?>"
			 data-message="Weet je zeker dat je '<?php echo htmlspecialchars($link['title']); ?>' wilt verwijderen?" 
			 id="btn<?php echo $link['id']; ?>"
			 class="btn btn-danger btn-delete" 
			 data-bs-toggle="modal" 
			 data-bs-target="#dialogModal">
			  <i class="bi bi-trash"></i>
		  </button>
			  <i class="bi bi-grip-vertical drag-handler"></i>
		</div>
	  <input type="hidden" name="<?php echo $link['hash']; ?>" value="<?php echo $link['hash']; ?>">
	  <input type="hidden" name="sortnum[]" value="<?php echo $link['sortnum']; ?>" id="<?php echo $link['hash']; ?>">

	</div>
    

<?php } ?>
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
        <button type="button" class="btn btn-danger btn-delete">Verwijderen</button>
      </div>
    </div>
  </div>
</div>
<?php 
} else {
  echo '<div class="alert alert-warning">Geen groep ID gevonden in sessie.</div>';
}
?>