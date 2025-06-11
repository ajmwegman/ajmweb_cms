<?php
$path = $_SERVER['DOCUMENT_ROOT'];

require_once( $path."/admin/modules/keywords/src/keywords.class.php" );
require_once( $path."/admin/functions/forms.php" );

$db = new database( $pdo );
$keywords = new keywords( $pdo );

if ( isset( $_SESSION[ 'group_id' ] ) ) {

  $group_id = $_SESSION[ 'group_id' ];
  $list = $keywords->getKeywordsList( $group_id );
?>
<div id="menuList" class="list-group">

	<?php
  	foreach ( $list as $row => $link ) {
	?>
  <div class="row g-2 pb-2 mt-1" id="row<?php echo $link[ 'id' ]; ?>" data-id="<?php echo $link[ 'id' ]; ?>" style="background-color: #F1F1F1;">
	  <?php echo input("hidden", $link[ 'hash' ], $link[ 'hash' ]); ?>
	  
		  <div class="col-5">	
			<?php echo input("text", 'keyword', htmlspecialchars($link['keyword']), "keyword".$link['id'], 'class="form-control autosave" data-field="keyword" data-set="'.$link['hash'].'"'); ?>
		</div>
		  <div class="col-5">	
			<?php echo input("text", 'replacer', htmlspecialchars($link['replacer']), "replacer".$link['id'], 'class="form-control autosave" data-field="replacer" data-set="'.$link['hash'].'"'); ?>
		</div>
		  <div class="col-2 text-end">	
		
		<button value="<?php echo $link['id']; ?>" 
			 data-message="Weet je zeker dat je <?php echo $link['keyword']; ?> wilt verwijderen?" 
			 id="btn<?php echo $link['id']; ?>"
			 class="btn btn-danger btn-ok" 
			 data-bs-toggle="modal" 
			 data-bs-target="#dialogModal">
			  <i class="bi bi-trash" data-set="<?php echo $link['id']; ?>"></i>
		  </button>
		</div>

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