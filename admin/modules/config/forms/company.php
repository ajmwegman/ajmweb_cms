<div class="row">
    <div class="col-md-8">
    
        <div class="form-group mt-2">
          <label for="menuLabel">Straat:</label>
          <?php echo input("text", 'street', $data['street'], "street".$data['group_id'], 'class="form-control autosave" data-field="street" data-set="'.$data['group_id'].'"'); ?> 
        </div>

    </div>
    <div class="col-md-4">
    
        <div class="form-group mt-2">
          <label for="menuLabel">Huisnummer:</label>
          <?php echo input("text", 'housenumber', $data['housenumber'], "housenumber".$data['group_id'], 'class="form-control autosave" data-field="housenumber" data-set="'.$data['group_id'].'"'); ?> 
        </div>
    
    </div>
</div>

<div class="row">
    <div class="col">
    <div class="form-group mt-2">
  <label for="menuLabel">Postcode:</label>
  <?php echo input("text", 'zipcode', $data['zipcode'], "zipcode".$data['group_id'], 'class="form-control autosave" data-field="zipcode" data-set="'.$data['group_id'].'"'); ?> 
</div>
    </div>
    <div class="col">
    <div class="form-group mt-2">
  <label for="menuLabel">Plaats:</label>
  <?php echo input("text", 'city', $data['city'], "city".$data['group_id'], 'class="form-control autosave" data-field="city" data-set="'.$data['group_id'].'"'); ?> 
</div>
    </div>
</div>

<div class="row">
    <div class="col"><div class="form-group mt-2">
  <label for="menuLabel">Telefoonnummer:</label>
  <?php echo input("text", 'phonenumber', $data['phonenumber'], "phonenumber".$data['group_id'], 'class="form-control autosave" data-field="phonenumber" data-set="'.$data['group_id'].'"'); ?> 
</div>
    </div>
    <div class="col">
    
<div class="form-group mt-2">
  <label for="menuLabel">Mobielnummer:</label>
  <?php echo input("text", 'mobilenumber', $data['mobilenumber'], "mobilenumber".$data['group_id'], 'class="form-control autosave" data-field="mobilenumber" data-set="'.$data['group_id'].'"'); ?> 
</div>
    </div>
</div>

<div class="row">
    <div class="col">
<div class="form-group mt-2">
  <label for="menuLabel">Kamer van Koophandel:</label>
  <?php echo input("text", 'kvk', $data['kvk'], "kvk".$data['group_id'], 'class="form-control autosave" data-field="kvk" data-set="'.$data['group_id'].'"'); ?> 
</div></div>
    <div class="col">
<div class="form-group mt-2">
  <label for="menuLabel">BTW Nummer:</label>
  <?php echo input("text", 'vat_number', $data['vat_number'], "vat_number".$data['group_id'], 'class="form-control autosave" data-field="vat_number" data-set="'.$data['group_id'].'"'); ?> 
</div></div>
</div>





