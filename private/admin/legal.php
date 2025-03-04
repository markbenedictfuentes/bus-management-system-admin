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
// Case Management
$sqlCases = "SELECT * FROM legal_cases ORDER BY date_created DESC";
$result_cases = $conn->query($sqlCases);

// Compliance & Regulation (assuming table 'bus_compliance')
$sqlCompliance = "SELECT * FROM bus_compliance ORDER BY updated_at DESC LIMIT 1";
$result_compliance = $conn->query($sqlCompliance);
$compliance = $result_compliance && $result_compliance->num_rows > 0 ? $result_compliance->fetch_assoc() : null;

// Accident Management (assuming table 'incidents')
$sqlIncidents = "SELECT * FROM incidents ORDER BY incident_date DESC";
$result_incidents = $conn->query($sqlIncidents);

// Contract Management (assuming table 'contracts')
$sqlContracts = "SELECT * FROM contracts WHERE status='active' ORDER BY contract_date DESC";
$result_contracts = $conn->query($sqlContracts);

// Dispute Resolution (assuming table 'disputes')
$sqlDisputes = "SELECT * FROM disputes ORDER BY dispute_date DESC";
$result_disputes = $conn->query($sqlDisputes);

// Documentation & Record Keeping (assuming table 'legal_documents')
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
</head>
<body class="h-screen flex bg-gray-100">
  <!-- Sidebar -->
  <?php include '../../include/sidebar2.php'; ?>
  
  <!-- Main Content Wrapper -->
  <div class="flex-1 flex flex-col">
    <!-- Topbar -->
    <?php include '../../include/topbar.php'; ?>
    
    <!-- Main Content Area with Tabs (Alpine.js) -->
    <main class="flex-1 p-6 overflow-y-auto" x-data="{ tab: 'overview' }">
      <!-- Display Session Messages -->
      <?php if(isset($_SESSION['msg'])): ?>
        <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">
          <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
        </div>
      <?php endif; ?>
      <?php if(isset($_SESSION['error'])): ?>
        <div class="mb-4 p-3 bg-red-200 text-red-800 rounded">
          <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>

      <!-- Tab Navigation -->
      <nav class="mb-6">
        <ul class="flex flex-wrap gap-2">
          <li>
            <button 
              @click="tab = 'overview'" 
              :class="tab === 'overview' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'" 
              class="px-4 py-2 rounded-md focus:outline-none">
              Overview
            </button>
          </li>
          <li>
            <button 
              @click="tab = 'caseManagement'" 
              :class="tab === 'caseManagement' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'" 
              class="px-4 py-2 rounded-md focus:outline-none">
              Case Management
            </button>
          </li>
          <li>
            <button 
              @click="tab = 'compliance'" 
              :class="tab === 'compliance' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'" 
              class="px-4 py-2 rounded-md focus:outline-none">
              Compliance & Regulation
            </button>
          </li>
          <li>
            <button 
              @click="tab = 'accidents'" 
              :class="tab === 'accidents' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'" 
              class="px-4 py-2 rounded-md focus:outline-none">
              Accident Management
            </button>
          </li>
          <li>
            <button 
              @click="tab = 'contracts'" 
              :class="tab === 'contracts' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'" 
              class="px-4 py-2 rounded-md focus:outline-none">
              Contract Management
            </button>
          </li>
          <li>
            <button 
              @click="tab = 'disputes'" 
              :class="tab === 'disputes' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'" 
              class="px-4 py-2 rounded-md focus:outline-none">
              Dispute Resolution
            </button>
          </li>
          <li>
            <button 
              @click="tab = 'documentation'" 
              :class="tab === 'documentation' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'" 
              class="px-4 py-2 rounded-md focus:outline-none">
              Documentation
            </button>
          </li>
        </ul>
      </nav>
      
      <!-- Overview Tab -->
      <div x-show="tab==='overview'" class="space-y-4">
        <h2 class="text-3xl font-bold text-[#00446b]">Legal Management Overview</h2>
        <p class="text-gray-700 text-lg">
          Ang system na ito ay nagma-manage ng lahat ng legal na isyu sa bus transportation, kabilang ang:
        </p>
        <ul class="list-disc pl-6 text-gray-700">
          <li>Pag-monitor kung sumusunod ang bus sa lokal at pambansang regulasyon at safety standards.</li>
          <li>Accident & Incident Management para sa mabilis na pagproseso ng legal at insurance claims.</li>
          <li>Contract Management para sa pag-manage ng kontrata ng bus operators at third-party services.</li>
          <li>Dispute Resolution para sa pagresolba ng mga reklamo at legal disputes.</li>
          <li>Documentation & Record Keeping para sa transparency at audit trail.</li>
          <li>Case Management para sa pag-record at pag-track ng legal cases.</li>
        </ul>
      </div>
      
      <!-- Case Management Tab -->
      <div x-show="tab==='caseManagement'" class="space-y-4">
        <h2 class="text-3xl font-bold text-[#00446b]">Case Management</h2>
        <p class="text-gray-700 text-lg">
          Mag-record, mag-track, at mag-update ng legal cases kasama ang status at assigned personnel.
        </p>
        <!-- Dynamic Case List Table -->
        <div class="overflow-x-auto bg-white rounded shadow">
          <table class="min-w-full">
            <thead class="bg-gray-200">
              <tr>
                <th class="px-4 py-2 text-left">Case ID</th>
                <th class="px-4 py-2 text-left">Title</th>
                <th class="px-4 py-2 text-left">Status</th>
                <th class="px-4 py-2 text-left">Assigned</th>
                <th class="px-4 py-2 text-left">Date</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <?php if ($result_cases && $result_cases->num_rows > 0): ?>
                <?php while($case = $result_cases->fetch_assoc()): ?>
                  <tr>
                    <td class="px-4 py-2"><?php echo htmlspecialchars($case['id']); ?></td>
                    <td class="px-4 py-2"><?php echo htmlspecialchars($case['title']); ?></td>
                    <td class="px-4 py-2"><?php echo htmlspecialchars(ucfirst($case['status'])); ?></td>
                    <td class="px-4 py-2"><?php echo htmlspecialchars($case['assigned']); ?></td>
                    <td class="px-4 py-2"><?php echo htmlspecialchars($case['date_created']); ?></td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="5" class="px-4 py-2 text-center">No cases found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
        
        <!-- New Case Entry Form -->
        <div class="mt-6 bg-white p-6 rounded shadow">
          <h3 class="text-2xl font-semibold text-gray-800 mb-4">Add New Case</h3>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="mb-4">
              <label for="case_title" class="block text-gray-700 mb-2">Case Title</label>
              <input type="text" id="case_title" name="case_title" placeholder="Enter case title" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div class="mb-4">
              <label for="case_description" class="block text-gray-700 mb-2">Description</label>
              <textarea id="case_description" name="case_description" placeholder="Enter case description" class="w-full px-3 py-2 border rounded" required></textarea>
            </div>
            <div class="mb-4">
              <label for="case_status" class="block text-gray-700 mb-2">Case Status</label>
              <select id="case_status" name="case_status" class="w-full px-3 py-2 border rounded" required>
                <option value="pending">Pending</option>
                <option value="ongoing">Ongoing</option>
                <option value="resolved">Resolved</option>
              </select>
            </div>
            <div class="mb-4">
              <label for="assigned_personnel" class="block text-gray-700 mb-2">Assigned Personnel</label>
              <select id="assigned_personnel" name="assigned_personnel" class="w-full px-3 py-2 border rounded" required>
                <option value="">Select personnel</option>
                <option value="lawyer1">Lawyer 1</option>
                <option value="lawyer2">Lawyer 2</option>
                <option value="admin">Admin</option>
              </select>
            </div>
            <div class="flex justify-end">
              <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md">Submit Case</button>
            </div>
          </form>
        </div>
      </div>
      
      <!-- Compliance & Regulation Tab -->
      <div x-show="tab==='compliance'" class="space-y-4">
        <h2 class="text-3xl font-bold text-[#00446b]">Compliance & Regulation</h2>
        <p class="text-gray-700 text-lg">
          Suriin kung sumusunod ang operasyon ng bus sa mga legal na regulasyon at safety standards.
        </p>
        <!-- Dynamic Compliance Data -->
        <div class="bg-white p-6 rounded-lg shadow">
          <h3 class="text-2xl font-semibold text-gray-800 mb-2">Bus Compliance Status</h3>
          <?php if ($compliance): ?>
            <p class="text-gray-600">
              <strong>Status:</strong> 
              <span class="<?php echo ($compliance['status'] === 'compliant') ? 'text-green-600' : 'text-red-600'; ?>">
                <?php echo ucfirst($compliance['status']); ?>
              </span>
              <br>
              <strong>Last Checked:</strong> <?php echo htmlspecialchars($compliance['updated_at']); ?>
            </p>
          <?php else: ?>
            <p class="text-gray-600">No compliance data found.</p>
          <?php endif; ?>
        </div>
      </div>
      
      <!-- Accident & Incident Management Tab -->
      <div x-show="tab==='accidents'" class="space-y-4">
        <h2 class="text-3xl font-bold text-[#00446b]">Accident & Incident Management</h2>
        <p class="text-gray-700 text-lg">
          Mabilis na pagproseso ng legal claims, insurance claims, at pagsasaayos ng aksidente o insidente.
        </p>
        <!-- Dynamic Incident Log -->
        <div class="bg-white p-6 rounded-lg shadow">
          <h3 class="text-2xl font-semibold text-gray-800 mb-2">Recent Incidents</h3>
          <?php if ($result_incidents && $result_incidents->num_rows > 0): ?>
            <ul class="list-disc pl-6 text-gray-700">
              <?php while($incident = $result_incidents->fetch_assoc()): ?>
                <li>
                  <strong>Incident <?php echo htmlspecialchars($incident['id']); ?>:</strong> 
                  <?php echo htmlspecialchars($incident['description']); ?> 
                  (<?php echo htmlspecialchars($incident['incident_date']); ?>)
                </li>
              <?php endwhile; ?>
            </ul>
          <?php else: ?>
            <p class="text-gray-600">No incidents reported.</p>
          <?php endif; ?>
        </div>
      </div>
      
      <!-- Contract Management Tab -->
      <div x-show="tab==='contracts'" class="space-y-4">
        <h2 class="text-3xl font-bold text-[#00446b]">Contract Management</h2>
        <p class="text-gray-700 text-lg">
          Pag-manage ng mga kontrata sa pagitan ng bus operators, maintenance providers, at iba pang third-party services.
        </p>
        <!-- Dynamic Contract List -->
        <div class="bg-white p-6 rounded-lg shadow">
          <h3 class="text-2xl font-semibold text-gray-800 mb-2">Active Contracts</h3>
          <?php if ($result_contracts && $result_contracts->num_rows > 0): ?>
            <ul class="list-disc pl-6 text-gray-700">
              <?php while($contract = $result_contracts->fetch_assoc()): ?>
                <li>
                  <strong><?php echo htmlspecialchars($contract['contract_title']); ?>:</strong> 
                  <?php echo htmlspecialchars($contract['details']); ?>
                </li>
              <?php endwhile; ?>
            </ul>
          <?php else: ?>
            <p class="text-gray-600">No active contracts found.</p>
          <?php endif; ?>
        </div>
      </div>
      
      <!-- Dispute Resolution Tab -->
      <div x-show="tab==='disputes'" class="space-y-4">
        <h2 class="text-3xl font-bold text-[#00446b]">Dispute Resolution</h2>
        <p class="text-gray-700 text-lg">
          Pagtugon sa anumang reklamo o legal dispute mula sa mga pasahero, empleyado, o stakeholders.
        </p>
        <!-- Dynamic Dispute List -->
        <div class="bg-white p-6 rounded-lg shadow">
          <h3 class="text-2xl font-semibold text-gray-800 mb-2">Ongoing Disputes</h3>
          <?php if ($result_disputes && $result_disputes->num_rows > 0): ?>
            <ul class="list-disc pl-6 text-gray-700">
              <?php while($dispute = $result_disputes->fetch_assoc()): ?>
                <li>
                  <strong>Dispute <?php echo htmlspecialchars($dispute['id']); ?>:</strong> 
                  <?php echo htmlspecialchars($dispute['issue']); ?> 
                  (<?php echo htmlspecialchars($dispute['dispute_date']); ?>)
                </li>
              <?php endwhile; ?>
            </ul>
          <?php else: ?>
            <p class="text-gray-600">No disputes recorded.</p>
          <?php endif; ?>
        </div>
      </div>
      
      <!-- Documentation & Record Keeping Tab -->
      <div x-show="tab==='documentation'" class="space-y-4">
        <h2 class="text-3xl font-bold text-[#00446b]">Documentation & Record Keeping</h2>
        <p class="text-gray-700 text-lg">
          Pag-iingat ng kumpletong record ng mga legal documents tulad ng permits, licenses, at mga case logs para sa transparency at audit trail.
        </p>
        <!-- Dynamic Document List -->
        <div class="bg-white p-6 rounded-lg shadow">
          <h3 class="text-2xl font-semibold text-gray-800 mb-2">Legal Documents</h3>
          <?php if ($result_docs && $result_docs->num_rows > 0): ?>
            <ul class="list-disc pl-6 text-gray-700">
              <?php while($doc = $result_docs->fetch_assoc()): ?>
                <li><?php echo htmlspecialchars($doc['document_title']); ?> - <?php echo htmlspecialchars($doc['document_type']); ?></li>
              <?php endwhile; ?>
            </ul>
          <?php else: ?>
            <p class="text-gray-600">No legal documents available.</p>
          <?php endif; ?>
        </div>
      </div>
    </main>
  </div>
  
  <!-- Footer -->
  <footer class="bg-white shadow-inner mt-8">
    <div class="container mx-auto px-4 py-4 text-center text-gray-600">
      &copy; <?php echo date('Y'); ?> BTMS. All rights reserved.
    </div>
  </footer>
</body>
</html>
