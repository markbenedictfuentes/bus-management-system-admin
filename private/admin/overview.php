<?php
include 'db_connect.php';

// --- Total Complaints (mula sa complaints table) ---
$totalComplaintsQuery = "SELECT COUNT(*) AS total FROM complaints";
$totalComplaintsResult = $conn->query($totalComplaintsQuery);
$totalComplaintsRow = $totalComplaintsResult->fetch_assoc();
$totalComplaints = $totalComplaintsRow['total'];

// --- Recent Incidents (accidents sa huling 24 oras) ---
$recentIncidentsQuery = "SELECT COUNT(*) AS recent FROM accidents WHERE accident_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
$recentIncidentsResult = $conn->query($recentIncidentsQuery);
$recentIncidentsRow = $recentIncidentsResult->fetch_assoc();
$recentIncidents = $recentIncidentsRow['recent'];

// --- Compliance Percentage (mula sa compliance table) ---
$complianceQuery = "SELECT status, COUNT(*) AS count FROM compliance GROUP BY status";
$resultCompliance = $conn->query($complianceQuery);
$compliantCount = 0;
$totalCompliance = 0;
while ($row = $resultCompliance->fetch_assoc()) {
    $totalCompliance += $row['count'];
    if (strtolower($row['status']) == 'compliant') {
        $compliantCount = $row['count'];
    }
}
$compliancePercentage = ($totalCompliance > 0) ? round(($compliantCount / $totalCompliance) * 100) : 100;

// --- Accidents per Month (para sa kasalukuyang taon) ---
$accidentsQuery = "
  SELECT DATE_FORMAT(accident_date, '%M') AS month, COUNT(*) AS count 
  FROM accidents 
  WHERE YEAR(accident_date) = YEAR(CURRENT_DATE())
  GROUP BY MONTH(accident_date)
  ORDER BY MONTH(accident_date)
";
$accidentsResult = $conn->query($accidentsQuery);
$allMonths = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$monthlyAccidents = array_fill_keys($allMonths, 0);
while ($row = $accidentsResult->fetch_assoc()) {
    $monthlyAccidents[$row['month']] = (int)$row['count'];
}
$months = array_keys($monthlyAccidents);
$accidentCounts = array_values($monthlyAccidents);

// --- Accidents per Year (para sa yearly view) ---
$yearlyQuery = "
  SELECT YEAR(accident_date) AS year, COUNT(*) AS count 
  FROM accidents 
  GROUP BY YEAR(accident_date)
  ORDER BY YEAR(accident_date)
";
$yearlyResult = $conn->query($yearlyQuery);
$years = [];
$yearlyCounts = [];
while ($row = $yearlyResult->fetch_assoc()) {
    $years[] = $row['year'];
    $yearlyCounts[] = $row['count'];
}
?>

<div class="card mb-4 shadow-lg border-0 rounded-lg">
  <div class="card-header bg-gradient-primary-to-secondary text-white">
  </div>
  <div class="card-body p-4">    
    <div class="row g-4 text-center mb-5">
      <!-- Total Complaints -->
      <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm rounded-lg hover-elevate">
          <div class="card-body p-4">
            <div class="icon-wrapper mb-3">
              <div class="icon-circle bg-primary-soft">
                <i class="fas fa-bullhorn fa-2x text-primary"></i>
              </div>
            </div>
            <h3 class="h4 fw-bold mb-2">Total Complaints</h3>
            <p class="display-5 fw-bold text-primary mb-0"><?php echo number_format($totalComplaints); ?></p>
            <p class="text-muted small">
              <i class="fas fa-sync-alt me-1"></i> Updated <?php echo date('M d, Y'); ?>
            </p>
          </div>
        </div>
      </div>
      
      <!-- Compliance Status -->
      <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm rounded-lg hover-elevate">
          <div class="card-body p-4">
            <div class="icon-wrapper mb-3">
              <div class="icon-circle bg-success-soft">
                <i class="fas fa-shield-alt fa-2x text-success"></i>
              </div>
            </div>
            <h3 class="h4 fw-bold mb-2">Compliance Rate</h3>
            <p class="display-5 fw-bold text-success mb-0"><?php echo $compliancePercentage; ?>%</p>
            <div class="progress mt-2 mb-1" style="height: 6px;">
              <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $compliancePercentage; ?>%" 
                   aria-valuenow="<?php echo $compliancePercentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <p class="text-muted small"><?php echo $compliantCount; ?> of <?php echo $totalCompliance; ?> compliant</p>
          </div>
        </div>
      </div>
      
      <!-- Recent Incidents -->
      <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm rounded-lg hover-elevate">
          <div class="card-body p-4">
            <div class="icon-wrapper mb-3">
              <div class="icon-circle bg-danger-soft">
                <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
              </div>
            </div>
            <h3 class="h4 fw-bold mb-2">Recent Incidents</h3>
            <p class="display-5 fw-bold text-danger mb-0"><?php echo $recentIncidents; ?></p>
            <p class="text-muted small">
              <i class="far fa-clock me-1"></i> Last 24 hours
            </p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Graph: Accidents as a Line Graph with Period Selector -->
    <div class="mt-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Accident Trends (<?php echo date('Y'); ?>)</h4>
        <div class="btn-group btn-group-sm" role="group" id="periodSelector">
          <button type="button" class="btn btn-outline-primary active">Monthly</button>
          <button type="button" class="btn btn-outline-primary">Quarterly</button>
          <button type="button" class="btn btn-outline-primary">Yearly</button>
        </div>
      </div>
      <div class="chart-container shadow-sm rounded-lg p-3 border" style="position: relative; height:300px;">
        <canvas id="accidentsChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Custom CSS -->
<style>
  .bg-gradient-primary-to-secondary {
    background: linear-gradient(135deg, #4e73df 0%, #36b9cc 100%);
  }
  .bg-primary-soft {
    background-color: rgba(78, 115, 223, 0.1);
  }
  .bg-success-soft {
    background-color: rgba(40, 167, 69, 0.1);
  }
  .bg-danger-soft {
    background-color: rgba(220, 53, 69, 0.1);
  }
  .icon-circle {
    height: 60px;
    width: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
  }
  .hover-elevate {
    transition: all 0.3s ease;
  }
  .hover-elevate:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
  }
  .chart-container {
    background-color: white;
  }
  h2.custom-heading {
    color: black !important;
  }
  .icon-wrapper {
    position: relative;
    z-index: 1;
  }
</style>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Data from PHP
  var monthlyLabels = <?php echo json_encode($months); ?>;
  var monthlyData = <?php echo json_encode($accidentCounts); ?>;
  
  // Compute quarterly data using a month-to-quarter mapping
  var quarterMapping = {
    "January": 0, "February": 0, "March": 0,
    "April": 1, "May": 1, "June": 1,
    "July": 2, "August": 2, "September": 2,
    "October": 3, "November": 3, "December": 3
  };
  var quarterLabels = ["Q1", "Q2", "Q3", "Q4"];
  var quarterlyData = [0, 0, 0, 0];
  for (var i = 0; i < monthlyLabels.length; i++) {
    var q = quarterMapping[monthlyLabels[i]];
    quarterlyData[q] += monthlyData[i];
  }
  
  // Yearly data from PHP
  var yearlyLabels = <?php echo json_encode($years); ?>;
  var yearlyData = <?php echo json_encode($yearlyCounts); ?>;
  
  // Initialize chart with monthly data as default
  var ctx = document.getElementById('accidentsChart').getContext('2d');
  var accidentsChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: monthlyLabels,
      datasets: [{
        label: 'Accidents per Month',
        data: monthlyData,
        fill: false,
        tension: 0.3,
        borderColor: 'rgba(78, 115, 223, 1)',
        backgroundColor: 'rgba(78, 115, 223, 0.8)',
        borderWidth: 2,
        pointRadius: 4,
        pointBackgroundColor: 'rgba(78, 115, 223, 1)',
        pointBorderColor: '#fff',
        pointHoverRadius: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          padding: 15,
          titleFont: { size: 14 },
          bodyFont: { size: 13 },
          displayColors: false
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            precision: 0,
            font: { family: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif", weight: 500 }
          },
          grid: { borderDash: [3, 3], color: "rgba(0, 0, 0, 0.05)" }
        },
        x: {
          grid: { display: false },
          ticks: { font: { family: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif", weight: 500 } }
        }
      },
      animation: { duration: 2000 }
    }
  });
  
  // Period selector buttons functionality
  var buttons = document.querySelectorAll('#periodSelector button');
  buttons.forEach(function(btn) {
    btn.addEventListener('click', function() {
      buttons.forEach(function(b) { b.classList.remove('active'); });
      this.classList.add('active');
      var period = this.textContent.trim().toLowerCase();
      if (period === 'monthly') {
        accidentsChart.data.labels = monthlyLabels;
        accidentsChart.data.datasets[0].data = monthlyData;
        accidentsChart.data.datasets[0].label = 'Accidents per Month';
      } else if (period === 'quarterly') {
        accidentsChart.data.labels = quarterLabels;
        accidentsChart.data.datasets[0].data = quarterlyData;
        accidentsChart.data.datasets[0].label = 'Accidents per Quarter';
      } else if (period === 'yearly') {
        accidentsChart.data.labels = yearlyLabels;
        accidentsChart.data.datasets[0].data = yearlyData;
        accidentsChart.data.datasets[0].label = 'Accidents per Year';
      }
      accidentsChart.update();
    });
  });
  
  // Initialize Bootstrap tooltips if needed
  if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  }
  
  // Add subtle animation to cards
  document.querySelectorAll('.hover-elevate').forEach((card, index) => {
    setTimeout(() => { card.classList.add('animated'); }, index * 100);
  });
});
</script>
