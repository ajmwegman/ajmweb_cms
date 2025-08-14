<?php
// Admin analytics module main page
require_once($_SERVER['DOCUMENT_ROOT'] . "/system/database.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/src/analytics.php");

$analytics = new AdvancedAnalytics($pdo);
$stats = $analytics->getStats();
?>
<h2>Analytics</h2>
<div class="row mb-3">
  <div class="col-md-2">
    <input type="date" id="start_date" class="form-control" />
  </div>
  <div class="col-md-2">
    <input type="date" id="end_date" class="form-control" />
  </div>
  <div class="col-md-3">
    <input type="text" id="category" class="form-control" placeholder="Categorie" />
  </div>
  <div class="col-md-3">
    <input type="text" id="channel" class="form-control" placeholder="Kanaal" />
  </div>
  <div class="col-md-2">
    <button id="filter" class="btn btn-primary w-100">Filter</button>
  </div>
</div>
<div class="mb-3">
  <button id="export_csv" class="btn btn-sm btn-secondary">Export CSV</button>
</div>
<canvas id="analyticsChart" height="120"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/admin/modules/analytics/js/analytics.js"></script>
<script>
  var analyticsStats = <?php echo json_encode($stats); ?>;
</script>
