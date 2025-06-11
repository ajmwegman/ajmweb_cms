<?php
$path = $_SERVER[ 'DOCUMENT_ROOT' ];

require_once( $path . "/admin/modules/admin_users/src/admin_users.class.php" );
require_once( $path . "/admin/functions/forms.php" );

$db = new database( $pdo );
$users = new adminUsers( $pdo );

$list = $users->getAllAdminUsers();
?>

<div id="menuList" class="list-group">
  <?php
  foreach ( $list as $row => $link ) {

      echo input("hidden", $link[ 'hash' ], $link[ 'hash' ]); ?>

  <div class="row mt-1" style="background-color: #F1F1F1;">

      <div class="col-2">
    <h5><?php echo $link['username']; ?></h5>
  </div>
  <div class="col-2">
    <h5><?php echo $link['firstname']; ?></h5>
  </div>
  <div class="col-3">
    <h5><?php echo $link['surname']; ?></h5>
  </div>
  <div class="col-3">
    <h5><?php echo $link['email']; ?></h5>
  </div>
      
    <div class="col-lg-1 text-center">
         <div class="form-check-inline form-switch mt-2">
        <input class="form-check-input switchbox" type="checkbox" data-set="<?php echo $link['hash']; ?>" <?php echo ($link['status'] == 'y') ? 'checked' : ''; ?>>
      </div>
      </div>
        <div class="col-lg-1 text-end">
        <a href="/admin/admin_users/edit/<?php echo $link['id']; ?>/" class="btn btn-dark btn-sm edit-btn mt-1"><i class="bi bi-pencil"></i></a>
      <button value="<?php echo $link['id']; ?>" 
			 data-message="Weet je zeker dat je <?php echo $link['username']; ?> wilt verwijderen?" 
			 id="btn<?php echo $link['id']; ?>"
			 class="btn btn-danger btn-sm btn-ok mt-1" 
			 data-bs-toggle="modal" 
			 data-bs-target="#dialogModal"> <i class="bi bi-trash" data-set="<?php echo $link['id']; ?>"></i> 
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
