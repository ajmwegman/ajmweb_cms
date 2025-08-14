			<input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
			<input type="hidden" id="hash" name="hash" value="<?php echo $hash; ?>">
		  <div class="card shadow">
			  <h5 class="card-header">Bewerken</h5>
			  <div class="card-body">
			<div class="form-group mt-2">
			  <label for="menuLabel">Titel</label>
				<?php echo input("text", 'title[]', $title, "title".$id, 'class="form-control autosave" data-field="title" data-set="'.$hash.'"'); ?>
			</div>
                        <div class="form-group mt-2 mb-2">
                <label for="location">Artikel</label>
                                <?php
                                $extra = 'class="form-control autosave wysiwyg" data-field="content" data-set="'.$hash.'"';
                                summernote_editor('content', $content, $extra);
                                ?>
                          </div>
                                  <?php echo $selectbox; ?>
                          <div class="form-group mt-2">
                          <label for="location">SEO Url</label>
                                  <?php echo input("text", 'seo_url[]', $seo_url, "seo_url".$id, 'class="form-control autosave" data-field="seo_url" data-set="'.$hash.'"'); ?>
                                  <small>* optioneel - wordt automatisch gegenereerd bij eerste invoer</small>
                          </div>
                        <div class="form-group mt-2">
                          <label for="meta_title">Meta Title</label>
                                  <?php echo input("text", 'meta_title[]', $meta_title, "meta_title".$id, 'class="form-control autosave" data-field="meta_title" data-set="'.$hash.'"'); ?>
                        </div>
                        <div class="form-group mt-2">
                          <label for="meta_description">Meta Description</label>
                                  <?php echo textarea('meta_description', $meta_description, 'class="form-control autosave" data-field="meta_description" data-set="'.$hash.'"'); ?>
                        </div>
                        <div class="form-group mt-2">
                          <label for="status">Status</label>
                                  <?php echo selectbox("Status", 'status', $status, ['draft'=>'Draft', 'published'=>'Published'], 'class="form-select autosave" data-field="status" data-set="'.$hash.'"'); ?>
                        </div>
                        <div class="form-group mt-2">
                          <label for="location">Zoekwoorden</label>
                                  <?php echo input("text", 'keywords[]', $keywords, "keywords".$id, 'class="form-control autosave" data-field="keywords" data-set="'.$hash.'"'); ?>
                                  <small>* optioneel - komma gescheiden</small>
                          </div>
		
		  </div>
		  </div>
		</form>