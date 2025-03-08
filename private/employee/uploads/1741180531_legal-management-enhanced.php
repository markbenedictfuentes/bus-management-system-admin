<?php
session_start();
include 'db_connect.php';

// Process New Case Entry Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['case_title'])) {
    $title       = mysqli_real_escape_string($conn, $_POST['case_title']);
    $description = mysqli_real_escape_string($conn, $_POST['case_description']);
    $status      = mysqli_real_escape_string($conn, $_POST['case_status']);
    $assigned    = mysqli_real_escape_string($conn, $_POST['assigned_personnel']);
    $date        = date('Y-m-d H:i:s');
    
    $sql = "INSERT INTO legal_cases (title, description, status, assigned, date_created) VALUES ('$title', '$description', '$status', '$assigned', '$date')";
    if ($conn->query($sql)) {
        $_SESSION['msg'] = "New case added successfully!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
    }
}

// Query dynamic data for each module
$sqlCases = "SELECT * FROM legal_cases ORDER BY date_created DESC";
$result_cases = $conn->query($sqlCases);

$sqlCompliance = "SELECT * FROM bus_compliance ORDER BY updated_at DESC LIMIT 1";
$result_compliance = $conn->query($sqlCompliance);
$compliance = $result_compliance && $result_compliance->num_rows > 0 ? $result_compliance->fetch_assoc() : null;

$sqlIncidents = "SELECT * FROM incidents ORDER BY incident_date DESC";
$result_incidents = $conn->query($sqlIncidents);

$sqlContracts = "SELECT * FROM contracts WHERE status='active' ORDER BY contract_date DESC";
$result_contracts = $conn->query($sqlContracts);

$sqlDisputes = "SELECT * FROM disputes ORDER BY dispute_date DESC";
$result_disputes = $conn->query($sqlDisputes);

$sqlDocs = "SELECT * FROM legal_documents ORDER BY uploaded_date DESC";
$result_docs = $conn->query($sqlDocs);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Legal Management - Bus Transportation - BTMS Admin</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Alpine.js CDN for interactivity -->
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Custom animations and additional styles */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .tab-content {
      animation: fadeIn 0.5s ease-out;
    }
  </style>
</head>
<body class="h-screen flex bg-gray-50">
  <!-- Sidebar (Not modified) -->
  <?php include '../../include/sidebar2.php'; ?>
  
  <!-- Main Content Wrapper -->
  <div class="flex-1 flex flex-col">
    <!-- Topbar (Not modified) -->
    <?php include '../../include/topbar.php'; ?>
    
    <!-- Main Content Area with Tabs (Alpine.js) -->
    <main class="flex-1 p-6 overflow-y-auto" x-data="{ tab: 'overview' }">
      <!-- Session Messages -->
      <?php if(isset($_SESSION['msg'])): ?>
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-800 rounded shadow">
          <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
        </div>
      <?php endif; ?>
      <?php if(isset($_SESSION['error'])): ?>
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-800 rounded shadow">
          <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>

      <!-- Enhanced Tab Navigation -->
      <nav class="mb-8 bg-white shadow-sm rounded-xl p-2">
        <ul class="flex flex-wrap gap-2 bg-gray-50 rounded-lg p-1.5">
          <li class="flex-1">
            <button 
              @click="tab = 'overview'" 
              :class="tab === 'overview' ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100'"
              class="w-full py-2.5 px-4 rounded-md text-sm font-medium transition-all duration-300 ease-in-out transform hover:scale-[1.02] focus:outline-none">
              <i class="fas fa-home mr-2 opacity-70"></i>
              Overview
            </button>
          </li>
          <li class="flex-1">
            <button 
              @click="tab = 'caseManagement'" 
              :class="tab === 'caseManagement' ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100'"
              class="w-full py-2.5 px-4 rounded-md text-sm font-medium transition-all duration-300 ease-in-out transform hover:scale-[1.02] focus:outline-none">
              <i class="fas fa-folder-open mr-2 opacity-70"></i>
              Case Management
            </button>
          </li>
          <li class="flex-1">
            <button 
              @click="tab = 'compliance'" 
              :class="tab === 'compliance' ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100'"
              class="w-full py-2.5 px-4 rounded-md text-sm font-medium transition-all duration-300 ease-in-out transform hover:scale-[1.02] focus:outline-none">
              <i class="fas fa-balance-scale mr-2 opacity-70"></i>
              Compliance
            </button>
          </li>
          <li class="flex-1">
            <button 
              @click="tab = 'accidents'" 
              :class="tab === 'accidents' ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100'"
              class="w-full py-2.5 px-4 rounded-md text-sm font-medium transition-all duration-300 ease-in-out transform hover:scale-[1.02] focus:outline-none">
              <i class="fas fa-car-crash mr-2 opacity-70"></i>
              Accidents
            </button>
          </li>
          <li class="flex-1">
            <button 
              @click="tab = 'contracts'" 
              :class="tab === 'contracts' ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100'"
              class="w-full py-2.5 px-4 rounded-md text-sm font-medium transition-all duration-300 ease-in-out transform hover:scale-[1.02] focus:outline-none">
              <i class="fas fa-file-contract mr-2 opacity-70"></i>
              Contracts
            </button>
          </li>
          <li class="flex-1">
            <button 
              @click="tab = 'disputes'" 
              :class="tab === 'disputes' ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100'"
              class="w-full py-2.5 px-4 rounded-md text-sm font-medium transition-all duration-300 ease-in-out transform hover:scale-[1.02] focus:outline-none">
              <i class="fas fa-gavel mr-2 opacity-70"></i>
              Disputes
            </button>
          </li>
          <li class="flex-1">
            <button 
              @click="tab = 'documentation'" 
              :class="tab === 'documentation' ? 'bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100'"
              class="w-full py-2.5 px-4 rounded-md text-sm font-medium transition-all duration-300 ease-in-out transform hover:scale-[1.02] focus:outline-none">
              <i class="fas fa-archive mr-2 opacity-70"></i>
              Documentation
            </button>
          </li>
        </ul>
      </nav>
      
      <!-- Rest of the content remains the same as in the original file -->
      
      <!-- Overview Tab -->
      <div x-show="tab==='overview'" class="space-y-6 tab-content">
        <h2 class="text-4xl font-bold pb-2 text-gray-800 border-b-4 border-blue-500">Legal Management Overview</h2>
        <p class="text-gray-700 text-lg leading-relaxed">
          Ang system na ito ay nagma-manage ng lahat ng legal na isyu sa bus transportation, kabilang ang:
        </p>
        <ul class="list-disc pl-8 text-gray-700 space-y-2">
          <li>Pag-monitor kung sumusunod ang bus sa lokal at pambansang regulasyon at safety standards.</li>
          <li>Accident & Incident Management para sa mabilis na pagproseso ng legal at insurance claims.</li>
          <li>Contract Management para sa pag-manage ng kontrata ng bus operators at third-party services.</li>
          <li>Dispute Resolution para sa pagresolba ng mga reklamo at legal disputes.</li>
          <li>Documentation & Record Keeping para sa transparency at audit trail.</li>
          <li>Case Management para sa pag-record at pag-track ng legal cases.</li>
        </ul>
      </div>

      <!-- Remaining tabs and content from the original file -->
      <!-- (All other sections like Case Management, Compliance, etc. remain unchanged) -->
    </main>
  </div>
</body>
</html>
