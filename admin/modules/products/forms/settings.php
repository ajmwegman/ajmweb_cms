<?php
$path = $_SERVER[ 'DOCUMENT_ROOT' ];

require_once( $path . "/admin/modules/products/src/products.class.php" );
require_once( $path . "/admin/functions/forms.php" );

$db = new database( $pdo );
$carousel = new products( $pdo );

$row = $carousel->getGallerySettings( 1 );

$maxItems  = $row['max_items'];
$itemsInRow = $row['itemsInRow']; //2 3 4 6
$folder     = $row['folder'];
?>

<div class="offcanvas offcanvas-end bg-dark text-white" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <h2 id="offcanvasRightLabel"><i class="bi bi-gear"></i> Instellingen</h2>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    
            <div class="row">
            
            <div class="form-group mt-2 col-md-12">
                <label for="height">Hoogte</label>
                <div class="input-group mt-2">
                    <?php echo input("text", 'height', $height, "height".$id, 'class="form-control autosave_settings" data-field="height" data-set="'.$hash.'"'); ?>
                    <span class="input-group-text" id="basic-addon2">Pixels</span>
                </div>
                <small>(700px standaard)</small>
            </div>
            
            <div class="form-group mt-2 col-md-12">
                <label for="height">Snelheid</label>
                <div class="input-group mt-2">
                    <?php echo input("text", 'speed', $speed, "speed".$id, 'class="form-control autosave_settings" data-field="speed" data-set="'.$hash.'"'); ?>
                    <span class="input-group-text" id="basic-addon2">Milliseconde</span>
                </div>
                <small>(4000ms standaard)</small>
            </div>
                          
            <div class="form-group mt-4 col-md-12">
                <div class="form-check form-switch">
                  <input name"buttons" class="form-check-input settings_switchbox" type="checkbox" data-field="buttons" data-set="<?php echo $hash; ?>" <?php echo ($buttons == 'y') ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="flexSwitchCheckChecked">Links / Rechts navigatie</label>
                </div>
            </div>  
                         
            <div class="form-group mt-4 col-md-12">
                <div class="form-check form-switch">
                  <input name"indicators" class="form-check-input settings_switchbox" type="checkbox" data-field="indicators" data-set="<?php echo $hash; ?>" <?php echo ($indicators == 'y') ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="flexSwitchCheckChecked">Bodem navigatie</label>
                </div>
            </div>  

          </div>
      
          <div class="row mt-2">
            <div class="col">
              <label for="folder" class="mt-2">Map</label>  	
<?php echo input("text", 'folder', $folder, "folder".$id, 'class="form-control autosave_settings" data-field="folder" data-set="'.$hash.'" disabled'); ?>
        
              </div>
       
          </div>
      
</div>
</div>