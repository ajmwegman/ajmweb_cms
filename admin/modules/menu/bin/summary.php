<?php
$path = $_SERVER['DOCUMENT_ROOT'];

require_once( $path."/admin/src/menulist.class.php" );
require_once( $path."/admin/functions/forms.php" );

$db = new database( $pdo );
$menu = new menu( $pdo );

if ( isset( $_SESSION[ 'group_id' ] ) ) {

  $group_id = $_SESSION[ 'group_id' ];
  $list = $menu->getMenuItems( $group_id );
//echo '<pre>';
//    print_r($list);
?>


<div id="menuList" class="list-group">

	<?php
  	foreach ( $list as $row => $link ) {
?>
      <div class="row g-2 pb-2 mt-1" id="row<?php echo $link[ 'id' ]; ?>" data-id="<?php echo $link[ 'id' ]; ?>" style="background-color: #F1F1F1;">
		  <div class="col-5">	
			<?php echo input("text", 'title[]', $link['title'], "title".$link['id'], 'class="form-control autosave" data-field="title" data-set="'.$link['hash'].'"'); ?>
		</div>
		  <div class="col-5">	
			<?php echo input("text", 'location[]', $link['location'], "location".$link['id'], 'class="form-control autosave" data-field="location" data-set="'.$link['hash'].'"'); ?>
		</div>
		  <div class="col-2 text-end">	
            <div class="form-check-inline form-switch">
                <input class="form-check-input switchbox mt-2" type="checkbox" data-set="<?php echo $link['hash']; ?>" <?php echo ($link['status'] == 'y') ? 'checked' : ''; ?>>
            </div>
		  <button value="<?php echo $link['id']; ?>" 
			 data-message="Weet je zeker dat je <?php echo $link['title']; ?> wilt verwijderen?" 
			 id="btn<?php echo $link['id']; ?>"
			 class="btn btn-danger btn-ok" 
			 data-bs-toggle="modal" 
			 data-bs-target="#dialogModal">
			  <i class="bi bi-trash" data-set="<?php echo $link['id']; ?>"></i>
		  </button>
			  <i class="bi bi-grip-vertical drag-handler"></i>
		</div>
	  <?php echo input("hidden", $link[ 'hash' ], $link[ 'hash' ]); ?>
	  <?php echo input("hidden", 'sortnum[]', $link['sortnum'], $link['hash']); ?>

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
        <button type="button" class="btn btn-danger btn-delete btn-ok" data-bs-dismiss="modal">Verwijderen</button>
      </div>
    </div>
  </div>
</div>
<?php 
} 
?>