<?php
session_start();
include 'db_connect.php';

// Query for the latest compliance record
$compliance = null;
$queryLatest = "SELECT * FROM compliance ORDER BY id DESC LIMIT 1";
$resultLatest = mysqli_query($conn, $queryLatest);
if ($resultLatest && mysqli_num_rows($resultLatest) > 0) {
  $compliance = mysqli_fetch_assoc($resultLatest);
}

// Query counts for compliant and non-compliant records
$queryCompliant = "SELECT COUNT(*) as count FROM compliance WHERE status = 'compliant'";
$resultCompliant = mysqli_query($conn, $queryCompliant);
$compliantCount = $resultCompliant ? mysqli_fetch_assoc($resultCompliant)['count'] : 0;

$queryNonCompliant = "SELECT COUNT(*) as count FROM compliance WHERE status = 'non-compliant'";
$resultNonCompliant = mysqli_query($conn, $queryNonCompliant);
$nonCompliantCount = $resultNonCompliant ? mysqli_fetch_assoc($resultNonCompliant)['count'] : 0;

$queryTotal = "SELECT COUNT(*) as count FROM compliance";
$resultTotal = mysqli_query($conn, $queryTotal);
$totalCompliance = $resultTotal ? mysqli_fetch_assoc($resultTotal)['count'] : 0;

// Calculate operational efficiency based on compliant checks
$efficiency = ($totalCompliance > 0) ? round(($compliantCount / $totalCompliance) * 100, 1) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Operations Manager Dashboard - BTMS Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              50: '#f0f9ff',
              100: '#e0f2fe',
              200: '#bae6fd',
              300: '#7dd3fc',
              400: '#38bdf8',
              500: '#0ea5e9',
              600: '#0284c7',
              700: '#0369a1',
              800: '#075985',
              900: '#0c4a6e',
            },
          },
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
          },
        }
      }
    }
  </script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    body {
      font-family: 'Inter', sans-serif;
    }
    
    .metric-card {
      transition: transform 0.2s;
    }
    
    .metric-card:hover {
      transform: translateY(-5px);
    }
    
    .progress-bar {
      transition: width 1s ease-in-out;
    }
  </style>
</head>
<body class="h-screen flex bg-gradient-to-br from-gray-50 to-gray-100">
  <?php include '../../include/sidebar2.php'; ?>
  <div class="flex-1 flex flex-col">
    <?php include '../../include/topbar2.php'; ?>

    <main class="p-6 lg:p-8">
      <!-- Header Section -->
      <header class="mb-8">
        <div class="flex items-center space-x-3 mb-2">
          <i class="fas fa-chart-line text-primary-600 text-3xl"></i>
          <h1 class="text-3xl md:text-4xl font-bold text-gray-800">Operations Dashboard</h1>
        </div>
        <p class="text-gray-600 max-w-3xl">
          Real-time monitoring of bus transportation operations, compliance metrics, and performance data
        </p>
      </header>

      <!-- Quick Summary Stats -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Checks -->
        <div class="bg-white rounded-xl shadow-sm border-l-4 border-primary-500 p-6 metric-card">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm font-medium">Total Checks</p>
              <p class="text-3xl font-bold text-gray-800 mt-1"><?php echo $totalCompliance; ?></p>
            </div>
            <div class="bg-primary-100 p-3 rounded-lg">
              <i class="fas fa-clipboard-check text-primary-600 text-xl"></i>
            </div>
          </div>
        </div>
        
        <!-- Compliant -->
        <div class="bg-white rounded-xl shadow-sm border-l-4 border-green-500 p-6 metric-card">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm font-medium">Compliant</p>
              <p class="text-3xl font-bold text-green-600 mt-1"><?php echo $compliantCount; ?></p>
            </div>
            <div class="bg-green-100 p-3 rounded-lg">
              <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
          </div>
        </div>
        
        <!-- Non-Compliant -->
        <div class="bg-white rounded-xl shadow-sm border-l-4 border-red-500 p-6 metric-card">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm font-medium">Non-Compliant</p>
              <p class="text-3xl font-bold text-red-600 mt-1"><?php echo $nonCompliantCount; ?></p>
            </div>
            <div class="bg-red-100 p-3 rounded-lg">
              <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
            </div>
          </div>
        </div>
        
        <!-- Efficiency -->
        <div class="bg-white rounded-xl shadow-sm border-l-4 border-blue-500 p-6 metric-card">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm font-medium">Efficiency</p>
              <p class="text-3xl font-bold text-blue-600 mt-1"><?php echo $efficiency; ?>%</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-lg">
              <i class="fas fa-tachometer-alt text-blue-500 text-xl"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content Panels -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Compliance Status Panel -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
          <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">Compliance Status</h2>
          </div>
          <div class="p-6">
            <div class="flex items-center mb-6">
              <div class="w-16 h-16 rounded-full flex items-center justify-center 
                <?php echo ($compliance && $compliance['status'] == 'compliant') ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'; ?>">
                <i class="fas <?php echo ($compliance && $compliance['status'] == 'compliant') ? 'fa-check' : 'fa-times'; ?> text-2xl"></i>
              </div>
              <div class="ml-4">
                <p class="text-2xl font-bold 
                  <?php echo ($compliance && $compliance['status'] == 'compliant') ? 'text-green-600' : 'text-red-600'; ?>">
                  <?php echo $compliance ? ucfirst($compliance['status']) : 'No Data'; ?>
                </p>
                <p class="text-gray-500">
                  Last Updated: <?php echo $compliance ? htmlspecialchars($compliance['updated_at']) : 'N/A'; ?>
                </p>
              </div>
            </div>
            
            <div class="space-y-4">
              <div class="w-full">
                <div class="flex justify-between mb-1">
                  <span class="text-sm font-medium text-gray-700">Compliance Rate</span>
                  <span class="text-sm font-medium text-gray-700"><?php echo $efficiency; ?>%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                  <div class="progress-bar bg-gradient-to-r from-green-400 to-green-600 h-2.5 rounded-full" style="width: <?php echo $efficiency; ?>%"></div>
                </div>
              </div>
              
              <div class="grid grid-cols-2 gap-4 mt-4">
                <div class="bg-green-50 p-4 rounded-lg text-center">
                  <p class="text-lg font-bold text-green-600"><?php echo $compliantCount; ?></p>
                  <p class="text-sm text-green-700">Compliant</p>
                </div>
                <div class="bg-red-50 p-4 rounded-lg text-center">
                  <p class="text-lg font-bold text-red-600"><?php echo $nonCompliantCount; ?></p>
                  <p class="text-sm text-red-700">Non-Compliant</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Quick Insights Panel -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
          <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">Quick Insights</h2>
          </div>
          <div class="p-6">
            <div class="flex items-center space-x-4 mb-6">
              <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-lightbulb text-blue-500 text-xl"></i>
              </div>
              <p class="text-gray-600 font-medium">Key metrics and trends at a glance</p>
            </div>
            
            <div class="space-y-4">
              <div class="flex items-start">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mt-0.5">
                  <i class="fas fa-chart-line text-green-500"></i>
                </div>
                <div class="ml-3">
                  <p class="font-medium text-gray-700">Daily Compliance Rate</p>
                  <p class="text-gray-600"><?php echo $totalCompliance > 0 ? round(($compliantCount / $totalCompliance) * 100, 1) : 0; ?>% compliance achieved today</p>
                </div>
              </div>
              
              <div class="flex items-start">
                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mt-0.5">
                  <i class="fas fa-tools text-yellow-500"></i>
                </div>
                <div class="ml-3">
                  <p class="font-medium text-gray-700">Maintenance Alerts</p>
                  <p class="text-gray-600">Regular checks ensure bus safety and efficient operation</p>
                </div>
              </div>
              
              <div class="flex items-start">
                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mt-0.5">
                  <i class="fas fa-search text-purple-500"></i>
                </div>
                <div class="ml-3">
                  <p class="font-medium text-gray-700">Operational Bottlenecks</p>
                  <p class="text-gray-600">Analyze data to identify areas for improvement</p>
                </div>
              </div>
            </div>
          
          </div>
        </div>
      </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>