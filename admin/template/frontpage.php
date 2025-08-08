<?php 
// Get all available sites
$sites = $analytics->getAllSites();
$currentSiteId = isset($_GET['site_id']) ? (int)$_GET['site_id'] : 1;
$stats = $analytics->getEnhancedStats(null, null, $currentSiteId); 
?>

<style>
  /* Modern Date Range & Filter Styling */
  .form-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .form-label i {
    color: #007bff;
    font-size: 1rem;
  }
  
  .form-control, .form-select {
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
    font-weight: 500;
    color: #495057;
    background: #ffffff;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
  }
  
  .form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    background: #f8f9ff;
    outline: none;
  }
  
  .form-control:hover, .form-select:hover {
    border-color: rgba(0, 123, 255, 0.3);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
  }
  
  /* Info Display Styling */
  .info-display {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 1rem;
    border-radius: 12px;
    border: 1px solid rgba(0, 0, 0, 0.05);
  }
  
  .info-label {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.3px;
  }
  
  .info-value {
    font-size: 1rem;
    font-weight: 600;
    color: #1a1a1a;
    margin-left: 0.5rem;
  }
  
  .range-display {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .range-label {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.3px;
  }
  
  .range-value {
    font-size: 0.95rem;
    font-weight: 600;
    color: #007bff;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    padding: 0.75rem 1rem;
    border-radius: 10px;
    border: 1px solid rgba(0, 123, 255, 0.1);
    display: inline-block;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
  }
  
  .range-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
  }
  
  .range-actions .btn {
    border-radius: 10px;
    font-size: 0.8rem;
    font-weight: 600;
    padding: 0.6rem 1rem;
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 123, 255, 0.2);
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    color: #007bff;
    text-transform: uppercase;
    letter-spacing: 0.3px;
  }
  
  .range-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    border-color: transparent;
  }
  
  .range-actions .btn i {
    margin-right: 0.4rem;
    font-size: 0.9rem;
  }
  
  /* Responsive Design */
  @media (max-width: 768px) {
    .date-range-container {
      gap: 0.75rem;
    }
    
    .date-range-info {
      margin-top: 1rem;
    }
    
    .range-actions {
      justify-content: center;
    }
    
    .range-actions .btn {
      flex: 1;
      min-width: 120px;
    }
  }
  
  /* Animation for date inputs */
  .date-input {
    animation: slideIn 0.3s ease-out;
  }
  
  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  /* Hover effects for better interactivity */
  .date-input-group:hover .input-wrapper {
    transform: translateY(-2px);
  }
  
  .date-input-group:hover .date-slider-track {
    transform: scaleX(0.8);
  }
  
  /* Focus states */
  .date-input:focus + .date-slider-track {
    transform: scaleX(1);
  }
  
  /* Table styling for fixed height */
  .top-pages-table {
    width: 100%;
  }
  
  .top-pages-table thead th {
    background: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
  }
  
  .top-pages-table tbody tr:hover {
    background-color: #f8f9fa;
  }
  
  .table-responsive {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
  }
  
  /* Ensure proper z-index for cards */
  .card {
    position: relative;
    z-index: 1;
  }
  
  /* Fix table container */
  .table-container {
    position: relative;
    height: 350px;
    overflow: hidden;
  }
  
  /* Modern Dashboard Styling */
  .card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    background: #ffffff;
    overflow: hidden;
  }
  
  .card:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
  }
  
  .card-header {
    background: transparent;
    border: none;
    padding: 1.5rem 1.5rem 0.5rem;
    font-weight: 600;
    color: #1a1a1a;
    font-size: 0.95rem;
  }
  
  .card-body {
    padding: 1.5rem;
  }
  
  /* Stats Cards Modern Styling */
  .stats-card {
    border: none;
    border-radius: 16px;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }
  
  .stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #007bff, #0056b3);
  }
  
  .stats-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
  }
  
  .stats-card .card-body {
    padding: 2rem 1.5rem;
  }
  
  .stats-card .card-title {
    font-size: 0.85rem;
    font-weight: 500;
    color: #6c757d;
    margin-bottom: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .stats-icon {
    font-size: 2rem;
    color: #007bff;
    margin-bottom: 1rem;
    opacity: 0.8;
  }
  
  .stats-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1;
    margin-bottom: 0.5rem;
  }
  
  .stats-subtitle {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: 500;
  }
  
  /* Chart Container Fix for Infinite Height Issue */
  #browserChart, #deviceChart {
    max-height: 300px !important;
    height: 300px !important;
    width: 100% !important;
  }
  
  /* Breakdown Cards Modern Styling */
  .breakdown-card {
    max-width: 300px;
    margin: 0 auto;
    height: 100%;
    border: none;
    border-radius: 16px;
    background: #ffffff;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
  }
  
  .breakdown-card:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
  }
  
  .breakdown-card .card-header {
    background: transparent;
    border: none;
    padding: 1.5rem 1.5rem 0.5rem;
    font-weight: 600;
    color: #1a1a1a;
    font-size: 0.95rem;
  }
  
  .breakdown-card .card-body {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 320px;
    padding: 1rem 1.5rem 1.5rem;
  }
  
  .breakdown-card canvas {
    max-height: 180px;
    margin-bottom: 1rem;
  }
  
  /* Device and Browser Stats Modern Styling */
  .device-stat-item, .browser-stat-item {
    padding: 1rem;
    border-radius: 12px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    transition: all 0.3s ease;
    margin-bottom: 0.75rem;
    border: 1px solid rgba(0, 0, 0, 0.05);
  }
  
  .device-stat-item:hover, .browser-stat-item:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: rgba(0, 123, 255, 0.2);
  }
  
  .device-icon, .browser-icon {
    margin-bottom: 0.75rem;
  }
  
  .device-name, .browser-name {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.3px;
  }
  
  .device-count, .browser-count {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0.25rem 0;
  }
  
  .device-percentage, .browser-percentage {
    font-size: 0.75rem;
    color: #007bff;
    font-weight: 600;
  }
  
  /* Top Pages Table Modern Styling */
  .top-pages-table {
    border: none;
    border-radius: 12px;
    overflow: hidden;
  }
  
  .top-pages-table thead th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    font-weight: 600;
    color: #495057;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    padding: 1rem 0.75rem;
  }
  
  .top-pages-table tbody tr {
    border: none;
    transition: all 0.3s ease;
  }
  
  .top-pages-table tbody tr:hover {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    transform: scale(1.01);
  }
  
  .top-pages-table tbody td {
    border: none;
    padding: 1rem 0.75rem;
    vertical-align: middle;
  }
  
  .badge {
    border-radius: 8px;
    font-weight: 600;
    padding: 0.5rem 0.75rem;
  }
  
  /* Loading Indicator Modern Styling */
  #loadingIndicator {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    text-align: center;
    z-index: 9999;
  }
  
  #loadingIndicator .spinner-border {
    width: 3rem;
    height: 3rem;
    border-width: 0.3rem;
  }
  
  /* Container Background */
  body {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
  }
  
  /* Enhanced Shadows and Depth */
  .visitorChart {
    border-radius: 12px;
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
  }
  
  /* Modern Table Responsive Container */
  .table-responsive {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    background: #ffffff;
  }
  
  /* Responsive adjustments */
  @media (max-width: 768px) {
    .breakdown-card {
      max-width: 100%;
    }
    
    .breakdown-card .card-body {
      min-height: auto;
      padding: 1rem;
    }
    
    .stats-card .card-body {
      padding: 1.5rem 1rem;
    }
    
    .stats-number {
      font-size: 2rem;
    }
  }
</style>

<div class="container-fluid px-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); min-height: 100vh; padding-top: 2rem; padding-bottom: 2rem;">
  <!-- Filters Card: Site & Datum Selectie -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h5><i class="bi bi-sliders"></i> Site & Datum Selectie</h5>
        </div>
        <div class="card-body">
          <div class="row g-3 align-items-end">
            <div class="col-lg-4 col-md-6">
              <label for="siteSelector" class="form-label">
                <i class="bi bi-building"></i> Selecteer Website
              </label>
              <select id="siteSelector" name="site_id" class="form-select" onchange="changeSite()">
                <?php foreach ($sites as $site): ?>
                  <option value="<?php echo $site['id']; ?>" <?php echo ($site['id'] == $currentSiteId) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($site['name']); ?> (<?php echo htmlspecialchars($site['domain']); ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-lg-4 col-md-3">
              <label for="startDate" class="form-label">
                <i class="bi bi-calendar-event"></i> Begin Datum
              </label>
              <input type="date" id="startDate" name="startDate" class="form-control" value="<?php echo date('Y-m-01'); ?>" onchange="updateAllAnalytics()">
            </div>
            <div class="col-lg-4 col-md-3">
              <label for="endDate" class="form-label">
                <i class="bi bi-calendar-check"></i> Eind Datum
              </label>
              <input type="date" id="endDate" name="endDate" class="form-control" value="<?php echo date('Y-m-d'); ?>" onchange="updateAllAnalytics()">
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-6">
              <div class="info-display">
                <span class="info-label">Huidige site:</span>
                <span class="info-value" id="currentSiteDisplay">
                  <?php
                  $currentSite = array_filter($sites, function($site) use ($currentSiteId) { return $site['id'] == $currentSiteId; });
                  $currentSite = reset($currentSite);
                  echo htmlspecialchars($currentSite['name'] ?? 'Onbekend');
                  ?>
                </span>
              </div>
            </div>
            <div class="col-md-6 text-md-end">
              <div class="range-actions mb-2">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="setQuickRange('today')">
                  <i class="bi bi-calendar-day"></i> Vandaag
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="setQuickRange('week')">
                  <i class="bi bi-calendar-week"></i> Deze week
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="setQuickRange('month')">
                  <i class="bi bi-calendar-month"></i> Deze maand
                </button>
              </div>
              <div class="range-display">
                <span class="range-label">Geselecteerde periode:</span>
                <span class="range-value" id="dateRangeDisplay">
                  <?php echo date('d-m-Y'); ?> tot <?php echo date('d-m-Y'); ?>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Website Performance Chart - Top with col-12 -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h5><i class="bi bi-graph-up"></i> Website Performance</h5>
        </div>
        <div class="card-body" style="height: 350px; overflow: hidden;">
          <div class="visitorChart" style="height: 100%; position: relative;">
            <canvas id="visitorChart" style="width: 100% !important; height: 100% !important;"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Basis Stats - Smaller cards with col-3 maximum -->
  <div class="row mb-4">
    <div class="col-3 mb-3">
      <div class="card stats-card">
        <div class="card-body text-center">
          <div class="stats-icon">
            <i class="bi bi-people-fill"></i>
          </div>
          <h5 class="card-title">Totaal Bezoekers</h5>
          <div class="stats-number">
            <span class="purecounter" data-purecounter-start="0" data-purecounter-end="<?php echo $stats['totalVisitors']; ?>" data-purecounter-duration="2">0</span>
          </div>
          <div class="stats-subtitle">Unieke: <?php echo $stats['uniqueVisitors']; ?></div>
        </div>
      </div>
    </div>
    
    <div class="col-3 mb-3">
      <div class="card stats-card">
        <div class="card-body text-center">
          <div class="stats-icon">
            <i class="bi bi-eye-fill"></i>
          </div>
          <h5 class="card-title">Page Views</h5>
          <div class="stats-number">
            <span class="purecounter" data-purecounter-start="0" data-purecounter-end="<?php echo $stats['totalPageViews']; ?>" data-purecounter-duration="2">0</span>
          </div>
          <div class="stats-subtitle">Gem. <?php echo $stats['avgPagesPerSession']; ?> per sessie</div>
        </div>
      </div>
    </div>
    
    <div class="col-3 mb-3">
      <div class="card stats-card">
        <div class="card-body text-center">
          <div class="stats-icon">
            <i class="bi bi-clock-fill"></i>
          </div>
          <h5 class="card-title">Sessieduur</h5>
          <div class="stats-number">
            <span class="purecounter" data-purecounter-start="0" data-purecounter-end="<?php echo round($stats['averageDuration']); ?>" data-purecounter-duration="2">0</span>
          </div>
          <div class="stats-subtitle">seconden gemiddeld</div>
        </div>
      </div>
    </div>
    
    <div class="col-3 mb-3">
      <div class="card stats-card">
        <div class="card-body text-center">
          <div class="stats-icon">
            <i class="bi bi-percent"></i>
          </div>
          <h5 class="card-title">Bounce Rate</h5>
          <div class="stats-number">
            <span class="purecounter" data-purecounter-start="0" data-purecounter-end="<?php echo $stats['bounceRate']; ?>" data-purecounter-duration="2" data-purecounter-decimals="1">0</span>%
          </div>
          <div class="stats-subtitle"><?php echo $stats['totalBounces']; ?> bounces</div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Device & Browser Breakdown -->
  <div class="row">
    <div class="col-6 mb-3">
      <div class="card">
        <div class="card-header">
          <h5><i class="bi bi-phone"></i> Device Breakdown</h5>
        </div>
        <div class="card-body">
          <canvas id="deviceChart"></canvas>
          <div id="deviceStats" class="mt-2">
            <!-- Device stats worden hier getoond -->
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-6 mb-3">
      <div class="card">
        <div class="card-header">
          <h5><i class="bi bi-globe"></i> Browser Breakdown</h5>
        </div>
        <div class="card-body">
          <canvas id="browserChart"></canvas>
          <div id="browserStats" class="mt-2">
            <!-- Browser stats worden hier getoond -->
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Top 10 Populairste Pagina's -->
  <div class="row mt-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h5><i class="bi bi-list-ol"></i> Top 10 Populairste Pagina's</h5>
        </div>
        <div class="card-body">
          <div class="table-container">
            <div class="table-responsive" style="height: 350px; overflow-y: auto; max-height: 350px;">
              <table class="table table-hover top-pages-table" style="margin-bottom: 0;">
                <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                  <tr>
                    <th>#</th>
                    <th>Pagina</th>
                    <th>Bezoeken</th>
                    <th>Percentage</th>
                    <th>Trend</th>
                  </tr>
                </thead>
                <tbody id="topPagesTable">
                  <!-- Top pages worden hier geladen -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Loading Indicator -->
<div id="loadingIndicator" style="display: none;">
  <div class="spinner-border text-primary" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
  <div class="mt-2">Data wordt geladen...</div>
</div>

<!-- Voeg PureCounter toe -->
<script src="/assets/vendor/purecounter/purecounter.js"></script>
<script>
  // Initialize PureCounter
  try {
    if (typeof PureCounter !== 'undefined') {
      new PureCounter();
    } else {
      console.warn('PureCounter library not loaded on page load');
    }
  } catch (error) {
    console.warn('PureCounter initialization failed on page load:', error);
  }
  
  // Initialize all analytics components when page loads
  document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, initializing analytics...');
    
    try {
      // Initialize date range display first
      updateDateRangeDisplay();
      
      // Get current date range from inputs
      var startDate = document.getElementById('startDate').value;
      var endDate = document.getElementById('endDate').value;
      
      if (startDate && endDate) {
        console.log('Initializing with date range:', startDate, 'to', endDate);
        
        // Initialize device and browser charts
        generateDeviceChart();
        generateBrowserChart();
        generateTopPagesTable();
        
        // Initialize performance chart
        generateEnhancedChart(startDate, endDate);
      }
    } catch (error) {
      console.error('Error initializing analytics:', error);
    }
  });
  
  // Enhanced chart met meerdere datasets
  function generateEnhancedChart(startDate, endDate) {
    console.log('Loading enhanced chart data for:', startDate, 'to', endDate);
    
    fetch('/admin/bin/get_enhanced_analytics.php', {
      method: 'POST',
      body: JSON.stringify({ startDate: startDate, endDate: endDate }),
      headers: {
        'Content-Type': 'application/json'
      }
    })
    .then(response => {
      console.log('Response status:', response.status);
      if (!response.ok) {
        throw new Error('Network response was not ok: ' + response.status);
      }
      return response.json();
    })
    .then(data => {
      console.log('Received data:', data);
      
      if (data.error) {
        console.error('Server error:', data.error);
        generateFallbackChart(startDate, endDate);
        return;
      }
      
      // Check if we have data
      if (!data.dates || !data.visitors || !data.pageViews || !data.bounces) {
        console.error('Invalid data structure:', data);
        generateFallbackChart(startDate, endDate);
        return;
      }
      
      // Check if we have any data points
      if (data.dates.length === 0) {
        console.log('No data available for the selected date range');
        showNoDataMessage();
        return;
      }
      
      // Destroy existing chart if it exists
      const existingChart = Chart.getChart('visitorChart');
      if (existingChart) {
        existingChart.destroy();
      }
      
      const ctx = document.getElementById('visitorChart').getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: data.dates,
          datasets: [
            {
              label: 'Bezoekers',
              data: data.visitors,
              borderColor: 'rgba(75, 192, 192, 1)',
              backgroundColor: 'rgba(75, 192, 192, 0.1)',
              tension: 0.4
            },
            {
              label: 'Page Views',
              data: data.pageViews,
              borderColor: 'rgba(255, 99, 132, 1)',
              backgroundColor: 'rgba(255, 99, 132, 0.1)',
              tension: 0.4
            },
            {
              label: 'Bounces',
              data: data.bounces,
              borderColor: 'rgba(255, 205, 86, 1)',
              backgroundColor: 'rgba(255, 205, 86, 0.1)',
              tension: 0.4
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: {
            mode: 'index',
            intersect: false,
          },
          plugins: {
            legend: {
              position: 'top',
            },
            title: {
              display: true,
              text: 'Website Performance Over Time'
            }
          },
          scales: {
            y: {
              type: 'linear',
              display: true,
              position: 'left',
            }
          }
        }
      });
    })
    .catch(error => {
      console.error('Error loading chart data:', error);
      // Fallback naar de oude methode als de nieuwe niet werkt
      generateFallbackChart(startDate, endDate);
    });
  }
  
  // Fallback chart functie
  function generateFallbackChart(startDate, endDate) {
    console.log('Loading fallback chart data for:', startDate, 'to', endDate);
    
    fetch('/admin/bin/get_analytics.php', {
      method: 'POST',
      body: JSON.stringify({ startDate: startDate, endDate: endDate }),
      headers: {
        'Content-Type': 'application/json'
      }
    })
    .then(response => {
      console.log('Fallback response status:', response.status);
      if (!response.ok) {
        throw new Error('Fallback network response was not ok: ' + response.status);
      }
      return response.json();
    })
    .then(data => {
      console.log('Fallback data received:', data);
      
      if (data.error) {
        console.error('Fallback server error:', data.error);
        showNoDataMessage();
        return;
      }
      
      // Check if we have data
      if (!data.dates || !data.counts || data.dates.length === 0) {
        console.log('No fallback data available');
        showNoDataMessage();
        return;
      }
      
      // Destroy existing chart if it exists
      const existingChart = Chart.getChart('visitorChart');
      if (existingChart) {
        existingChart.destroy();
      }
      
      const ctx = document.getElementById('visitorChart').getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: data.dates,
          datasets: [{
            label: 'Aantal bezoekers',
            data: data.counts,
            borderColor: 'rgba(75, 192, 192, 1)',
            fill: false,
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          animation: {
            duration: 2000,
            easing: 'easeInOutQuart',
          },
          scales: {
            x: {
              title: {
                display: true,
                text: 'Datum'
              }
            },
            y: {
              title: {
                display: true,
                text: 'Aantal bezoekers'
              }
            }
          }
        }
      });
    })
    .catch(error => {
      console.error('Error loading fallback chart data:', error);
      showNoDataMessage();
    });
  }
  
  // Device chart
  function generateDeviceChart() {
    try {
      const deviceData = <?php echo json_encode($stats['deviceBreakdown']); ?>;
      console.log('Device data:', deviceData);
      
      if (deviceData && deviceData.length > 0) {
        // Destroy existing chart if it exists
        const existingChart = Chart.getChart('deviceChart');
        if (existingChart) {
          existingChart.destroy();
        }
        
        const ctx = document.getElementById('deviceChart').getContext('2d');
        
        // Device-specifieke kleuren en iconen
        const deviceColors = {
          'Desktop': '#49b5e7',
          'Mobile': '#16df7e', 
          'Tablet': '#ffc107'
        };
        
        const deviceIcons = {
          'Desktop': 'bi-laptop',
          'Mobile': 'bi-phone',
          'Tablet': 'bi-tablet'
        };
        
        new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels: deviceData.map(item => item.device),
            datasets: [{
              data: deviceData.map(item => item.count),
              backgroundColor: deviceData.map(item => deviceColors[item.device] || '#6c757d'),
              borderWidth: 3,
              borderColor: '#ffffff'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.2,
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  padding: 20,
                  usePointStyle: true,
                  pointStyle: 'circle'
                }
              },
              tooltip: {
                callbacks: {
                  label: function(context) {
                    const device = context.label;
                    const count = context.parsed;
                    const percentage = deviceData.find(d => d.device === device)?.percentage || 0;
                    return `${device}: ${count} bezoeken (${percentage}%)`;
                  }
                }
              }
            }
          }
        });
        
        // Toon device stats met iconen
        const deviceStats = document.getElementById('deviceStats');
        if (deviceStats) {
          let statsHtml = '<div class="row text-center">';
          deviceData.forEach(item => {
            const icon = deviceIcons[item.device] || 'bi-question-circle';
            const color = deviceColors[item.device] || '#6c757d';
            
            statsHtml += `
              <div class="col-4 mb-2">
                <div class="device-stat-item">
                  <div class="device-icon mb-2">
                    <i class="bi ${icon}" style="color: ${color}; font-size: 1.5rem;"></i>
                  </div>
                  <div class="device-name small text-muted">${item.device}</div>
                  <div class="device-count fw-bold">${item.count}</div>
                  <div class="device-percentage small text-muted">${item.percentage}%</div>
                </div>
              </div>
            `;
          });
          statsHtml += '</div>';
          deviceStats.innerHTML = statsHtml;
        }
      } else {
        console.log('No device data available');
        // Toon placeholder
        const deviceStats = document.getElementById('deviceStats');
        if (deviceStats) {
          deviceStats.innerHTML = '<div class="text-center text-muted">Geen device data beschikbaar</div>';
        }
      }
    } catch (error) {
      console.error('Error generating device chart:', error);
    }
  }
  
  // Browser chart
  let browserChartGenerating = false;
  function generateBrowserChart() {
    if (browserChartGenerating) {
      console.log('generateBrowserChart already running, skipping...');
      return;
    }
    
    try {
      browserChartGenerating = true;
      console.log('generateBrowserChart called');
      const browserData = <?php echo json_encode($stats['browserBreakdown']); ?>;
      console.log('Browser data:', browserData);
      
      // Validate data
      if (!browserData) {
        console.log('No browserData received');
        return;
      }
      
      if (!Array.isArray(browserData)) {
        console.error('browserData is not an array:', typeof browserData);
        return;
      }
      
      if (browserData && browserData.length > 0) {
        // Destroy existing chart if it exists
        const existingChart = Chart.getChart('browserChart');
        if (existingChart) {
          existingChart.destroy();
        }
        
        const ctx = document.getElementById('browserChart').getContext('2d');
        
        // Browser-specifieke kleuren en iconen
        const browserColors = {
          'Chrome': '#4285f4',
          'Firefox': '#ff7139',
          'Safari': '#1b88ca',
          'Edge': '#0078d4',
          'Opera': '#ff1b2d',
          'Internet Explorer': '#1ebbee'
        };
        
        const browserIcons = {
          'Chrome': 'bi-google',
          'Firefox': 'bi-browser-firefox',
          'Safari': 'bi-apple',
          'Edge': 'bi-microsoft',
          'Opera': 'bi-browser-chrome',
          'Internet Explorer': 'bi-browser-edge'
        };
        
        new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels: browserData.map(item => item.browser),
            datasets: [{
              data: browserData.map(item => item.count),
              backgroundColor: browserData.map(item => browserColors[item.browser] || '#6c757d'),
              borderWidth: 3,
              borderColor: '#ffffff'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.2,
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  padding: 20,
                  usePointStyle: true,
                  pointStyle: 'circle'
                }
              },
              tooltip: {
                callbacks: {
                  label: function(context) {
                    const browser = context.label;
                    const count = context.parsed;
                    const percentage = browserData.find(d => d.browser === browser)?.percentage || 0;
                    return `${browser}: ${count} bezoeken (${percentage}%)`;
                  }
                }
              }
            }
          }
        });
        
        // Toon browser stats met iconen
        const browserStats = document.getElementById('browserStats');
        if (browserStats) {
          let statsHtml = '<div class="row text-center">';
          browserData.forEach(item => {
            const icon = browserIcons[item.browser] || 'bi-browser-chrome';
            const color = browserColors[item.browser] || '#6c757d';
            
            statsHtml += `
              <div class="col-4 mb-2">
                <div class="browser-stat-item">
                  <div class="browser-icon mb-2">
                    <i class="bi ${icon}" style="color: ${color}; font-size: 1.5rem;"></i>
                  </div>
                  <div class="browser-name small text-muted">${item.browser}</div>
                  <div class="browser-count fw-bold">${item.count}</div>
                  <div class="browser-percentage small text-muted">${item.percentage}%</div>
                </div>
              </div>
            `;
          });
          statsHtml += '</div>';
          browserStats.innerHTML = statsHtml;
        }
      } else {
        console.log('No browser data available');
        // Toon placeholder
        const browserStats = document.getElementById('browserStats');
        if (browserStats) {
          browserStats.innerHTML = '<div class="text-center text-muted">Geen browser data beschikbaar</div>';
        }
      }
    } catch (error) {
      console.error('Error generating browser chart:', error);
    } finally {
      browserChartGenerating = false;
    }
  }
  
  // Top Pages Table
  function generateTopPagesTable() {
    try {
      const topPagesData = <?php echo json_encode($stats['topPages'] ?? []); ?>;
      console.log('Top pages data:', topPagesData);
      
      const topPagesTable = document.getElementById('topPagesTable');
      if (topPagesTable && topPagesData && topPagesData.length > 0) {
        let tableHtml = '';
        
        topPagesData.forEach((page, index) => {
          const rank = index + 1;
          const pageUrl = page.page_url || 'Onbekend';
          const visitCount = page.count || 0;
          const percentage = page.percentage || 0;
          
          // Bepaal trend icon (simpele implementatie)
          let trendIcon = '<i class="bi bi-dash text-muted"></i>';
          if (rank <= 3) {
            trendIcon = '<i class="bi bi-arrow-up text-success"></i>';
          } else if (rank <= 7) {
            trendIcon = '<i class="bi bi-arrow-up-right text-warning"></i>';
          }
          
          tableHtml += `
            <tr>
              <td><span class="badge bg-primary">${rank}</span></td>
              <td>
                <div class="d-flex align-items-center">
                  <i class="bi bi-file-earmark-text me-2"></i>
                  <span class="text-truncate" title="${pageUrl}">${pageUrl}</span>
                </div>
              </td>
              <td><strong>${visitCount}</strong></td>
              <td><span class="badge bg-info">${percentage}%</span></td>
              <td>${trendIcon}</td>
            </tr>
          `;
        });
        
        topPagesTable.innerHTML = tableHtml;
      } else {
        console.log('No top pages data available');
        if (topPagesTable) {
          topPagesTable.innerHTML = `
            <tr>
              <td colspan="5" class="text-center text-muted">
                <i class="bi bi-info-circle me-2"></i>
                Geen pagina data beschikbaar voor de geselecteerde periode
              </td>
            </tr>
          `;
        }
      }
    } catch (error) {
      console.error('Error generating top pages table:', error);
      const topPagesTable = document.getElementById('topPagesTable');
      if (topPagesTable) {
        topPagesTable.innerHTML = `
          <tr>
            <td colspan="5" class="text-center text-danger">
              <i class="bi bi-exclamation-triangle me-2"></i>
              Fout bij laden van pagina data
            </td>
          </tr>
        `;
      }
    }
  }
  

  
  // Functie om van site te wisselen
  function changeSite() {
    try {
      var siteId = document.getElementById('siteSelector').value;
      var currentUrl = new URL(window.location);
      currentUrl.searchParams.set('site_id', siteId);
      window.location.href = currentUrl.toString();
    } catch (error) {
      console.error('Error changing site:', error);
      alert('Fout bij wisselen van site: ' + error.message);
    }
  }
  
  // Functie om alle analytics data bij te werken wanneer de datumkiezers worden gewijzigd
  function updateAllAnalytics() {
    try {
      var startDate = document.getElementById('startDate').value;
      var endDate = document.getElementById('endDate').value;

      // Valideer datums
      if (!startDate || !endDate) {
        console.error('Start en eind datum zijn verplicht');
        return;
      }

      // Controleer of start datum voor eind datum is
      if (startDate > endDate) {
        console.error('Start datum moet voor eind datum zijn');
        alert('Start datum moet voor eind datum zijn');
        return;
      }

      console.log('Updating analytics for date range:', startDate, 'to', endDate);

      // Toon loading state
      showLoadingState();

      // Haal alle analytics data op voor de geselecteerde datum range en site
      var siteId = document.getElementById('siteSelector').value;
      fetch('/admin/bin/get_all_analytics.php', {
        method: 'POST',
        body: JSON.stringify({ startDate: startDate, endDate: endDate, siteId: siteId }),
        headers: {
          'Content-Type': 'application/json'
        }
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
      })
      .then(data => {
        console.log('All analytics data received:', data);
        
        if (data.success) {
          // Update stat cards
          updateStatCards(data.stats);
          
          // Update charts
          updateDeviceChart(data.deviceBreakdown);
          updateBrowserChart(data.browserBreakdown);
          updateTopPagesTable(data.topPages);
          
          // Update main performance chart
          updatePerformanceChart(startDate, endDate);
          
          console.log('All analytics components updated successfully');
        } else {
          console.error('Error fetching analytics data:', data.error);
          alert('Fout bij ophalen van analytics data: ' + (data.error || 'Onbekende fout'));
        }
      })
      .catch(error => {
        console.error('Error updating analytics:', error);
        alert('Fout bij bijwerken van analytics: ' + error.message);
      })
      .finally(() => {
        hideLoadingState();
      });
    } catch (error) {
      console.error('Error in updateAllAnalytics:', error);
      alert('Fout in updateAllAnalytics: ' + error.message);
      hideLoadingState();
    }
  }

  // Update stat cards met nieuwe data
  function updateStatCards(stats) {
    try {
      // Update totaal bezoekers
      const totalVisitorsElement = document.querySelector('[data-purecounter-end]');
      if (totalVisitorsElement) {
        totalVisitorsElement.setAttribute('data-purecounter-end', stats.totalVisitors || 0);
        totalVisitorsElement.textContent = '0';
      }
      
      // Update unique visitors
      const uniqueVisitorsElement = document.querySelector('.stats-subtitle');
      if (uniqueVisitorsElement) {
        uniqueVisitorsElement.textContent = 'Unieke: ' + (stats.uniqueVisitors || 0);
      }
      
      // Update page views
      const pageViewsElement = document.querySelectorAll('[data-purecounter-end]')[1];
      if (pageViewsElement) {
        pageViewsElement.setAttribute('data-purecounter-end', stats.totalPageViews || 0);
        pageViewsElement.textContent = '0';
      }
      
      // Update avg pages per session
      const avgPagesElement = document.querySelectorAll('.stats-subtitle')[1];
      if (avgPagesElement) {
        avgPagesElement.textContent = 'Gem. ' + (stats.avgPagesPerSession || 0) + ' per sessie';
      }
      
      // Update session duration
      const durationElement = document.querySelectorAll('[data-purecounter-end]')[2];
      if (durationElement) {
        durationElement.setAttribute('data-purecounter-end', Math.round(stats.averageDuration || 0));
        durationElement.textContent = '0';
      }
      
      // Update bounce rate
      const bounceRateElement = document.querySelectorAll('[data-purecounter-end]')[3];
      if (bounceRateElement) {
        bounceRateElement.setAttribute('data-purecounter-end', stats.bounceRate || 0);
        bounceRateElement.textContent = '0';
      }
      
      // Update bounce count
      const bounceCountElement = document.querySelectorAll('.stats-subtitle')[3];
      if (bounceCountElement) {
        bounceCountElement.textContent = (stats.totalBounces || 0) + ' bounces';
      }
      
      // Reinitialize PureCounter with error handling
      if (typeof PureCounter !== 'undefined') {
        try {
          new PureCounter();
        } catch (pureCounterError) {
          console.warn('PureCounter initialization failed:', pureCounterError);
          // Fallback: manually update the counter values
          updateCounterValues();
        }
      } else {
        console.warn('PureCounter library not loaded, using fallback counter update');
        // Fallback: manually update the counter values
        updateCounterValues();
      }
    } catch (error) {
      console.error('Error updating stat cards:', error);
      // Fallback: manually update the counter values
      updateCounterValues();
    }
  }
  
  // Fallback function to manually update counter values
  function updateCounterValues() {
    const counterElements = document.querySelectorAll('[data-purecounter-end]');
    counterElements.forEach(element => {
      const endValue = element.getAttribute('data-purecounter-end');
      if (endValue) {
        element.textContent = endValue;
      }
    });
  }

  // Update device chart met nieuwe data
  function updateDeviceChart(deviceData) {
    try {
      // Destroy existing chart
      const existingChart = Chart.getChart('deviceChart');
      if (existingChart) {
        existingChart.destroy();
      }
      
      if (deviceData && deviceData.length > 0) {
        const ctx = document.getElementById('deviceChart').getContext('2d');
        
        // Device-specifieke kleuren en iconen
        const deviceColors = {
          'Desktop': '#49b5e7',
          'Mobile': '#16df7e', 
          'Tablet': '#ffc107'
        };
        
        const deviceIcons = {
          'Desktop': 'bi-laptop',
          'Mobile': 'bi-phone',
          'Tablet': 'bi-tablet'
        };
        
        new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels: deviceData.map(item => item.device),
            datasets: [{
              data: deviceData.map(item => item.count),
              backgroundColor: deviceData.map(item => deviceColors[item.device] || '#6c757d'),
              borderWidth: 3,
              borderColor: '#ffffff'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.2,
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  padding: 20,
                  usePointStyle: true,
                  pointStyle: 'circle'
                }
              },
              tooltip: {
                callbacks: {
                  label: function(context) {
                    const device = context.label;
                    const count = context.parsed;
                    const percentage = deviceData.find(d => d.device === device)?.percentage || 0;
                    return `${device}: ${count} bezoeken (${percentage}%)`;
                  }
                }
              }
            }
          }
        });
        
        // Update device stats
        const deviceStats = document.getElementById('deviceStats');
        if (deviceStats) {
          let statsHtml = '<div class="row text-center">';
          deviceData.forEach(item => {
            const icon = deviceIcons[item.device] || 'bi-question-circle';
            const color = deviceColors[item.device] || '#6c757d';
            
            statsHtml += `
              <div class="col-4 mb-2">
                <div class="device-stat-item">
                  <div class="device-icon mb-2">
                    <i class="bi ${icon}" style="color: ${color}; font-size: 1.5rem;"></i>
                  </div>
                  <div class="device-name small text-muted">${item.device}</div>
                  <div class="device-count fw-bold">${item.count}</div>
                  <div class="device-percentage small text-muted">${item.percentage}%</div>
                </div>
              </div>
            `;
          });
          statsHtml += '</div>';
          deviceStats.innerHTML = statsHtml;
        }
      } else {
        const deviceStats = document.getElementById('deviceStats');
        if (deviceStats) {
          deviceStats.innerHTML = '<div class="text-center text-muted">Geen device data beschikbaar</div>';
        }
      }
    } catch (error) {
      console.error('Error updating device chart:', error);
    }
  }

  // Update browser chart met nieuwe data
  function updateBrowserChart(browserData) {
    try {
      // Destroy existing chart
      const existingChart = Chart.getChart('browserChart');
      if (existingChart) {
        existingChart.destroy();
      }
      
      if (browserData && browserData.length > 0) {
        const ctx = document.getElementById('browserChart').getContext('2d');
        
        // Browser-specifieke kleuren en iconen
        const browserColors = {
          'Chrome': '#4285f4',
          'Firefox': '#ff7139',
          'Safari': '#1b88ca',
          'Edge': '#0078d4',
          'Opera': '#ff1b2d',
          'Internet Explorer': '#1ebbee'
        };
        
        const browserIcons = {
          'Chrome': 'bi-google',
          'Firefox': 'bi-browser-firefox',
          'Safari': 'bi-apple',
          'Edge': 'bi-microsoft',
          'Opera': 'bi-browser-chrome',
          'Internet Explorer': 'bi-browser-edge'
        };
        
        new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels: browserData.map(item => item.browser),
            datasets: [{
              data: browserData.map(item => item.count),
              backgroundColor: browserData.map(item => browserColors[item.browser] || '#6c757d'),
              borderWidth: 3,
              borderColor: '#ffffff'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.2,
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  padding: 20,
                  usePointStyle: true,
                  pointStyle: 'circle'
                }
              },
              tooltip: {
                callbacks: {
                  label: function(context) {
                    const browser = context.label;
                    const count = context.parsed;
                    const percentage = browserData.find(d => d.browser === browser)?.percentage || 0;
                    return `${browser}: ${count} bezoeken (${percentage}%)`;
                  }
                }
              }
            }
          }
        });
        
        // Update browser stats met iconen
        const browserStats = document.getElementById('browserStats');
        if (browserStats) {
          let statsHtml = '<div class="row text-center">';
          browserData.forEach(item => {
            const icon = browserIcons[item.browser] || 'bi-browser-chrome';
            const color = browserColors[item.browser] || '#6c757d';
            
            statsHtml += `
              <div class="col-4 mb-2">
                <div class="browser-stat-item">
                  <div class="browser-icon mb-2">
                    <i class="bi ${icon}" style="color: ${color}; font-size: 1.5rem;"></i>
                  </div>
                  <div class="browser-name small text-muted">${item.browser}</div>
                  <div class="browser-count fw-bold">${item.count}</div>
                  <div class="browser-percentage small text-muted">${item.percentage}%</div>
                </div>
              </div>
            `;
          });
          statsHtml += '</div>';
          browserStats.innerHTML = statsHtml;
        }
      } else {
        const browserStats = document.getElementById('browserStats');
        if (browserStats) {
          browserStats.innerHTML = '<div class="text-center text-muted">Geen browser data beschikbaar</div>';
        }
      }
    } catch (error) {
      console.error('Error updating browser chart:', error);
    }
  }

  // Update top pages table met nieuwe data
  function updateTopPagesTable(topPagesData) {
    try {
      const topPagesTable = document.getElementById('topPagesTable');
      if (topPagesTable && topPagesData && topPagesData.length > 0) {
        let tableHtml = '';
        
        topPagesData.forEach((page, index) => {
          const rank = index + 1;
          const pageUrl = page.page_url || 'Onbekend';
          const visitCount = page.count || 0;
          const percentage = page.percentage || 0;
          
          // Bepaal trend icon (simpele implementatie)
          let trendIcon = '<i class="bi bi-dash text-muted"></i>';
          if (rank <= 3) {
            trendIcon = '<i class="bi bi-arrow-up text-success"></i>';
          } else if (rank <= 7) {
            trendIcon = '<i class="bi bi-arrow-up-right text-warning"></i>';
          }
          
          tableHtml += `
            <tr>
              <td><span class="badge bg-primary">${rank}</span></td>
              <td>
                <div class="d-flex align-items-center">
                  <i class="bi bi-file-earmark-text me-2"></i>
                  <span class="text-truncate" title="${pageUrl}">${pageUrl}</span>
                </div>
              </td>
              <td><strong>${visitCount}</strong></td>
              <td><span class="badge bg-info">${percentage}%</span></td>
              <td>${trendIcon}</td>
            </tr>
          `;
        });
        
        topPagesTable.innerHTML = tableHtml;
      } else {
        if (topPagesTable) {
          topPagesTable.innerHTML = `
            <tr>
              <td colspan="5" class="text-center text-muted">
                <i class="bi bi-info-circle me-2"></i>
                Geen pagina data beschikbaar voor de geselecteerde periode
              </td>
            </tr>
          `;
        }
      }
    } catch (error) {
      console.error('Error updating top pages table:', error);
      const topPagesTable = document.getElementById('topPagesTable');
      if (topPagesTable) {
        topPagesTable.innerHTML = `
          <tr>
            <td colspan="5" class="text-center text-danger">
              <i class="bi bi-exclamation-triangle me-2"></i>
              Fout bij laden van pagina data
            </td>
          </tr>
        `;
      }
    }
  }

  // Update performance chart
  function updatePerformanceChart(startDate, endDate) {
    try {
      // Verwijder de oude grafiek door het canvas-element te vervangen
      var canvas = document.getElementById('visitorChart');
      if (canvas) {
        canvas.parentNode.removeChild(canvas);
      }

      var newCanvas = document.createElement('canvas');
      newCanvas.id = 'visitorChart';
      newCanvas.style.height = '350px';
      document.querySelector('.visitorChart').appendChild(newCanvas);

      generateEnhancedChart(startDate, endDate);
    } catch (error) {
      console.error('Error updating performance chart:', error);
    }
  }

  // Loading state functies
  function showLoadingState() {
    // Voeg loading indicator toe
    const loadingDiv = document.getElementById('loadingIndicator');
    if (loadingDiv) {
      loadingDiv.style.display = 'block';
    }
  }

  function hideLoadingState() {
    const loadingDiv = document.getElementById('loadingIndicator');
    if (loadingDiv) {
      loadingDiv.style.display = 'none';
    }
  }

  // Function to show no data message
  function showNoDataMessage() {
    const canvas = document.getElementById('visitorChart');
    if (canvas) {
      const ctx = canvas.getContext('2d');
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      
      // Draw no data message
      ctx.fillStyle = '#666';
      ctx.font = '16px Arial';
      ctx.textAlign = 'center';
      ctx.fillText('Geen data beschikbaar voor de geselecteerde periode', canvas.width / 2, canvas.height / 2);
    }
  }

  // Functie om de grafiek bij te werken wanneer de datumkiezers worden gewijzigd
  function updateChart() {
    updateAllAnalytics();
  }
   
   // Quick date range functions
   function setQuickRange(range) {
     const today = new Date();
     let startDate, endDate;
     
     switch(range) {
       case 'today':
         startDate = today.toISOString().split('T')[0];
         endDate = today.toISOString().split('T')[0];
         break;
       case 'week':
         const startOfWeek = new Date(today);
         startOfWeek.setDate(today.getDate() - today.getDay());
         startDate = startOfWeek.toISOString().split('T')[0];
         endDate = today.toISOString().split('T')[0];
         break;
       case 'month':
         startDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
         endDate = today.toISOString().split('T')[0];
         break;
       default:
         return;
     }
     
     document.getElementById('startDate').value = startDate;
     document.getElementById('endDate').value = endDate;
     updateDateRangeDisplay();
     updateAllAnalytics();
   }
   
   // Update date range display
   function updateDateRangeDisplay() {
     const startDate = document.getElementById('startDate').value;
     const endDate = document.getElementById('endDate').value;
     
     if (startDate && endDate) {
       const startFormatted = new Date(startDate).toLocaleDateString('nl-NL');
       const endFormatted = new Date(endDate).toLocaleDateString('nl-NL');
       document.getElementById('dateRangeDisplay').textContent = `${startFormatted} tot ${endFormatted}`;
     }
   }
   

   
   // Update display when dates change
   document.getElementById('startDate').addEventListener('change', updateDateRangeDisplay);
   document.getElementById('endDate').addEventListener('change', updateDateRangeDisplay);
</script>
