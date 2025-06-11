    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body">
    

                    <!-- Startdatum en starttijd -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="startDate" class="form-label mb-1">Startdatum:</label>
                            <?php echo input("date", "startDate", $startdate, "startDate", 'class="form-control autosave" data-field="startDate" data-set="'.$hash.'"'); ?>
                        </div>
                        <div class="col-md-6">
                            <label for="startTime" class="form-label mb-1">Starttijd:</label>
                            <?php echo input("time", "startTime", $starttime, "startTime", 'class="form-control autosave" data-field="startTime" data-set="'.$hash.'"'); ?>
                        </div>
                    </div>

                    <!-- Einddatum en eindtijd -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="endDate" class="form-label mb-1">Einddatum:</label>
                            <?php echo input("date", "endDate", $enddate, "endDate", 'class="form-control autosave" data-field="endDate" data-set="'.$hash.'"'); ?>
                        </div>
                        <div class="col-md-6">
                            <label for="endTime" class="form-label mb-1">Eindtijd:</label>
                            <?php echo input("time", "endTime", $endtime, "endTime", 'class="form-control autosave" data-field="endTime" data-set="'.$hash.'"'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <h5 class="mb-3">Prijs en kosten</h5>
                        <div class="row">
                        <div class="form-group mt-2 col-md-4">
                            <label for="startPrice" class="form-label mb-1">Start prijs</label>
                            <?php echo input("text", "startPrice", $startprice, "startPrice", 'class="form-control autosave" data-field="startPrice" data-set="'.$hash.'"'); ?>
                        </div>
                        <div class="form-group mt-2 col-md-4">
                            <label for="startPrice" class="form-label mb-1">Richt prijs</label>
                            <?php echo input("text", "guidePrice", $guideprice, "guidePrice", 'class="form-control autosave" data-field="guidePrice" data-set="'.$hash.'"'); ?>
                        </div>
                        <div class="form-group mt-2 col-md-4">
                            <label for="startPrice" class="form-label mb-1">Minimale prijs</label>
                            <?php echo input("text", "reservePrice", $reserveprice, "reservePrice", 'class="form-control autosave" data-field="reservePrice" data-set="'.$hash.'"'); ?>
                        </div>
                            </div>
                        <div class="row">
                        <div class="form-group mt-2 col-md-4">
                            <label for="minUp" class="form-label mb-1">Minimale verhoging</label>
                            <?php echo input("text", "minUp", $minup, "minUp", 'class="form-control autosave" data-field="minUp" data-set="'.$hash.'"'); ?>
                        </div>
                        <div class="form-group mt-2 col-md-4">
                            <label for="commision" class="form-label mb-1">Commisie</label>
                            <?php echo input("text", "commision", $commision, "commision", 'class="form-control autosave" data-field="commision" data-set="'.$hash.'"'); ?>
                        </div>
                        <div class="form-group mt-2 col-md-4">
                            <label for="shippingcost" class="form-label mb-1">Verzendkosten</label>
                            <?php echo input("text", "shippingcost", $shippingcost, "shippingcost", 'class="form-control autosave" data-field="shippingcost" data-set="'.$hash.'"'); ?>
                        </div>
                            </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
