<?php
$path = $_SERVER[ 'DOCUMENT_ROOT' ];

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 ); //Array ( [order] => Array ( [0] => item3 [1] => item2 [2] => item1 ) )

if(!isset($db)) { 
    
    require_once( $path."/system/database.php" );
    require_once( $path."/admin/src/database.class.php" );

    $db = new database($pdo); 
} else {
    //echo "db";
}

require_once( $path . "/admin/modules/products/src/products.class.php" );
require_once( $path . "/admin/functions/forms.php" );

$db = new database( $pdo );
$products = new products( $pdo );

$list = $products->getAllProducts();

$location = "/product_images/";
?>

<div id="menuList" class="list-group">
  <?php
  foreach ( $list as $row => $link ) {
    
      echo input("hidden", $link[ 'hash' ], $link[ 'hash' ]); 
      
    $imageName = $products->getFirstImage( $link[ 'hash' ] );

  ?>
  <div class="row g-2 pb-2 mt-1" id="row<?php echo $link[ 'id' ]; ?>" data-id="<?php echo $link[ 'id' ]; ?>" style="background-color: #F1F1F1;">
      
    <div class="col-lg-1 text-center">
 <?php       
        if ($imageName !== false) {
            echo '<img src="' . $location . $imageName['image'] .'" alt=""  class="img-fluid" style="max-height: 40px;">'; 
  } ?>
    </div>
    
    <div class="col-lg-4">
        <?php echo input("text", 'title', $link['title'], "title".$link['id'], 'class="form-control autosave" data-field="title" data-set="'.$link['hash'].'"'); ?>
    </div>
       
    <div class="col-lg-4">
        <?php echo input("text", 'category', $link['category'], "category".$link['id'], 'class="form-control autosave" data-field="category" data-set="'.$link['hash'].'"'); ?>
    </div>
      
      
    <div class="col-lg-1 text-center">
        <div class="form-check-inline form-switch mt-2">
            <input name"active" class="form-check-input switchbox" type="checkbox" data-set="<?php echo $link['hash']; ?>" <?php echo ($link['active'] == 'y') ? 'checked' : ''; ?>>
        </div>
    </div>
      
        <div class="col-lg-2 text-end">
        <a href="/admin/products/edit/<?php echo $link['id']; ?>/" class="btn btn-dark btn-sm edit-btn mt-1"><i class="bi bi-pencil"></i></a>
      <button value="<?php echo $link['id']; ?>" 
			 data-message="Weet je zeker dat je <?php echo $link['title']; ?> wilt verwijderen?" 
			 id="btn<?php echo $link['id']; ?>"
			 class="btn btn-danger btn-sm btn-ok mt-1" 
			 data-bs-toggle="modal" 
			 data-bs-target="#dialogModal"> <i class="bi bi-trash" data-set="<?php echo $link['id']; ?>"></i> 
      </button>
            <i class="bi bi-grip-vertical drag-handler"></i>
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

