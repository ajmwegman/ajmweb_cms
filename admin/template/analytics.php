<?php 
$stats = $analytics->getStats(); 

?>  

<div class="container mt-5">
  <div class="row">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-6 mb-3">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-people-fill"></i> Totaal aantal bezoekers</h5>
              <p class="card-text"><?php echo $stats['totalVisitors']; ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-3">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-eye-fill"></i> Totaal aantal pageviews</h5>
              <p class="card-text"><?php echo $stats['totalPageViews']; ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-3">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-clock-fill"></i> Gemiddelde sessieduur</h5>
              <p class="card-text"><?php echo $stats['averageDuration']; ?> seconden</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-3">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-exclamation-triangle-fill"></i> Totaal aantal bounces</h5>
              <p class="card-text"><?php echo $stats['totalBounces']; ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title"><i class="bi bi-calendar-check"></i> Aantal Bezoekers</h5>
          <div class="row">
            <div class="col-md-6 mb-3">
            <label for="startDate" class="form-label">Begin Datum:</label>
            <input type="date" id="startDate" name="startDate" class="form-control" value="<?php echo date('Y-m-01'); ?>" onchange="updateChart()">
          </div>
          <div class="col-md-6 mb-3">
            <label for="endDate" class="form-label">Eind Datum:</label>
            <input type="date" id="endDate" name="endDate" class="form-control" value="<?php echo date('Y-m-d'); ?>" onchange="updateChart()">
          </div>
              </div>
          <div class="visitorChart">
            <canvas id="visitorChart" style="height: calc(100% - 1rem);" width="400" height="200"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Functie om de grafiek te genereren
  function generateChart(startDate, endDate) {
    // Voer een AJAX-verzoek uit om gegevens op te halen
    fetch('/admin/bin/get_analytics.php', {
      method: 'POST',
      body: JSON.stringify({ startDate: startDate, endDate: endDate }),
      headers: {
        'Content-Type': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      // Gebruik de opgehaalde gegevens voor de grafiek
      var ctx = document.getElementById('visitorChart').getContext('2d');
      var myChart = new Chart(ctx, {
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
    });
  }
// Functie om de grafiek bij te werken wanneer de datumkiezers worden gewijzigd
function updateChart() {
  var startDate = document.getElementById('startDate').value;
  var endDate = document.getElementById('endDate').value;

  // Verwijder de oude grafiek door het canvas-element te vervangen
  var canvas = document.getElementById('visitorChart');
  canvas.parentNode.removeChild(canvas);

  var newCanvas = document.createElement('canvas');
  newCanvas.id = 'visitorChart';
  newCanvas.style.height = 'calc(100% - 1rem)';
  newCanvas.width = 400;
  newCanvas.height = 200;
  document.querySelector('.visitorChart').appendChild(newCanvas);

  generateChart(startDate, endDate);
}


  // Roep de functie direct aan bij het laden van de pagina
  window.addEventListener('DOMContentLoaded', function() {
    var startDate = document.getElementById('startDate').value;
    var endDate = document.getElementById('endDate').value;
    generateChart(startDate, endDate);
  });
</script>
