<form method="post">
<input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
<input type="hidden" id="hash" name="hash" value="<?php echo $hash; ?>">
<div class="card shadow">
  <h5 class="card-header">Bewerken</h5>
  <div class="card-body">
    <ul class="nav nav-tabs" id="contentTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="content-general-tab" data-bs-toggle="tab" data-bs-target="#content-general" type="button" role="tab">Algemeen</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="content-seo-tab" data-bs-toggle="tab" data-bs-target="#content-seo" type="button" role="tab">SEO</button>
      </li>
    </ul>
    <div class="tab-content pt-3" id="contentTabContent">
      <div class="tab-pane fade show active" id="content-general" role="tabpanel" aria-labelledby="content-general-tab">
        <div class="form-group mt-2">
          <label for="menuLabel">Titel</label>
          <?php echo input("text", 'title[]', $title, "title".$id, 'class="form-control autosave" data-field="title" data-set="'.$hash.'"'); ?>
        </div>
        <div class="form-group mt-2 mb-2">
          <label for="location">Artikel</label>
          <?php
          $extra = 'class="form-control autosave wysiwyg" data-field="content" data-set="'.$hash.'"';
          echo textarea('content', $content, $extra);
          ?>
        </div>
        <?php echo $selectbox; ?>
        <div class="form-group mt-2">
          <label for="location">SEO Url</label>
          <?php echo input("text", 'seo_url[]', $seo_url, "seo_url".$id, 'class="form-control autosave" data-field="seo_url" data-set="'.$hash.'"'); ?>
          <small>* optioneel - wordt automatisch gegenereerd bij eerste invoer</small>
        </div>
        <div class="form-group mt-2">
          <label for="location">Zoekwoorden</label>
          <?php echo input("text", 'keywords[]', $keywords, "keywords".$id, 'class="form-control autosave" data-field="keywords" data-set="'.$hash.'"'); ?>
          <small>* optioneel - komma gescheiden</small>
        </div>
      </div>
      <div class="tab-pane fade" id="content-seo" role="tabpanel" aria-labelledby="content-seo-tab">
        <div class="form-group mt-2">
          <label for="meta_title">Meta titel</label>
          <?php echo input("text", 'meta_title[]', $meta_title, "meta_title".$id, 'class="form-control autosave" data-field="meta_title" data-set="'.$hash.'"'); ?>
        </div>
        <div class="form-group mt-2">
          <label for="meta_description">Meta omschrijving</label>
          <?php echo textarea('meta_description', $meta_description, 'class="form-control autosave" data-field="meta_description" data-set="'.$hash.'"'); ?>
        </div>
        <div class="form-group mt-2">
          <label for="og_title">OG titel</label>
          <?php echo input("text", 'og_title[]', $og_title, "og_title".$id, 'class="form-control autosave" data-field="og_title" data-set="'.$hash.'"'); ?>
        </div>
        <div class="form-group mt-2">
          <label for="og_description">OG omschrijving</label>
          <?php echo textarea('og_description', $og_description, 'class="form-control autosave" data-field="og_description" data-set="'.$hash.'"'); ?>
        </div>
        <div class="form-group mt-2">
          <label for="og_image">OG afbeelding URL</label>
          <?php echo input("text", 'og_image[]', $og_image, "og_image".$id, 'class="form-control autosave" data-field="og_image" data-set="'.$hash.'"'); ?>
        </div>
      </div>
    </div>
  </div>
</div>
</form>
