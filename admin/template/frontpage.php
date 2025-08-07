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
      <div class="row">
        <div class="col-12 mb-3">
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
        
        <div class="col-12">
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
    
    <!-- Top 10 Populairste Pagina's -->
    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h5><i class="bi bi-list-ol"></i> Top 10 Populairste Pagina's</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover top-pages-table">
                <thead>
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

<!-- Voeg PureCounter toe -->
<script src="/themes/onepage/assets/vendor/purecounter/purecounter.js"></script>
<script>
  // Initialize PureCounter
  new PureCounter();
  
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
      return response.json();
    })
    .then(data => {
      console.log('Received data:', data);
      
      if (data.error) {
        console.error('Server error:', data.error);
        generateFallbackChart(startDate, endDate);
        return;
      }
      
      if (!data.dates || !data.visitors || !data.pageViews || !data.bounces) {
        console.error('Invalid data structure:', data);
        generateFallbackChart(startDate, endDate);
        return;
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
      return response.json();
    })
    .then(data => {
      console.log('Fallback data received:', data);
      
      if (data.error) {
        console.error('Fallback server error:', data.error);
        return;
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
    });
  }
  
  // Device chart
  function generateDeviceChart() {
    try {
      const deviceData = <?php echo json_encode($stats['deviceBreakdown']); ?>;
      console.log('Device data:', deviceData);
      
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
            maintainAspectRatio: false,
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
  function generateBrowserChart() {
    try {
      const browserData = <?php echo json_encode($stats['browserBreakdown']); ?>;
      console.log('Browser data:', browserData);
      
      if (browserData && browserData.length > 0) {
        const ctx = document.getElementById('browserChart').getContext('2d');
        new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels: browserData.map(item => item.browser),
            datasets: [{
              data: browserData.map(item => item.count),
              backgroundColor: ['#49b5e7', '#16df7e', '#ffc107', '#dc3545', '#6f42c1'],
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
        
        // Toon browser stats
        const browserStats = document.getElementById('browserStats');
        if (browserStats) {
          let statsHtml = '<div class="row text-center">';
          browserData.forEach(item => {
            statsHtml += `
              <div class="col-6 mb-2">
                <div class="small text-muted">${item.browser}</div>
                <div class="fw-bold">${item.count}</div>
                <div class="small text-muted">${item.percentage}%</div>
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
    }
  }
  
  // Top Pages Table
  function generateTopPagesTable() {
    try {
      const topPagesData = <?php echo json_encode($stats['topPages']); ?>;
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
                Geen pagina data beschikbaar
              </td>
            </tr>
          `;
        }
      }
    } catch (error) {
      console.error('Error generating top pages table:', error);
    }
  }
  
  // Initialize charts
  window.addEventListener('DOMContentLoaded', function() {
    try {
      generateDeviceChart();
      generateBrowserChart();
      generateTopPagesTable();
      var startDate = document.getElementById('startDate').value;
      var endDate = document.getElementById('endDate').value;
      generateEnhancedChart(startDate, endDate);
    } catch (error) {
      console.error('Error initializing charts:', error);
    }
  });
  
  // Functie om de grafiek bij te werken wanneer de datumkiezers worden gewijzigd
  function updateChart() {
    try {
      var startDate = document.getElementById('startDate').value;
      var endDate = document.getElementById('endDate').value;

      // Verwijder de oude grafiek door het canvas-element te vervangen
      var canvas = document.getElementById('visitorChart');
      if (canvas) {
        canvas.parentNode.removeChild(canvas);
      }

      var newCanvas = document.createElement('canvas');
      newCanvas.id = 'visitorChart';
      newCanvas.style.height = '400px';
      document.querySelector('.visitorChart').appendChild(newCanvas);

      generateEnhancedChart(startDate, endDate);
    } catch (error) {
      console.error('Error updating chart:', error);
    }
  }
</script>
