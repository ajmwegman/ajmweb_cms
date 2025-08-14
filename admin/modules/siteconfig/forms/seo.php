<div class="container mt-3">
    
<div class="card card-body shadow">
<div class="row">
    <h3>Website instellingen</h3>
<div class="row mt-2">
  <div class="col-md-6">
    <div class="form-group">
      <label for="menuLabel">Website</label>
      <?php echo input("text", 'titel', $data['titel'], "titel", 'class="form-control autosave_config" data-field="titel" data-set="'.$data['id'].'"'); ?> </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="menuLabel">Website Titel</label>
      <?php echo input("text", 'web_naam', $data['web_naam'], "web_naam", 'class="form-control autosave_config" data-field="web_naam" data-set="'.$data['id'].'"'); ?> </div>
  </div></div>
    <div class="row mt-2">
  <div class="col-md-12">
    <div class="form-group">
      <label for="menuLabel">Zoekwoorden</label>
      <?php echo input("text", 'zoekwoorden', $data['zoekwoorden'], "zoekwoorden", 'class="form-control autosave_config" data-field="zoekwoorden" data-set="'.$data['id'].'"'); ?> </div>
  </div>   
  </div>   
        <div class="row mt-2">

  <div class="col-md-12">
    <div class="form-group">
      <label for="menuLabel">Beschrijving:</label>
      <?php echo input("text", 'beschrijving', $data['beschrijving'], "beschrijving", 'class="form-control autosave_config" data-field="beschrijving" data-set="'.$data['id'].'"'); ?> </div>
  </div>
  </div>

  <div class="row mt-4">
    <div class="col-md-12">
      <h4>SEO Taken</h4>
      <ul class="list-group">
        <li class="list-group-item">
          <input class="form-check-input me-1" type="checkbox" id="task-title">
          <label class="form-check-label" for="task-title">Gebruik een duidelijke en unieke websitenaam</label>
        </li>
        <li class="list-group-item">
          <input class="form-check-input me-1" type="checkbox" id="task-keywords">
          <label class="form-check-label" for="task-keywords">Voeg relevante zoekwoorden toe</label>
        </li>
        <li class="list-group-item">
          <input class="form-check-input me-1" type="checkbox" id="task-description">
          <label class="form-check-label" for="task-description">Schrijf een beknopte beschrijving van ongeveer 150 tekens</label>
        </li>
        <li class="list-group-item">
          <input class="form-check-input me-1" type="checkbox" id="task-sitemap">
          <label class="form-check-label" for="task-sitemap">Dien de sitemap in bij zoekmachines</label>
        </li>
      </ul>
    </div>
  </div>
  </div>
</div>

</div>
