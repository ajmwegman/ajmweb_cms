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
  </div>
</div>

</div>