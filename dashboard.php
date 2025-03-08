<?php 
// header.php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'db_connect.php';

// --- Total Complaints ---
$q_total_complaints = "SELECT COUNT(*) AS total FROM complaints";
$result_total_complaints = $conn->query($q_total_complaints);
$total_complaints = $result_total_complaints->fetch_assoc()['total'];

// --- Total Accidents ---
$q_total_accidents = "SELECT COUNT(*) AS total FROM accidents";
$result_total_accidents = $conn->query($q_total_accidents);
$total_accidents = $result_total_accidents->fetch_assoc()['total'];

// --- Compliance Rate ---
$q_compliance = "SELECT status, COUNT(*) AS count FROM compliance GROUP BY status";
$result_compliance = $conn->query($q_compliance);
$total_compliance_records = 0;
$compliant_count = 0;
while ($row = $result_compliance->fetch_assoc()) {
    $total_compliance_records += $row['count'];
    if (strtolower($row['status']) == 'compliant') {
        $compliant_count = $row['count'];
    }
}
$compliance_rate = ($total_compliance_records > 0) ? round(($compliant_count / $total_compliance_records) * 100) : 0;

// --- Total Safety Inspections ---
$q_total_safety = "SELECT COUNT(*) AS total FROM safety";
$result_total_safety = $conn->query($q_total_safety);
$total_safety = $result_total_safety->fetch_assoc()['total'];

// --- Accident Trends per Month (current year) ---
$q_accidents_month = "
  SELECT DATE_FORMAT(accident_date, '%M') AS month, COUNT(*) AS count 
  FROM accidents 
  WHERE YEAR(accident_date) = YEAR(CURRENT_DATE())
  GROUP BY MONTH(accident_date)
  ORDER BY MONTH(accident_date)
";
$result_accidents_month = $conn->query($q_accidents_month);
$all_months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$monthly_accidents = array_fill_keys($all_months, 0);
while ($row = $result_accidents_month->fetch_assoc()) {
    $monthly_accidents[$row['month']] = (int)$row['count'];
}
$months = array_keys($monthly_accidents);
$accident_counts = array_values($monthly_accidents);

// --- Accident Trends per Year ---
$q_accidents_year = "
  SELECT YEAR(accident_date) AS year, COUNT(*) AS count 
  FROM accidents 
  GROUP BY YEAR(accident_date)
  ORDER BY YEAR(accident_date)
";
$result_accidents_year = $conn->query($q_accidents_year);
$years = [];
$yearly_counts = [];
while ($row = $result_accidents_year->fetch_assoc()) {
    $years[] = $row['year'];
    $yearly_counts[] = $row['count'];
}
?>
<?php include 'include/header.php'; ?>



<div class="dashboard" style="padding: 1rem;">
  <!-- Summary Cards -->
  <div class="summary-box" style="display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center;">
    <!-- Total Complaints -->
    <div class="summary-card" style="flex: 1 1 200px;  background: linear-gradient(135deg, #4e73df, #36b9cc); padding: 1.5rem; border-radius: 8px; text-align: center;">
      <div class="summary-number" style="font-size: 2rem; font-weight: bold;"><?php echo number_format($total_complaints); ?></div>
      <p class="summary-label" style="margin: 0.5rem 0 0; font-size: 1rem;">Total Complaints</p>
    </div>
    <!-- Total Accidents -->
    <div class="summary-card" style="flex: 1 1 200px;  background: linear-gradient(135deg,rgb(255, 0, 255),rgb(243, 155, 255)); padding: 1.5rem; border-radius: 8px; text-align: center;">
      <div class="summary-number" style="font-size: 2rem; font-weight: bold;"><?php echo number_format($total_accidents); ?></div>
      <p class="summary-label" style="margin: 0.5rem 0 0; font-size: 1rem;">Total Accidents</p>
    </div>
    <!-- Compliance Rate -->
    <div class="summary-card" style="flex: 1 1 200px;  background: linear-gradient(135deg,rgb(255, 51, 0),hsl(0, 92.60%, 73.50%)); padding: 1.5rem; border-radius: 8px; text-align: center;">
      <div class="summary-number" style="font-size: 2rem; font-weight: bold;"><?php echo $compliance_rate; ?>%</div>
      <p class="summary-label" style="margin: 0.5rem 0 0; font-size: 1rem;">Compliance Rate</p>
    </div>
    <!-- Safety Inspections -->
    <div class="summary-card" style="flex: 1 1 200px;  background: linear-gradient(135deg,rgb(0, 255, 170),rgb(106, 235, 255)); padding: 1.5rem; border-radius: 8px; text-align: center;">
      <div class="summary-number" style="font-size: 2rem; font-weight: bold; color:white"><?php echo number_format($total_safety); ?></div>
      <p class="summary-label" style="margin: 0.5rem 0 0; font-size: 1rem; color:white">Safety Inspections</p>
    </div>
  </div>

  <!-- Charts Section -->
  <div class="stats-container" style="margin-top: 2rem;">
    <div class="chart-container" style="display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center;">
      <!-- Bar Chart -->
      <div class="chart-box" style="flex: 1 1 300px; background: #fff; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h3 class="chart-title" style="text-align: center; margin-bottom: 1rem;">Accident Metrics</h3>
        <canvas id="barChart"></canvas>
      </div>
      <!-- Line Chart -->
      <div class="chart-box" style="flex: 1 1 300px; background: #fff; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h3 class="chart-title" style="text-align: center; margin-bottom: 1rem;">Monthly Accident Trends</h3>
        <canvas id="lineChart"></canvas>
      </div>
      <!-- Pie Chart -->
      <div class="chart-box" style="flex: 1 1 300px; background: #fff; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h3 class="chart-title" style="text-align: center; margin-bottom: 1rem;">Accident Distribution</h3>
        <canvas id="pieChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Include Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
 // Color Palette for Charts
const colorPalette = {
  backgroundColor: [
    'rgba(255, 99, 132, 0.8)',
    'rgba(54, 162, 235, 0.8)',
    'rgba(255, 206, 86, 0.8)',
    'rgba(75, 192, 192, 0.8)',
    'rgba(153, 102, 255, 0.8)',
    'rgba(255, 159, 64, 0.8)'
  ],
  borderColor: [
    'rgba(255, 99, 132, 1)',
    'rgba(54, 162, 235, 1)',
    'rgba(255, 206, 86, 1)',
    'rgba(75, 192, 192, 1)',
    'rgba(153, 102, 255, 1)',
    'rgba(255, 159, 64, 1)'
  ]
};

Chart.defaults.font.size = 12;
Chart.defaults.plugins.legend.display = true;

// Bar Chart: Accident Metrics (Complaints, Accidents, Safety, Compliance)
const barCtx = document.getElementById('barChart').getContext('2d');
new Chart(barCtx, {
  type: 'bar',
  data: {
    labels: ['Complaints', 'Accidents', 'Safety', 'Compliance'],
    datasets: [{
      label: 'Metrics',
      data: [<?php echo $total_complaints; ?>, <?php echo $total_accidents; ?>, <?php echo $total_safety; ?>, <?php echo $compliance_rate; ?>],
      backgroundColor: [
        colorPalette.backgroundColor[0],
        colorPalette.backgroundColor[1],
        colorPalette.backgroundColor[2],
        colorPalette.backgroundColor[3]
      ],
      borderColor: [
        colorPalette.borderColor[0],
        colorPalette.borderColor[1],
        colorPalette.borderColor[2],
        colorPalette.borderColor[3]
      ],
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      y: { beginAtZero: true, grid: { color: 'rgba(200,200,200,0.2)' } },
      x: { grid: { display: false } }
    },
    plugins: { legend: { position: 'top' } }
  }
});

// Line Chart: Monthly Accident Trends with Gradient Fill
const lineCtx = document.getElementById('lineChart').getContext('2d');
const lineGradient = lineCtx.createLinearGradient(0, 0, 0, 400);
lineGradient.addColorStop(0, 'rgba(153, 102, 255, 0.8)');
lineGradient.addColorStop(1, 'rgba(153, 102, 255, 0.1)');

new Chart(lineCtx, {
  type: 'line',
  data: {
    labels: <?php echo json_encode($months); ?>,
    datasets: [{
      label: 'Accidents per Month',
      data: <?php echo json_encode($accident_counts); ?>,
      backgroundColor: lineGradient,
      borderColor: colorPalette.borderColor[4],
      borderWidth: 3,
      fill: true,
      tension: 0.4
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      y: { grid: { color: 'rgba(200,200,200,0.2)' } },
      x: { grid: { display: false } }
    }
  }
});

// Pie Chart: Yearly Accident Distribution
const pieCtx = document.getElementById('pieChart').getContext('2d');
new Chart(pieCtx, {
  type: 'doughnut',
  data: {
    labels: <?php echo json_encode($years); ?>,
    datasets: [{
      data: <?php echo json_encode($yearly_counts); ?>,
      backgroundColor: [
        colorPalette.backgroundColor[0],
        colorPalette.backgroundColor[1],
        colorPalette.backgroundColor[2],
        colorPalette.backgroundColor[3],
        colorPalette.backgroundColor[4],
        colorPalette.backgroundColor[5]
      ],
      borderColor: [
        colorPalette.borderColor[0],
        colorPalette.borderColor[1],
        colorPalette.borderColor[2],
        colorPalette.borderColor[3],
        colorPalette.borderColor[4],
        colorPalette.borderColor[5]
      ],
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'bottom',
        labels: { boxWidth: 30, padding: 30, font: { size: 12 } }
      }
    },
    cutout: '65%'
  }
});
</script>

<?php include 'include/footer.php'; ?>
