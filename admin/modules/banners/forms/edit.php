  <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
  <input type="hidden" id="hash" name="hash" value="">
  <div class="row mt-4">
    <div class="col-md-12">
      <div class="card shadow">
        <div class="card-header"><h5>Banners toevoegen</h5></div>
        <div class="card-body">
          <div class="row">
            <div class="form-group mt-2 col-md-4">
              <label for="Onderwerp">Naam</label>
<?php echo input("text", 'subject', $subject, "subject".$id, 'class="form-control autosave" data-field="subject" data-set="'.$hash.'"'); ?>
            </div>
            <div class="form-group mt-2 col-md-4">
              <label for="advertiser">Adverteerder</label>
<?php echo input("text", 'advertiser', $advertiser, "advertiser".$id, 'class="form-control autosave" data-field="advertiser" data-set="'.$hash.'"'); ?>

            </div>
            <div class="form-group mt-2 col-md-4">
              <label for="url">Doellocatie</label>
<?php echo input("text", 'url', $url, "url".$id, 'class="form-control autosave" data-field="url" data-set="'.$hash.'"'); ?>
            </div>
            <div class="form-group mt-2 col-md-6">
              <label for="startdate">Startdatum</label>
<?php echo input("date", 'startdate', $startdate, "startdate".$id, 'class="form-control autosave" data-field="startdate" data-set="'.$hash.'"'); ?>
            </div>
            <div class="form-group mt-2 col-md-6">
              <label for="enddate">Einddatum</label>
<?php echo input("date", 'enddate', $enddate, "enddate".$id, 'class="form-control autosave" data-field="enddate" data-set="'.$hash.'"'); ?>
            </div>
          </div>
                      <div class="row mt-2">
            <div class="form-group mt-2 col-md-6">
              <label for="description">Omschrijving</label>
                <?php 
				$extra = 'class="form-control autosave summernote" data-field="description" data-set="'.$hash.'"';
				echo textarea('description', $description, $extra); 
                ?>
              </div>
            <div class="col-md-6">
              <label for="image" class="mt-2">Afbeelding</label>  	
                
                <div class="card card-body text-center mt-2">

                    <div id="cat_image"><?php require_once("../admin/modules/banners/bin/cat_image.php"); ?></div>
                </div>
                <div class="card card-body text-center mt-2">
                    <a class="btn btn-primary mt-4" id="single_cat_upload" data-set="<?php echo $hash; ?>">Selecteer een afbeelding</a>

                </div>
        
              </div>
       
          </div>
        </div>
      </div>
    </div>
  </div>