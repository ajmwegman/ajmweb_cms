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
  <div class="row mt-4">
    <div class="col-md-12">
      <h4>Kleine tools ter ondersteuning van vindbaarheid</h4>

      <div class="mt-3">
        <h5>SEO Snippet Preview</h5>
        <input type="text" id="snippet-title" class="form-control mb-2" placeholder="Paginatitel">
        <input type="text" id="snippet-description" class="form-control mb-2" placeholder="Meta beschrijving">
        <div class="border p-2">
          <div id="preview-title" class="fw-bold">Voorbeeld titel</div>
          <div id="preview-url" class="text-success">www.jouwsite.nl</div>
          <div id="preview-description">Voorbeeld beschrijving</div>
        </div>
      </div>

      <div class="mt-4">
        <h5>Zoekwoordanalyse-widget</h5>
        <textarea id="keyword-content" class="form-control mb-2" rows="3" placeholder="Plak hier de pagina-inhoud"></textarea>
        <button class="btn btn-primary" id="analyze-keywords" type="button">Analyseer</button>
        <ul id="keyword-results" class="mt-2"></ul>
      </div>

      <div class="mt-4">
        <h5>Broken Link Checker</h5>
        <textarea id="link-checker-urls" class="form-control mb-2" rows="3" placeholder="Eén URL per regel"></textarea>
        <button class="btn btn-primary" id="check-links" type="button">Controleer</button>
        <ul id="link-results" class="mt-2"></ul>
      </div>

    </div>
  </div>
  </div>
</div>

</div>
