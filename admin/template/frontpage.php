<?php 
// Get all available sites
$sites = $analytics->getAllSites();
$currentSiteId = isset($_GET['site_id']) ? (int)$_GET['site_id'] : 1;
$stats = $analytics->getEnhancedStats(null, null, $currentSiteId); 
?>



<div class="container-fluid px-4 analytics-dashboard-container">
  <!-- Filters: Site & Datum Selectie -->
  <div class="row mb-4">
    <div class="col-lg-4 col-md-6">
      <label for="siteSelector" class="form-label">
        <i class="bi bi-building"></i> Selecteer Website
      </label>
      <select id="siteSelector" name="site_id" class="form-select" onchange="changeSite()">
        <?php foreach ($sites as $site): ?>
          <option value="<?php echo $site['id']; ?>" <?php echo ($site['id'] == $currentSiteId) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($site['name']); ?>
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
  
  <!-- Quick Actions & Date Range Display -->
  <div class="row mb-4">
    <div class="col-md-12 text-md-end">
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
        <span id="dateRangeDisplay">
          Geselecteerde periode: <?php echo date('d-m-Y'); ?> tot <?php echo date('d-m-Y'); ?>
        </span>
      </div>
    </div>
  </div>

  <!-- Website Performance Chart - Top with col-12 -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5><i class="bi bi-graph-up"></i> Website Performance</h5>
          <button class="btn btn-sm btn-outline-secondary collapse-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#performanceCard" aria-expanded="true">
            <i class="bi bi-chevron-up"></i>
          </button>
        </div>
        <div id="performanceCard" class="collapse show">
          <div class="card-body chart-container">
            <div class="visitorChart chart-wrapper">
              <canvas id="visitorChart" class="chart-canvas"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Basis Stats - Smaller cards with col-3 maximum -->
  <div class="row mb-4">
    <div class="col-3 mb-3">
      <div class="card stats-card">
        <div class="card-header d-flex justify-content-between align-items-center p-2">
          <h6 class="mb-0"><i class="bi bi-people-fill me-1"></i> Bezoekers</h6>
          <button class="btn btn-sm collapse-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#visitorsCard" aria-expanded="true">
            <i class="bi bi-chevron-up"></i>
          </button>
        </div>
        <div id="visitorsCard" class="collapse show">
          <div class="card-body text-center">
            <div class="stats-number">
              <span class="purecounter" data-purecounter-start="0" data-purecounter-end="<?php echo $stats['totalVisitors']; ?>" data-purecounter-duration="2">0</span>
            </div>
            <div class="stats-subtitle">Unieke: <?php echo $stats['uniqueVisitors']; ?></div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-3 mb-3">
      <div class="card stats-card">
        <div class="card-header d-flex justify-content-between align-items-center p-2">
          <h6 class="mb-0"><i class="bi bi-eye-fill me-1"></i> Paginaweergaven</h6>
          <button class="btn btn-sm collapse-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#pageViewsCard" aria-expanded="true">
            <i class="bi bi-chevron-up"></i>
          </button>
        </div>
        <div id="pageViewsCard" class="collapse show">
          <div class="card-body text-center">
            <div class="stats-number">
              <span class="purecounter" data-purecounter-start="0" data-purecounter-end="<?php echo $stats['totalPageViews']; ?>" data-purecounter-duration="2">0</span>
            </div>
            <div class="stats-subtitle">Gem. <?php echo $stats['avgPagesPerSession']; ?> per sessie</div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-3 mb-3">
      <div class="card stats-card">
        <div class="card-header d-flex justify-content-between align-items-center p-2">
          <h6 class="mb-0"><i class="bi bi-clock-fill me-1"></i> Sessieduur</h6>
          <button class="btn btn-sm collapse-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#sessionDurationCard" aria-expanded="true">
            <i class="bi bi-chevron-up"></i>
          </button>
        </div>
        <div id="sessionDurationCard" class="collapse show">
          <div class="card-body text-center">
            <div class="stats-number">
              <span class="purecounter" data-purecounter-start="0" data-purecounter-end="<?php echo round($stats['averageDuration']); ?>" data-purecounter-duration="2">0</span>
            </div>
            <div class="stats-subtitle">seconden gemiddeld</div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-3 mb-3">
      <div class="card stats-card">
        <div class="card-header d-flex justify-content-between align-items-center p-2">
          <h6 class="mb-0"><i class="bi bi-percent me-1"></i> Bounce Rate</h6>
          <button class="btn btn-sm collapse-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#bounceRateCard" aria-expanded="true">
            <i class="bi bi-chevron-up"></i>
          </button>
        </div>
        <div id="bounceRateCard" class="collapse show">
          <div class="card-body text-center">
            <div class="stats-number">
              <span class="purecounter" data-purecounter-start="0" data-purecounter-end="<?php echo $stats['bounceRate']; ?>" data-purecounter-duration="2" data-purecounter-decimals="1">0</span>%
            </div>
            <div class="stats-subtitle"><?php echo $stats['totalBounces']; ?> bounces</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Apparaten overzicht & Browserstatistieken -->
  <div class="row">
    <div class="col-6 mb-3">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5><i class="bi bi-phone"></i> Apparaten overzicht</h5>
          <button class="btn btn-sm btn-outline-secondary collapse-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#deviceCard" aria-expanded="true">
            <i class="bi bi-chevron-up"></i>
          </button>
        </div>
        <div id="deviceCard" class="collapse show">
          <div class="card-body">
            <canvas id="deviceChart"></canvas>
            <div id="deviceStats" class="mt-2">
              <!-- Device stats worden hier getoond -->
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-6 mb-3">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5><i class="bi bi-globe"></i> Browserstatistieken</h5>
          <button class="btn btn-sm btn-outline-secondary collapse-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#browserCard" aria-expanded="true">
            <i class="bi bi-chevron-up"></i>
          </button>
        </div>
        <div id="browserCard" class="collapse show">
          <div class="card-body">
            <canvas id="browserChart"></canvas>
            <div id="browserStats" class="mt-2">
              <!-- Browser stats worden hier getoond -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Referral Bronnen & Top 10 Populairste Pagina's -->
  <div class="row mb-4">
    <!-- Referral Bronnen -->
    <div class="col-md-6 mb-3">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5><i class="bi bi-link-45deg"></i> Referral Bronnen</h5>
          <button class="btn btn-sm btn-outline-secondary collapse-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#referralSourcesCard" aria-expanded="true">
            <i class="bi bi-chevron-up"></i>
          </button>
        </div>
        <div id="referralSourcesCard" class="collapse show">
          <div class="card-body">
            <div class="table-responsive search-keywords-table-container">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>Referral Bron</th>
                    <th>Bezoeken</th>
                    <th>%</th>
                  </tr>
                </thead>
                <tbody id="referralSourcesTable">
                  <!-- Referral sources worden hier geladen -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Top 10 Populairste Pagina's -->
    <div class="col-md-6 mb-3">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5><i class="bi bi-list-ol"></i> Top 10 Populairste Pagina's</h5>
          <button class="btn btn-sm btn-outline-secondary collapse-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#topPagesCard" aria-expanded="true">
            <i class="bi bi-chevron-up"></i>
          </button>
        </div>
        <div id="topPagesCard" class="collapse show">
          <div class="card-body">
            <div class="table-container-compact">
              <div class="table-responsive top-pages-table-container-compact">
                <table class="table table-hover table-sm top-pages-table-compact">
                  <thead class="sticky-header">
                    <tr>
                      <th class="text-truncate">Pagina</th>
                      <th class="text-center" style="width: 80px;">Hits</th>
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
</div>

<!-- Loading Indicator -->
<div id="loadingIndicator" class="loading-hidden">
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
        generateReferralSourcesTable();
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
    
    fetch('bin/get_enhanced_analytics.php', {
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
    
    fetch('bin/get_analytics.php', {
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
      const deviceData = <?php echo json_encode($stats['deviceBreakdown'] ?? []); ?>;
      console.log('generateDeviceChart - Initial device data:', deviceData);
      console.log('generateDeviceChart - Data type:', typeof deviceData);
      console.log('generateDeviceChart - Is array:', Array.isArray(deviceData));
      
      // Validate data
      if (!deviceData) {
        console.log('generateDeviceChart - No deviceData received');
        return;
      }
      
      if (!Array.isArray(deviceData)) {
        console.error('generateDeviceChart - deviceData is not an array:', typeof deviceData);
        return;
      }
      
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
            const icon = browserIcons[item.browser] || 'bi-globe';
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
  
  // Referral Sources Table
  function generateReferralSourcesTable() {
    try {
      const referralSourcesData = <?php echo json_encode($stats['referralSources'] ?? []); ?>;
      console.log('Referral sources data:', referralSourcesData);
      
      const referralSourcesTable = document.getElementById('referralSourcesTable');
      if (referralSourcesTable && referralSourcesData && referralSourcesData.length > 0) {
        let tableHtml = '';
        
        referralSourcesData.forEach(source => {
          const sourceName = source.source || 'Onbekend';
          const count = source.count || 0;
          const percentage = source.percentage || 0;
          
          // Bepaal icon op basis van source
          let icon = 'bi-link-45deg';
          let iconColor = '#6c757d';
          
          if (sourceName.includes('google')) {
            icon = 'bi-google';
            iconColor = '#4285f4';
          } else if (sourceName.includes('facebook')) {
            icon = 'bi-facebook';
            iconColor = '#1877f2';
          } else if (sourceName.includes('twitter')) {
            icon = 'bi-twitter';
            iconColor = '#1da1f2';
          } else if (sourceName.includes('linkedin')) {
            icon = 'bi-linkedin';
            iconColor = '#0077b5';
          } else if (sourceName.includes('instagram')) {
            icon = 'bi-instagram';
            iconColor = '#e4405f';
          } else if (sourceName === 'Direct bezoek') {
            icon = 'bi-person-fill';
            iconColor = '#28a745';
          }
          
          tableHtml += `
            <tr>
              <td>
                <i class="bi ${icon} me-2" style="color: ${iconColor};"></i>
                ${sourceName}
              </td>
              <td class="text-center"><span class="badge bg-primary">${count}</span></td>
              <td class="text-center"><small class="text-muted">${percentage}%</small></td>
            </tr>
          `;
        });
        
        referralSourcesTable.innerHTML = tableHtml;
      } else {
        console.log('No referral sources data available');
        if (referralSourcesTable) {
          referralSourcesTable.innerHTML = `
            <tr>
              <td colspan="3" class="text-center text-muted">
                <i class="bi bi-info-circle me-2"></i>
                Geen referral data beschikbaar
              </td>
            </tr>
          `;
        }
      }
    } catch (error) {
      console.error('Error generating referral sources table:', error);
      const referralSourcesTable = document.getElementById('referralSourcesTable');
      if (referralSourcesTable) {
        referralSourcesTable.innerHTML = `
          <tr>
            <td colspan="3" class="text-center text-danger">
              <i class="bi bi-exclamation-triangle me-2"></i>
              Fout bij laden van referral data
            </td>
          </tr>
        `;
      }
    }
  }
  
  // Search Keywords Table
  function generateSearchKeywordsTable() {
    try {
      const searchKeywordsData = <?php echo json_encode($stats['topSearchKeywords'] ?? []); ?>;
      console.log('Search keywords data:', searchKeywordsData);
      
      const searchKeywordsTable = document.getElementById('searchKeywordsTable');
      if (searchKeywordsTable && searchKeywordsData && searchKeywordsData.length > 0) {
        let tableHtml = '';
        
        searchKeywordsData.forEach((keyword, index) => {
          const source = keyword.source || 'Onbekend';
          const visitCount = keyword.count || 0;
          const percentage = keyword.percentage || 0;
          
          // Bepaal icon op basis van bron
          let icon = 'bi-search';
          let iconColor = '#6c757d';
          
          if (source.includes('Google')) {
            icon = 'bi-google';
            iconColor = '#4285f4';
          } else if (source.includes('Bing')) {
            icon = 'bi-microsoft';
            iconColor = '#00809d';
          } else if (source.includes('Yahoo')) {
            icon = 'bi-search';
            iconColor = '#7B0099';
          } else if (source.includes('DuckDuckGo')) {
            icon = 'bi-shield-check';
            iconColor = '#de5833';
          } else if (source.includes('Facebook')) {
            icon = 'bi-facebook';
            iconColor = '#1877f2';
          } else if (source.includes('Twitter')) {
            icon = 'bi-twitter';
            iconColor = '#1da1f2';
          } else if (source.includes('LinkedIn')) {
            icon = 'bi-linkedin';
            iconColor = '#0a66c2';
          } else if (source.includes('Direct')) {
            icon = 'bi-arrow-right-circle';
            iconColor = '#198754';
          }
          
          tableHtml += `
            <tr>
              <td>
                <div class="d-flex align-items-center">
                  <i class="bi ${icon} me-2" style="color: ${iconColor};"></i>
                  <span class="text-truncate" title="${source}">${source}</span>
                </div>
              </td>
              <td><strong>${visitCount}</strong></td>
              <td><span class="badge bg-info">${percentage}%</span></td>
            </tr>
          `;
        });
        
        searchKeywordsTable.innerHTML = tableHtml;
      } else {
        console.log('No search keywords data available');
        if (searchKeywordsTable) {
          searchKeywordsTable.innerHTML = `
            <tr>
              <td colspan="3" class="text-center text-muted">
                <i class="bi bi-info-circle me-2"></i>
                Geen zoekwoord data beschikbaar voor de geselecteerde periode
              </td>
            </tr>
          `;
        }
      }
    } catch (error) {
      console.error('Error generating search keywords table:', error);
      const searchKeywordsTable = document.getElementById('searchKeywordsTable');
      if (searchKeywordsTable) {
        searchKeywordsTable.innerHTML = `
          <tr>
            <td colspan="3" class="text-center text-danger">
              <i class="bi bi-exclamation-triangle me-2"></i>
              Fout bij laden van zoekwoord data
            </td>
          </tr>
        `;
      }
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
      fetch('bin/get_all_analytics.php', {
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
          updateReferralSourcesTable(data.referralSources);
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
  let deviceChartUpdating = false;
  function updateDeviceChart(deviceData) {
    if (deviceChartUpdating) {
      console.log('updateDeviceChart already running, skipping...');
      return;
    }
    
    try {
      deviceChartUpdating = true;
      console.log('updateDeviceChart called with data:', deviceData);
      console.log('updateDeviceChart - Data length:', deviceData?.length);
      console.log('updateDeviceChart - First item:', deviceData?.[0]);
      
      // Validate data
      if (!deviceData) {
        console.log('updateDeviceChart - No deviceData received');
        return;
      }
      
      if (!Array.isArray(deviceData)) {
        console.error('updateDeviceChart - deviceData is not an array:', typeof deviceData);
        return;
      }
      
      console.log('updateDeviceChart - About to destroy existing chart');
      // Destroy existing chart
      const existingChart = Chart.getChart('deviceChart');
      if (existingChart) {
        console.log('updateDeviceChart - Destroying existing chart');
        existingChart.destroy();
      } else {
        console.log('updateDeviceChart - No existing chart to destroy');
      }
      
      if (deviceData && deviceData.length > 0) {
        console.log('updateDeviceChart - Data has items, creating chart');
        const chartElement = document.getElementById('deviceChart');
        console.log('updateDeviceChart - Chart element:', chartElement);
        
        if (!chartElement) {
          console.error('updateDeviceChart - deviceChart element not found!');
          return;
        }
        
        const ctx = chartElement.getContext('2d');
        console.log('updateDeviceChart - Got context:', ctx);
        
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
        
        console.log('updateDeviceChart - About to create new Chart');
        const newChart = new Chart(ctx, {
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
        
        console.log('updateDeviceChart - Chart created successfully:', newChart);
        
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
        console.log('updateDeviceChart - No data or empty data, showing no data message');
        const deviceStats = document.getElementById('deviceStats');
        if (deviceStats) {
          deviceStats.innerHTML = '<div class="text-center text-muted">Geen device data beschikbaar</div>';
        }
      }
      
      console.log('updateDeviceChart - Function completed successfully');
    } catch (error) {
      console.error('Error updating device chart:', error);
    } finally {
      console.log('updateDeviceChart - Setting deviceChartUpdating to false');
      deviceChartUpdating = false;
    }
  }

  // Update browser chart met nieuwe data
  let browserChartUpdating = false;
  function updateBrowserChart(browserData) {
    if (browserChartUpdating) {
      console.log('updateBrowserChart already running, skipping...');
      return;
    }
    
    try {
      browserChartUpdating = true;
      console.log('updateBrowserChart called with data:', browserData);
      
      // Validate data
      if (!browserData) {
        console.log('No browserData received');
        return;
      }
      
      if (!Array.isArray(browserData)) {
        console.error('browserData is not an array:', typeof browserData);
        return;
      }
      
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
            const icon = browserIcons[item.browser] || 'bi-globe';
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
    } finally {
      browserChartUpdating = false;
    }
  }

  // Update referral sources table met nieuwe data
  function updateReferralSourcesTable(referralSourcesData) {
    try {
      console.log('updateReferralSourcesTable called with data:', referralSourcesData);
      
      const referralSourcesTable = document.getElementById('referralSourcesTable');
      if (referralSourcesTable && referralSourcesData && referralSourcesData.length > 0) {
        let tableHtml = '';
        
        referralSourcesData.forEach(source => {
          const sourceName = source.source || 'Onbekend';
          const count = source.count || 0;
          const percentage = source.percentage || 0;
          
          // Bepaal icon op basis van source
          let icon = 'bi-link-45deg';
          let iconColor = '#6c757d';
          
          if (sourceName.includes('google')) {
            icon = 'bi-google';
            iconColor = '#4285f4';
          } else if (sourceName.includes('facebook')) {
            icon = 'bi-facebook';
            iconColor = '#1877f2';
          } else if (sourceName.includes('twitter')) {
            icon = 'bi-twitter';
            iconColor = '#1da1f2';
          } else if (sourceName.includes('linkedin')) {
            icon = 'bi-linkedin';
            iconColor = '#0077b5';
          } else if (sourceName.includes('instagram')) {
            icon = 'bi-instagram';
            iconColor = '#e4405f';
          } else if (sourceName === 'Direct bezoek') {
            icon = 'bi-person-fill';
            iconColor = '#28a745';
          }
          
          tableHtml += `
            <tr>
              <td>
                <i class="bi ${icon} me-2" style="color: ${iconColor};"></i>
                ${sourceName}
              </td>
              <td class="text-center"><span class="badge bg-primary">${count}</span></td>
              <td class="text-center"><small class="text-muted">${percentage}%</small></td>
            </tr>
          `;
        });
        
        referralSourcesTable.innerHTML = tableHtml;
      } else {
        if (referralSourcesTable) {
          referralSourcesTable.innerHTML = `
            <tr>
              <td colspan="3" class="text-center text-muted">
                <i class="bi bi-info-circle me-2"></i>
                Geen referral data beschikbaar voor de geselecteerde periode
              </td>
            </tr>
          `;
        }
      }
    } catch (error) {
      console.error('Error updating referral sources table:', error);
      const referralSourcesTable = document.getElementById('referralSourcesTable');
      if (referralSourcesTable) {
        referralSourcesTable.innerHTML = `
          <tr>
            <td colspan="3" class="text-center text-danger">
              <i class="bi bi-exclamation-triangle me-2"></i>
              Fout bij laden van referral data
            </td>
          </tr>
        `;
      }
    }
  }

  // Update search keywords table met nieuwe data
  function updateSearchKeywordsTable(searchKeywordsData) {
    try {
      console.log('updateSearchKeywordsTable called with data:', searchKeywordsData);
      
      const searchKeywordsTable = document.getElementById('searchKeywordsTable');
      if (searchKeywordsTable && searchKeywordsData && searchKeywordsData.length > 0) {
        let tableHtml = '';
        
        searchKeywordsData.forEach((keyword, index) => {
          const source = keyword.source || 'Onbekend';
          const visitCount = keyword.count || 0;
          const percentage = keyword.percentage || 0;
          
          // Bepaal icon op basis van bron
          let icon = 'bi-search';
          let iconColor = '#6c757d';
          
          if (source.includes('Google')) {
            icon = 'bi-google';
            iconColor = '#4285f4';
          } else if (source.includes('Bing')) {
            icon = 'bi-microsoft';
            iconColor = '#00809d';
          } else if (source.includes('Yahoo')) {
            icon = 'bi-search';
            iconColor = '#7B0099';
          } else if (source.includes('DuckDuckGo')) {
            icon = 'bi-shield-check';
            iconColor = '#de5833';
          } else if (source.includes('Facebook')) {
            icon = 'bi-facebook';
            iconColor = '#1877f2';
          } else if (source.includes('Twitter')) {
            icon = 'bi-twitter';
            iconColor = '#1da1f2';
          } else if (source.includes('LinkedIn')) {
            icon = 'bi-linkedin';
            iconColor = '#0a66c2';
          } else if (source.includes('Direct')) {
            icon = 'bi-arrow-right-circle';
            iconColor = '#198754';
          }
          
          tableHtml += `
            <tr>
              <td>
                <div class="d-flex align-items-center">
                  <i class="bi ${icon} me-2" style="color: ${iconColor};"></i>
                  <span class="text-truncate" title="${source}">${source}</span>
                </div>
              </td>
              <td><strong>${visitCount}</strong></td>
              <td><span class="badge bg-info">${percentage}%</span></td>
            </tr>
          `;
        });
        
        searchKeywordsTable.innerHTML = tableHtml;
      } else {
        if (searchKeywordsTable) {
          searchKeywordsTable.innerHTML = `
            <tr>
              <td colspan="3" class="text-center text-muted">
                <i class="bi bi-info-circle me-2"></i>
                Geen zoekwoord data beschikbaar voor de geselecteerde periode
              </td>
            </tr>
          `;
        }
      }
    } catch (error) {
      console.error('Error updating search keywords table:', error);
      const searchKeywordsTable = document.getElementById('searchKeywordsTable');
      if (searchKeywordsTable) {
        searchKeywordsTable.innerHTML = `
          <tr>
            <td colspan="3" class="text-center text-danger">
              <i class="bi bi-exclamation-triangle me-2"></i>
              Fout bij laden van zoekwoord data
            </td>
          </tr>
        `;
      }
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
       document.getElementById('dateRangeDisplay').textContent = `Geselecteerde periode: ${startFormatted} tot ${endFormatted}`;
     }
   }
   

   
   // Update display when dates change
   document.getElementById('startDate').addEventListener('change', updateDateRangeDisplay);
   document.getElementById('endDate').addEventListener('change', updateDateRangeDisplay);
   
   // Card collapse functionality with localStorage
   document.addEventListener('DOMContentLoaded', function() {
     const STORAGE_KEY = 'analytics_card_states';
     
     // Load saved states from localStorage
     function loadCardStates() {
       try {
         const savedStates = localStorage.getItem(STORAGE_KEY);
         return savedStates ? JSON.parse(savedStates) : {};
       } catch (e) {
         console.warn('Could not load card states from localStorage:', e);
         return {};
       }
     }
     
     // Save states to localStorage
     function saveCardStates(states) {
       try {
         localStorage.setItem(STORAGE_KEY, JSON.stringify(states));
       } catch (e) {
         console.warn('Could not save card states to localStorage:', e);
       }
     }
     
     // Get current states of all cards
     function getCurrentStates() {
       const states = {};
       const collapseElements = document.querySelectorAll('[data-bs-toggle="collapse"]');
       
       collapseElements.forEach(function(toggle) {
         const targetId = toggle.getAttribute('data-bs-target').replace('#', '');
         const targetElement = document.getElementById(targetId);
         if (targetElement) {
           states[targetId] = targetElement.classList.contains('show');
         }
       });
       
       return states;
     }
     
     // Apply saved states to cards
     function applyCardStates(states) {
       Object.keys(states).forEach(function(cardId) {
         const cardElement = document.getElementById(cardId);
         const toggle = document.querySelector(`[data-bs-target="#${cardId}"]`);
         
         if (cardElement && toggle) {
           const chevronIcon = toggle.querySelector('i');
           const shouldShow = states[cardId];
           
           if (shouldShow) {
             cardElement.classList.add('show');
             chevronIcon.className = 'bi bi-chevron-up';
             toggle.setAttribute('aria-expanded', 'true');
           } else {
             cardElement.classList.remove('show');
             chevronIcon.className = 'bi bi-chevron-down';
             toggle.setAttribute('aria-expanded', 'false');
           }
         }
       });
     }
     
     // Load and apply saved states
     const savedStates = loadCardStates();
     if (Object.keys(savedStates).length > 0) {
       applyCardStates(savedStates);
     }
     
     // Add event listeners for all collapse toggles
     const collapseToggles = document.querySelectorAll('.collapse-toggle');
     
     collapseToggles.forEach(function(toggle) {
       const targetId = toggle.getAttribute('data-bs-target');
       const targetElement = document.querySelector(targetId);
       const chevronIcon = toggle.querySelector('i');
       
       if (targetElement) {
         // Bootstrap collapse events
         targetElement.addEventListener('shown.bs.collapse', function() {
           chevronIcon.className = 'bi bi-chevron-up';
           toggle.setAttribute('aria-expanded', 'true');
           
           // Save state to localStorage
           const currentStates = getCurrentStates();
           saveCardStates(currentStates);
         });
         
         targetElement.addEventListener('hidden.bs.collapse', function() {
           chevronIcon.className = 'bi bi-chevron-down';
           toggle.setAttribute('aria-expanded', 'false');
           
           // Save state to localStorage
           const currentStates = getCurrentStates();
           saveCardStates(currentStates);
         });
       }
     });
   });
</script>
