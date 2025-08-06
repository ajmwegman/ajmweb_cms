<?php
$selectbox = selectbox( "Kies een protocol", 'loc_http', $data[ 'loc_http' ], $http_locations, 'class="form-select autosave" data-field="loc_http" data-set="' . $data[ 'group_id' ] . '"' );
?>
    <div class="row">
      <div class="col-md-4 mt-1"><?php echo $selectbox; ?></div>
      <div class="col-md-4">
        <div class="form-group mt-2">
          <label for="menuLabel">Domeinnaam:</label>
          <?php echo input("text", 'loc_domain', $data['loc_domain'], "loc_domain".$data['group_id'], 'class="form-control autosave" data-field="loc_domain" data-set="'.$data['group_id'].'"'); ?> </div>
      </div>
      <div class="col-md-4">
        <div class="form-group mt-2">
          <label for="menuLabel">Website Groep:</label>
      <?php echo input("text", 'Websitenaam', $data['web_naam'], "web_naam".$data['group_id'], 'class="form-control autosave" data-field="web_naam" data-set="'.$data['group_id'].'"'); ?> 
          </div>
      </div>
    </div>
<div class="form-group mt-2">
      <label for="menuLabel">Website Titel:</label>
      <?php echo input("text", 'Titel', $data['title'], "title".$data['group_id'], 'class="form-control autosave" data-field="title" data-set="'.$data['group_id'].'"'); ?> </div>
    <div class="form-group mt-2">
      <label for="menuLabel">Omschrijving:</label>
      <?php echo input("text", 'description', $data['description'], "description".$data['group_id'], 'class="form-control autosave" data-field="description" data-set="'.$data['group_id'].'"'); ?> <small>Niet langer dan 140 tekens</small> </div>
    <div class="form-group mt-2">
      <label for="menuLabel">Zoekwoorden:</label>
      <?php echo input("text", 'keywords', $data['keywords'], "keywords".$data['group_id'], 'class="form-control autosave" data-field="keywords" data-set="'.$data['group_id'].'"'); ?> <small>Komma gescheiden</small> </div>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group mt-2">
          <label for="menuLabel">Standaard e-mailadres:</label>
          <?php echo input("text", 'std_mail', $data['std_mail'], "std_mail".$data['group_id'], 'class="form-control autosave" data-field="std_mail" data-set="'.$data['group_id'].'"'); ?> </div>
      </div>
      <div class="col-md-4">
        <div class="form-group mt-2">
          <label for="menuLabel">Nieuwsbrief e-mailadres:</label>
          <?php echo input("text", 'news_mail', $data['news_mail'], "news_mail".$data['group_id'], 'class="form-control autosave" data-field="news_mail" data-set="'.$data['group_id'].'"'); ?> </div>
      </div>
      <div class="col-md-4">
        <div class="form-group mt-2">
          <label for="menuLabel">Nieuwsbrief afzender:</label>
          <?php echo input("text", 'sender', $data['sender'], "sender".$data['group_id'], 'class="form-control autosave" data-field="sender" data-set="'.$data['group_id'].'"'); ?> </div>
      </div>
    </div>
    <div class="form-group mt-2">
      <label for="menuLabel">Website pointers:</label>
      <?php
      echo textarea( 'loc_pointers', $data[ 'loc_pointers' ], 'class="form-control autosave" data-field="loc_pointers" data-set="'.$data['group_id'].'"' );
      ?>
      <small>Optioneel, als er andere domeinnamen naar dit domein moeten verwijzen.</small> </div>
    <div class="form-group mt-2">Activeren:
        <div class="form-check form-switch">
            <input class="form-check-input activate" type="checkbox" id="activate" name="activate" data-set="<?php echo $data['group_id']; ?>" <?php echo ($data['activate'] == 'y') ? 'checked' : ''; ?>>
            <!--<label class="form-check-label" for="english">English</label>-->
		</div>
    </div>
  