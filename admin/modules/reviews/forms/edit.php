  <div class="row mt-4">
    <div class="col-md-12">
      <div class="card shadow">
        <div class="card-body">
          <div class="row">
            <div class="form-group mt-2 col-md-5">
              <label for="subject">Onderwerp</label>
<?php echo input("text", 'subject', $subject, "subject".$id, 'class="form-control autosave" data-field="subject" data-set="'.$hash.'"'); ?>
            </div>
            <div class="form-group mt-2 col-md-3">
              <label for="location">Plaats</label>
<?php echo input("text", 'location', $location, "location".$id, 'class="form-control autosave" data-field="location" data-set="'.$hash.'"'); ?>
            </div>
            <div class="form-group mt-2 col-md-3">
              <label for="reviewdate">Datum</label>
<?php echo input("date", 'reviewdate', $reviewdate, "reviewdate".$id, 'class="form-control autosave" data-field="reviewdate" data-set="'.$hash.'"'); ?>
            </div>
            <div class="form-group mt-2 col-md-1">
              <label for="score">Cijfer</label>
<?php echo input("text", 'score', $score, "score".$id, 'class="form-control autosave" data-field="score" data-set="'.$hash.'"'); ?>
            </div>
          </div>
          <div class="row">
            <div class="form-group mt-2 col-md-6">
              <label for="description">Omschrijving</label>
                <?php 
				$extra = 'class="form-control autosave_text summernote" data-field="description" data-set="'.$hash.'"';
				echo textarea('description', $description, $extra); 
                ?>
              </div>
            <div class="form-group mt-2 col-md-6">
              <label for="reaction">Reactie</label>
          <?php 
				$extra = 'class="form-control autosave_text summernote" data-field="reaction" data-set="'.$hash.'"';
				echo textarea('reaction', $reaction, $extra); 
                ?>
              </div>
            <div class="form-group mt-2 col-md-12">
              <label for="seo_url">SEO URL</label>
<?php echo input("text", 'seo_url', $seo_url, "seo_url".$id, 'class="form-control autosave" data-field="seo_url" data-set="'.$hash.'"'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>