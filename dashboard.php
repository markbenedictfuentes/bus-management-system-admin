<?php 
// header.php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$name = $_SESSION['name'];
?>

<?php include 'include/header.php'; ?>

<div class="dashboard">

  <div class="summary-box">
    <div class="summary-card">
      <div class="summary-number">150</div>
      <p class="summary-label">Total Visitors</p>
    </div>
    <div class="summary-card">
      <div class="summary-number">75</div>
      <p class="summary-label">Repeat Visitors</p>
    </div>
    <div class="summary-card">
      <div class="summary-number">50%</div>
      <p class="summary-label">Return Rate</p>
    </div>
  </div>

  <div class="stats-container">
    <div class="chart-container">
      <!-- Bar Chart -->
      <div class="chart-box">
        <h3 class="chart-title">Visitor Categories</h3>
        <canvas id="barChart"></canvas>
      </div>

      <!-- Line Chart -->
      <div class="chart-box">
        <h3 class="chart-title">Monthly Visitor Trends</h3>
        <canvas id="lineChart"></canvas>
      </div>

      <!-- Pie Chart -->
      <div class="chart-box">
        <h3 class="chart-title">Visitor Distribution</h3>
        <canvas id="pieChart"></canvas>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
 // Replace monotonous blue theme with vibrant diverse colors
const colorPalette = {
  backgroundColor: [
    'rgba(255, 99, 132, 0.8)',    // Vibrant red
    'rgba(54, 162, 235, 0.8)',    // Blue
    'rgba(255, 206, 86, 0.8)',    // Yellow
    'rgba(75, 192, 192, 0.8)',    // Teal
    'rgba(153, 102, 255, 0.8)',   // Purple
    'rgba(255, 159, 64, 0.8)'     // Orange
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

Chart.defaults.font.size = 12; // Increased font size
Chart.defaults.plugins.legend.display = true;

// Bar Chart with new colors
const barCtx = document.getElementById('barChart').getContext('2d');
new Chart(barCtx, {
  type: 'bar',
  data: {
    labels: ['Total', 'Repeat', 'New'],
    datasets: [{
      label: 'Visitors',
      data: [150, 75, 75],
      backgroundColor: [colorPalette.backgroundColor[0], colorPalette.backgroundColor[1], colorPalette.backgroundColor[2]],
      borderColor: [colorPalette.borderColor[0], colorPalette.borderColor[1], colorPalette.borderColor[2]],
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      y: {
        beginAtZero: true,
        grid: {
          color: 'rgba(200, 200, 200, 0.2)' // Lighter grid lines
        }
      },
      x: {
        grid: {
          display: false // Remove x-axis grid lines
        }
      }
    },
    plugins: {
      legend: {
        display: true,
        position: 'top'
      }
    }
  }
});

// Line Chart with gradient fill
const lineCtx = document.getElementById('lineChart').getContext('2d');
const lineGradient = lineCtx.createLinearGradient(0, 0, 0, 400);
lineGradient.addColorStop(0, 'rgba(153, 102, 255, 0.8)');
lineGradient.addColorStop(1, 'rgba(153, 102, 255, 0.1)');

new Chart(lineCtx, {
  type: 'line',
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
    datasets: [{
      label: 'Visitors',
      data: [65, 59, 80, 81, 56, 55],
      backgroundColor: lineGradient,
      borderColor: colorPalette.borderColor[4], // Purple
      borderWidth: 3,
      fill: true,
      tension: 0.4 // Add curve to line
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      y: {
        grid: {
          color: 'rgba(200, 200, 200, 0.2)'
        }
      },
      x: {
        grid: {
          display: false
        }
      }
    }
  }
});

// Pie Chart with vibrant colors
const pieCtx = document.getElementById('pieChart').getContext('2d');
new Chart(pieCtx, {
  type: 'doughnut',
  data: {
    labels: ['First-time', 'Repeat', 'Frequent'],
    datasets: [{
      data: [75, 50, 25],
      backgroundColor: [
        colorPalette.backgroundColor[5], // Orange
        colorPalette.backgroundColor[3], // Teal
        colorPalette.backgroundColor[2]  // Yellow
      ],
      borderColor: [
        colorPalette.borderColor[5],
        colorPalette.borderColor[3],
        colorPalette.borderColor[2]
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
        labels: {
          boxWidth: 15,
          padding: 15,
          font: {
            size: 12
          }
        }
      }
    },
    cutout: '65%'
  }
});
</script>

<?php include 'include/footer.php'; ?>