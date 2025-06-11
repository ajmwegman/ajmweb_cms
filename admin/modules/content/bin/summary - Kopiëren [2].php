<?php
if ( isset( $_SESSION[ 'group_id' ] ) ) {

  $group_id = $_SESSION[ 'group_id' ];
  $list = $content->getContentItems( $group_id );
  ?>

<div id="menuList" class="list-group">
  <?php
  foreach ( $list as $row => $data ) {

    $link = "{$_SERVER['PHP_SELF']}?loc=content&id={$data['hash']}";
    ?>
  <div class="row p-1 mt-1" id="row<?php echo $data[ 'id' ]; ?>" data-id="<?php echo $data[ 'id' ]; ?>" style="background-color: #F1F1F1;">
    <div class="col-md-7 p-2"> <?php echo $data['title']; ?> </div>
    <div class="col-md-1 p-2"> 
      
      <div class="btn-group" role="group" aria-label="Basic example">
  
          <a href="/admin/content/edit/<?php echo $data['id']; ?>/" class="btn btn-dark edit-btn"><i class="bi bi-pencil"></i></a>
          
<button value="<?php echo $data['id']; ?>" 
                 data-message="Weet je zeker dat je <?php echo $data['title']; ?> wilt verwijderen?" 
                 id="btn<?php echo $data['id']; ?>"
                 class="btn btn-danger btn-ok" 
                 data-bs-toggle="modal" 
                 data-bs-target="#dialogModal"> <i class="bi bi-trash" data-set="<?php echo $data['id']; ?>"></i>
</button>
  
      <div class="form-check-inline form-switch mt-2 ml-2">
        <input class="form-check-input switchbox" type="checkbox" data-set="<?php echo $data['hash']; ?>" <?php echo ($data['status'] == 'y') ? 'checked' : ''; ?>>
      </div>
          
       <!--   <button type="button" class="btn btn-secondary">Right</button> -->
          
</div>
        
      </div>
    <div class="col-md-1 text-end"> <a href="/admin/content/edit/<?php echo $data['id']; ?>/" class="btn btn-dark edit-btn"><i class="bi bi-pencil"></i></a> </div>
    <div class="col-md-1 text-end">
      <button value="<?php echo $data['id']; ?>" 
                 data-message="Weet je zeker dat je <?php echo $data['title']; ?> wilt verwijderen?" 
                 id="btn<?php echo $data['id']; ?>"
                 class="btn btn-danger btn-ok" 
                 data-bs-toggle="modal" 
                 data-bs-target="#dialogModal"> <i class="bi bi-trash" data-set="<?php echo $data['id']; ?>"></i></button>
    </div>
    <div class="col-md-1 text-end"> <i class="bi bi-grip-vertical drag-handler"></i> </div>
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
