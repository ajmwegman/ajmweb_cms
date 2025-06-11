<form action="/admin/modules/auctions/bin/add.php" enctype="multipart/form-data" method="post" name="menuform" id="menuform" autocomplete="off">
  <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
  <input type="hidden" id="hash" name="hash" value="">
  <input type="hidden" id="searchId" name="searchId"> <!-- Hidden field to store the selected productId -->

  <div class="row mt-4">
    <div class="col-md-12">
      <div class="card shadow">
        <div class="card-header"><h5>Veiling toevoegen</h5></div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-12">
              <label for="searchInput" class="form-label mb-1">Zoek naar een product:</label>
              <input type="text" id="searchInput" name="searchInput" class="form-control" autocomplete="off">
              <div id="searchResults"></div>
            </div>
          </div>

          <!-- Startdatum en starttijd -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="startDate" class="form-label mb-1">Startdatum:</label>
              <input type="date" id="startDate" name="startDate" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label for="startTime" class="form-label mb-1">Starttijd:</label>
              <input type="time" id="startTime" name="startTime" class="form-control" required>
            </div>
          </div>

          <!-- Einddatum en eindtijd -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="endDate" class="form-label mb-1">Einddatum:</label>
              <input type="date" id="endDate" name="endDate" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label for="endTime" class="form-label mb-1">Eindtijd:</label>
              <input type="time" id="endTime" name="endTime" class="form-control" required>
            </div>
          </div>

                    <div class="row">
                        <h5 class="mb-3">Prijs en kosten</h5>
                        <div class="row">
                        <div class="form-group mt-2 col-md-4">
                            <label for="startPrice" class="form-label mb-1">Start prijs</label>
                            <?php echo input("text", "startPrice", "", "startPrice", 'class="form-control"'); ?>
                        </div>
                        <div class="form-group mt-2 col-md-4">
                            <label for="startPrice" class="form-label mb-1">Richt prijs</label>
                            <?php echo input("text", "guidePrice", "", "guidePrice", 'class="form-control"'); ?>
                        </div>
                        <div class="form-group mt-2 col-md-4">
                            <label for="startPrice" class="form-label mb-1">Minimale prijs</label>
                            <?php echo input("text", "reservePrice", "", "reservePrice", 'class="form-control"'); ?>
                        </div>
                            </div>
                        <div class="row">
                        <div class="form-group mt-2 col-md-4">
                            <label for="minUp" class="form-label mb-1">Minimale verhoging</label>
                            <?php echo input("text", "minUp", "", "minUp", 'class="form-control"'); ?>
                        </div>
                        <div class="form-group mt-2 col-md-4">
                            <label for="commision" class="form-label mb-1">Commisie</label>
                            <?php echo input("text", "commision", "", "commision", 'class="form-control"'); ?>
                        </div>
                        <div class="form-group mt-2 col-md-4">
                            <label for="shippingcost" class="form-label mb-1">Verzendkosten</label>
                            <?php echo input("text", "shippingcost", "", "shippingcost", 'class="form-control"'); ?>
                        </div>
                            </div>
                    </div>

          <div class="row text-center">
            <div class="form-group mt-2">
              <button type="submit" class="btn btn-dark" id="add_menu_item">Veiling Activeren</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
