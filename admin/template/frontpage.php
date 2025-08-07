<?php 
$stats = $analytics->getEnhancedStats(); 
?>

<div class="container mt-5">
  <div class="row">
    <!-- Basis Stats -->
    <div class="col-md-8">
      <div class="row">
        <div class="col-md-6 mb-3">
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
        
        <div class="col-md-6 mb-3">
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
        
        <div class="col-md-6 mb-3">
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
        
        <div class="col-md-6 mb-3">
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
    </div>
    
    <!-- Device & Browser Breakdown -->
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h5><i class="bi bi-phone"></i> Device Breakdown</h5>
        </div>
        <div class="card-body">
          <canvas id="deviceChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Enhanced Chart -->
  <div class="row mt-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h5><i class="bi bi-graph-up"></i> Website Performance</h5>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-3">
              <label for="startDate" class="form-label">Begin Datum:</label>
              <input type="date" id="startDate" name="startDate" class="form-control" value="<?php echo date('Y-m-01'); ?>" onchange="updateChart()">
            </div>
            <div class="col-md-3">
              <label for="endDate" class="form-label">Eind Datum:</label>
              <input type="date" id="endDate" name="endDate" class="form-control" value="<?php echo date('Y-m-d'); ?>" onchange="updateChart()">
            </div>
          </div>
          <div class="visitorChart">
            <canvas id="visitorChart" style="height: 400px;"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Voeg PureCounter toe -->
<script src="/themes/onepage/assets/vendor/purecounter/purecounter.js"></script>
<script>
  // Initialize PureCounter
  new PureCounter();
  
  // Enhanced chart met meerdere datasets
  function generateEnhancedChart(startDate, endDate) {
    fetch('/admin/bin/get_enhanced_analytics.php', {
      method: 'POST',
      body: JSON.stringify({ startDate: startDate, endDate: endDate }),
      headers: {
        'Content-Type': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
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
    });
  }
  
  // Device chart
  function generateDeviceChart() {
    const deviceData = <?php echo json_encode($stats['deviceBreakdown']); ?>;
    const ctx = document.getElementById('deviceChart').getContext('2d');
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: deviceData.map(item => item.device),
        datasets: [{
          data: deviceData.map(item => item.count),
          backgroundColor: ['#49b5e7', '#16df7e'],
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    });
  }
  
  // Initialize charts
  window.addEventListener('DOMContentLoaded', function() {
    generateDeviceChart();
    var startDate = document.getElementById('startDate').value;
    var endDate = document.getElementById('endDate').value;
    generateEnhancedChart(startDate, endDate);
  });
  
  // Functie om de grafiek bij te werken wanneer de datumkiezers worden gewijzigd
  function updateChart() {
    var startDate = document.getElementById('startDate').value;
    var endDate = document.getElementById('endDate').value;

    // Verwijder de oude grafiek door het canvas-element te vervangen
    var canvas = document.getElementById('visitorChart');
    canvas.parentNode.removeChild(canvas);

    var newCanvas = document.createElement('canvas');
    newCanvas.id = 'visitorChart';
    newCanvas.style.height = '400px';
    document.querySelector('.visitorChart').appendChild(newCanvas);

    generateEnhancedChart(startDate, endDate);
  }
</script>
