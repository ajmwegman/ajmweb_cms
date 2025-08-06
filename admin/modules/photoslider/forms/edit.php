 <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
  <input type="hidden" id="hash" name="hash" value="">
  <div class="row mt-4">
    <div class="col-md-12">
      <div class="card shadow">
        <div class="card-header"><h5>Afbeeldingen toevoegen</h5></div>
        <div class="card-body">
          <div class="row">
            <div class="form-group mt-2 col-md-12">
              <label for="Onderwerp">Titel:</label>
<?php echo input("text", 'subject', $subject, "subject".$id, 'class="form-control autosave" data-field="subject" data-set="'.$hash.'"'); ?>
            </div>
            <div class="form-group mt-2 col-md-12">
              <label for="category">Omschrijving:</label>
<?php echo input("text", 'category', $category, "category".$id, 'class="form-control autosave" data-field="category" data-set="'.$hash.'"'); ?>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col">
              <label for="image" class="mt-2">Afbeelding</label>  	
                
                <div class="card card-body text-center mt-2">

                    <div id="cat_image"><?php require_once("../admin/modules/photoslider/bin/cat_image.php"); ?></div>

                    
                    <a class="btn btn-primary mt-4" id="single_cat_upload" data-set="<?php echo $hash; ?>">Selecteer een afbeelding</a>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>