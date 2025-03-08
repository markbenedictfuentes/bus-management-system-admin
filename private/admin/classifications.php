<?php
session_start();
include 'db_connect.php';
// Fetch complaint classification data from the database
$sql = "SELECT classification, COUNT(*) as count FROM complaints GROUP BY classification";
$result = $conn->query($sql);

// Prepare data for the pie chart
$classificationData = [];
while ($row = $result->fetch_assoc()) {
    $classificationData[] = $row;
}

// Create data arrays for the pie chart
$labels = [];
$data = [];
foreach ($classificationData as $classification) {
    $labels[] = $classification['classification'];
    $data[] = $classification['count'];
}

// Fetch data for the bar chart (complaints by date)
$sqlDate = "SELECT DATE(created_at) as date, COUNT(*) as count FROM complaints GROUP BY DATE(created_at) ORDER BY DATE(created_at) DESC LIMIT 7";
$resultDate = $conn->query($sqlDate);
$dateLabels = [];
$dateData = [];
while ($row = $resultDate->fetch_assoc()) {
    $dateLabels[] = $row['date'];
    $dateData[] = $row['count'];
}

// Fetch trending complaint subjects (top 5 most common complaints)
$sqlTrending = "SELECT subject, COUNT(*) as count FROM complaints GROUP BY subject ORDER BY count DESC LIMIT 5";
$resultTrending = $conn->query($sqlTrending);
$trendingComplaints = [];
while ($row = $resultTrending->fetch_assoc()) {
    $trendingComplaints[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AI Classification Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="/styles/poppins.css?v=<?php echo time(); ?>">
  <style>
    /* Modern elegant design */
    body {
      font-family: 'poppins', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f7fa;
    }
    
    .card {
      border-radius: 12px;
      border: none;
      box-shadow: 0 8px 20px rgba(0,0,0,0.05);
      transition: transform 0.3s, box-shadow 0.3s;
      overflow: hidden;
      margin-bottom: 24px;
    }
    
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 25px rgba(0,0,0,0.1);
    }
    
    .card-header {
      background: linear-gradient(135deg, #6e8efb, #a777e3);
      color: white;
      border-bottom: none;
      padding: 16px 20px;
      font-weight: 600;
    }
    
    .card-header h4 {
      margin: 0;
      font-size: 1.2rem;
      font-weight: 600;
    }
    
    .card-body {
      padding: 20px;
    }
    
    /* Table styling */
    .table {
      border-collapse: separate;
      border-spacing: 0;
      width: 100%;
      border-radius: 8px;
      overflow: hidden;
    }
    
    .table th {
      background-color: #f0f4f8;
      color: #334155;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.8rem;
      letter-spacing: 0.5px;
      padding: 15px;
      border: none;
    }
    
    .table td {
      padding: 15px;
      border-top: 1px solid #f0f4f8;
      color: #475569;
      font-size: 0.95rem;
      vertical-align: middle;
    }
    
    .table tr:hover {
      background-color: #f8fafc;
    }
    
    /* DataTables customization */
    .dataTables_wrapper .dataTables_filter input {
      border-radius: 20px;
      border: 1px solid #e2e8f0;
      padding: 6px 12px;
      font-size: 0.9rem;
    }
    
    .dataTables_wrapper .dataTables_length select {
      border-radius: 20px;
      border: 1px solid #e2e8f0;
      padding: 6px 12px;
      font-size: 0.9rem;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
      border-radius: 20px !important;
      padding: 5px 12px !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
      background: linear-gradient(135deg, #6e8efb, #a777e3) !important;
      border: none !important;
      color: white !important;
    }
    
    /* Filter dropdown styling */
    #filterClass {
      border-radius: 20px;
      border: 1px solid #e2e8f0;
      padding: 8px 15px;
      background-color: white;
      color: #334155;
      cursor: pointer;
      transition: all 0.3s;
    }
    
    #filterClass:focus {
      outline: none;
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
      border-color: #6366f1;
    }
    
    h1 {
      color: #334155;
      font-weight: 700;
      margin-bottom: 1.5rem;
      position: relative;
    }
    
    h1::after {
      content: '';
      position: absolute;
      width: 60px;
      height: 4px;
      background: linear-gradient(135deg, #6e8efb, #a777e3);
      bottom: -10px;
      left: 0;
      border-radius: 2px;
    }
  </style>
</head>

<body class="h-screen flex">
  <?php include '../../include/sidebar2.php'; ?>
  <div class="flex-1 flex flex-col">
    <?php include '../../include/topbar2.php'; ?>

    <div class="container py-4">

      <!-- Filters (Optional) -->
      <div class="card mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <label for="filterClass" class="mb-0 fw-bold">Complaint Classification Overview</label>
          </div>
        </div>
      </div>

      <div class="row">
        <!-- Pie Chart and Bar Chart side by side (Horizontal) -->
        <div class="col-md-6 mb-4">
          <!-- Pie Chart for Complaint Classifications -->
          <div class="card">
            <div class="card-header">
              <h4>Complaint Classification Breakdown</h4>
            </div>
            <div class="card-body">
              <canvas id="classificationChart" width="400" height="400"></canvas>
            </div>
          </div>
        </div>

        <div class="col-md-6 mb-4">
          <!-- Bar Chart for Complaints by Date -->
          <div class="card">
            <div class="card-header">
              <h4>Complaints by Date (Last 7 Days)</h4>
            </div>
            <div class="card-body">
              <canvas id="complaintsByDateChart" width="400" height="400"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Trending Complaints Table -->
      <div class="card">
        <div class="card-header">
          <h4>Trending Complaints</h4>
        </div>
        <div class="card-body">
          <table id="trendingComplaintsTable" class="table table-striped">
            <thead>
              <tr>
                <th>Complaint Subject</th>
                <th>Frequency</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($trendingComplaints as $complaint) : ?>
                <tr>
                  <td><?= $complaint['subject'] ?></td>
                  <td><?= $complaint['count'] ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <script>
      // Pie Chart for Complaint Classification
      const ctxPie = document.getElementById('classificationChart').getContext('2d');
      const classificationChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
          labels: <?php echo json_encode($labels); ?>,
          datasets: [{
            label: 'Complaint Classification',
            data: <?php echo json_encode($data); ?>,
            backgroundColor: ['#ff6b8a', '#4e9ff5', '#56d798', '#ffc168', '#a389f4', '#f99a3e'],
            borderColor: '#fff',
            borderWidth: 2
          }]
        },
        options: {
          responsive: true,
          plugins: {
            datalabels: {
              color: 'white',
              font: {
                weight: 'bold'
              }
            },
            legend: {
              position: 'bottom'
            }
          }
        }
      });

      // Bar Chart for Complaints by Date (Last 7 Days)
      const ctxBar = document.getElementById('complaintsByDateChart').getContext('2d');
      const complaintsByDateChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
          labels: <?php echo json_encode($dateLabels); ?>,
          datasets: [{
            label: 'Complaints by Date',
            data: <?php echo json_encode($dateData); ?>,
            backgroundColor: 'rgba(110, 142, 251, 0.7)',
            borderColor: '#6e8efb',
            borderWidth: 1,
            borderRadius: 8
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
              grid: {
                display: true,
                color: 'rgba(0, 0, 0, 0.05)'
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

      // DataTable Initialization for Trending Complaints
      $(document).ready(function() {
        $('#trendingComplaintsTable').DataTable({
          responsive: true,
          language: {
            search: "_INPUT_",
            searchPlaceholder: "Search complaints...",
            paginate: {
              previous: "<i class='fa fa-chevron-left'></i>",
              next: "<i class='fa fa-chevron-right'></i>"
            }
          },
          "pageLength": 5,
          "lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]]
        });
      });

      // Function to filter data by classification
      function filterData() {
        const filterValue = document.getElementById('filterClass').value;
        // Logic for filtering data based on selection
        if (filterValue) {
          // Make an AJAX call or dynamically filter the data on the page
          console.log('Filtering by ' + filterValue); // Placeholder logic
        } else {
          console.log('Show all classifications'); // Placeholder logic
        }
      }
    </script>
  </div>
</body>
</html>