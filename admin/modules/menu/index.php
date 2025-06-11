<?php
echo "<h2>" . $menu->getGroupName( $group_id ) . "</h2>";

require_once( "../admin/template/navtabs.php" );
?>
<h2>Menu Beheer</h2>
<!--
 <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="tab1" data-bs-toggle="tab" data-bs-target="#content1" type="button" role="tab" aria-controls="content1" aria-selected="true">Tab 1</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="tab2" data-bs-toggle="tab" data-bs-target="#content2" type="button" role="tab" aria-controls="content2" aria-selected="false">Tab 2</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="tab3" data-bs-toggle="tab" data-bs-target="#content3" type="button" role="tab" aria-controls="content3" aria-selected="false">Tab 3</button>
    </li>
  </ul>
  
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="content1" role="tabpanel" aria-labelledby="tab1">
      <p>Standaard Taal (NL)</p>
    </div>
    <div class="tab-pane fade" id="content2" role="tabpanel" aria-labelledby="tab2">
      <p>Small text for tab 2</p>
    </div>
    <div class="tab-pane fade" id="content3" role="tabpanel" aria-labelledby="tab3">
      <p>Small text for tab 3</p>
    </div>
  </div>
-->
<div class="row mt-4">
  <div class="col">
    <div class="card shadow">
      <div class="card-header">
        <div class="row">
          <div class="col-5">
            <h5>Titel</h5>
          </div>
          <div class="col-4">
            <h5>Locatie</h5>
          </div>
          <div class="col-2 text-end">
            <h5>Aan/uit</h5>
          </div>
          <div class="col-1"></div>
        </div>
      </div>
      <div class="card-body">
        <div id="menulist">
          <?php require("../admin/modules/menu/bin/summary.php"); ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Button trigger modal -->
<?php include("forms/add.php"); ?>
<script src="/admin/modules/menu/js/menulist.js"  type="text/javascript"></script>