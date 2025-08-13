<?php
// Admin orders module main page
?>
<h2>Bestellingen</h2>
<div class="mb-3">
  <button id="export_postnl" class="btn btn-sm btn-secondary">Export PostNL CSV</button>
  <button id="export_dhl" class="btn btn-sm btn-secondary">Export DHL CSV</button>
</div>
<div class="row mt-4">
  <div class="col-md-12">
    <div class="card shadow">
      <div class="card-header">
        <div class="row">
          <div class="col-2"><h5>Ordernr</h5></div>
          <div class="col-3"><h5>Klant</h5></div>
          <div class="col-2"><h5>Status</h5></div>
          <div class="col-3"><h5>Factuur</h5></div>
          <div class="col-2 text-end"><h5>Acties</h5></div>
        </div>
      </div>
      <div class="card-body">
        <div id="orderlist"></div>
      </div>
    </div>
  </div>
</div>
<script src="/admin/modules/orders/js/orders.js" type="text/javascript"></script>
