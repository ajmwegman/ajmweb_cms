<div class="container mt-3">
    
<div class="row">

  <div class="col-md-3">
    <div class="form-group">
      <label for="menuLabel">Kleur 1:</label>
      <?php echo input("text", 'kleur1', $data['kleur1'], "kleur1", 'class="form-control autosave_config_site" data-field="kleur1" data-set="'.$data['id'].'"'); ?> </div>
  </div>
  <div class="col-md-3">
    <div class="form-group">
      <label for="menuLabel">Kleur 2:</label>
      <?php echo input("color", 'kleur2', $data['kleur2'], "kleur2", 'class="form-control autosave_config_site" data-field="kleur2" data-set="'.$data['id'].'"'); ?> </div>
  </div>
  <div class="col-md-3">
    <div class="form-group">
      <label for="menuLabel">Kleur 3:</label>
      <?php echo input("color", 'kleur3', $data['kleur3'], "kleur3", 'class="form-control autosave_config_site" data-field="kleur3" data-set="'.$data['id'].'"'); ?> </div>
  </div>
  <div class="col-md-3">
    <div class="form-group">
      <label for="menuLabel">Kleur 4:</label>
      <?php echo input("color", 'kleur4', $data['kleur4'], "kleur4", 'class="form-control autosave_config_site" data-field="kleur4" data-set="'.$data['id'].'"'); ?> </div>
  </div>
  </div>
</div>