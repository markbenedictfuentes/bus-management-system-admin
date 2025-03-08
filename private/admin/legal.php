<?php
session_start();
include 'db_connect.php';

$sql_compliance_all = "SELECT * FROM compliance ORDER BY updated_at DESC";
$result_compliance_all = $conn->query($sql_compliance_all);
$compliance_records = [];
if ($result_compliance_all && $result_compliance_all->num_rows > 0) {
    while ($row = $result_compliance_all->fetch_assoc()) {
        $compliance_records[] = $row;
    }
}

$safety_records = [];
$sql_safety = "SELECT * FROM safety ORDER BY inspection_date DESC";
$result_safety = $conn->query($sql_safety);
if ($result_safety && $result_safety->num_rows > 0) {
    while ($row = $result_safety->fetch_assoc()) {
        $safety_records[] = $row;
    }
}
// Kunin lahat ng accident reports at i-store sa array
$query = "SELECT * FROM accidents ORDER BY accident_date DESC";
$result = $conn->query($query);

$query = "SELECT * FROM accidents ORDER BY accident_date DESC";
$result = $conn->query($query);
$accident_reports = [];
if ($result) {
  while ($row = $result->fetch_assoc()) {
    $accident_reports[] = $row;
  }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Safety Manager Dashboard - BTMS Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="/styles/legal.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="/styles/poppins.css?v=<?php echo time(); ?>">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    .custom-heading { color: #2563eb; }
    .nav-tabs { flex-wrap: nowrap !important; }
    .nav-tabs .nav-item { flex: 1 1 auto; text-align: center; }
    body, table, th, td {
      font-family: 'Poppins', sans-serif;
    }
    /* Custom DataTables styling */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
    background: #e2e8f0;
    border-radius: 0.375rem;
    margin: 0 0.25rem;
    padding: 0.25rem 0.5rem;
    border: none;
    cursor: pointer;
    font-size: 0.7rem;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #4299e1;
    color: white !important;
    font-size: 0.7rem;
}

.dataTables_wrapper .dataTables_filter {
    margin-bottom: 10px; /* Padding sa baba ng search */
}

.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 0.4rem 0.6rem; /* Mas malaking padding */
    font-size: 0.7rem;
}

#employeeTable tbody td {
    font-size: 0.8rem;
}

.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    font-size: 0.7rem;
}

.dataTables_wrapper .dataTables_length {
    margin-bottom: 10px; /* Padding sa baba ng dropdown */
}

.dataTables_wrapper .dataTables_length label {
    font-size: 0.7rem;
    color: #4a5568;
    font-weight: 500;
}

.dataTables_wrapper .dataTables_length select {
    padding: 0.4rem 0.6rem; /* Mas malaking padding */
    font-size: 0.7rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    background-color: #f7fafc;
    color: #4a5568;
    outline: none;
}
#busStatusTable th, #busStatusTable td {
    padding: 0.4rem;  /* bawasan ang padding */
    font-size: 0.85rem; /* mas maliit na font size */
}
  </style>
</head>
<body class="h-screen flex bg-gray-100">
<?php include '../../include/sidebar2.php'; ?>
  <div class="flex-1 flex flex-col">
  <?php include '../../include/topbar2.php'; ?>


  <div class="container-fluid">
    <main class="p-2">
      <?php if(isset($_SESSION['msg'])): ?>
        <div class="alert alert-success" role="alert">
          <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
        </div>
      <?php endif; ?>
      <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>
      <ul class="nav nav-tabs mb-4" id="dashboardTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="true">
            <i class="fas fa-home me-2"></i>Overview
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="compliance-tab" data-bs-toggle="tab" data-bs-target="#compliance" type="button" role="tab" aria-controls="compliance" aria-selected="false">
            <i class="fas fa-balance-scale me-2"></i>Compliance
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="accidents-tab" data-bs-toggle="tab" data-bs-target="#accidents" type="button" role="tab" aria-controls="accidents" aria-selected="false">
            <i class="fas fa-car-crash me-2"></i>Accident Management
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="contracts-tab" data-bs-toggle="tab" data-bs-target="#contracts" type="button" role="tab" aria-controls="contracts" aria-selected="false">
            <i class="fas fa-file-contract me-2"></i>Bus List
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="regulation-tab" data-bs-toggle="tab" data-bs-target="#regulation" type="button" role="tab" aria-controls="regulation" aria-selected="false">
            <i class="fas fa-gavel me-2"></i>Safety Regulation
          </button>
        </li>
      </ul>
      
      <!-- Tab Content -->

      <div class="tab-content">
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
          <?php include 'overview.php'; ?>
        </div>
        <div class="tab-pane fade" id="caseManagement" role="tabpanel" aria-labelledby="caseManagement-tab">
          <?php include 'case_management.php'; ?>
        </div>
        <div class="tab-pane fade" id="compliance" role="tabpanel" aria-labelledby="compliance-tab">
          <?php include 'compliance_tab.php'; ?>
        </div>
        <div class="tab-pane fade" id="accidents" role="tabpanel" aria-labelledby="accidents-tab">
          <?php include 'accidents.php'; ?>
        </div>
        <div class="tab-pane fade" id="contracts" role="tabpanel" aria-labelledby="contracts-tab">
          <?php include 'contracts.php'; ?>
        </div>
        <div class="tab-pane fade" id="regulation" role="tabpanel" aria-labelledby="regulation-tab">
          <?php include 'regulation.php'; ?>
        </div>
      </div><!-- End Tab Content -->
      </div><!-- End Tab Content -->
    </main>
</div>
</div>

<?php if (!empty($compliance_records)): ?>
  <?php foreach ($compliance_records as $record): ?>
    <div class="modal fade" id="viewComplianceModal<?php echo $record['id']; ?>" tabindex="-1"
         aria-labelledby="viewComplianceModalLabel<?php echo $record['id']; ?>" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="viewComplianceModalLabel<?php echo $record['id']; ?>">Compliance Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p><strong>Status:</strong> <?php echo ucfirst($record['status']); ?></p>
            <p><strong>Last Checked:</strong> <?php echo date("F d Y, g:ia", strtotime($record['updated_at'])); ?></p>
            <p><strong>Notes:</strong> <?php echo htmlspecialchars($record['notes']); ?></p>
            <?php if (!empty($record['checklist'])): 
              // Hatiin ang checklist string sa array at alisin ang extrang spaces
              $checklist_items = array_map('trim', explode(',', $record['checklist']));
            ?>
              <p><strong>Checklist:</strong></p>
              <div class="row">
                <?php foreach ($checklist_items as $item): 
                  // Palitan ang underscores ng space at gawing title case
                  $clean_item = ucwords(str_replace('_', ' ', $item));
                ?>
                  <div class="col-md-6 mb-2">
                    <p class="mb-0">
                      <i class="fas fa-check-circle text-success me-1"></i>
                      Check <?php echo $clean_item; ?>
                    </p>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>




<?php if (!empty($safety_records)): ?>
  <?php foreach ($safety_records as $record): ?>
    <div class="modal fade" id="viewSafetyModal<?= $record['id'] ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              Safety Proofs - Bus <?= htmlspecialchars($record['bus_number']) ?> 
              (Plate: <?= htmlspecialchars($record['plate_number']) ?>)
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <?php foreach (['bus_proof', 'passenger_proof'] as $proofType): ?>
                <?php if (!empty($record[$proofType])): ?>
                  <?php
                    // Gamitin ang ACTUAL PHYSICAL PATH
                    $filename = basename($record[$proofType]);
                    $absolutePath = 'C:/wamp64/www/bus-management-system-admin/private/employee/uploads/' . $filename;
                    $imagePath = '/private/employee/uploads/' . rawurlencode($filename);
                  ?>
                  <div class="col-md-6 mb-4">
                    <div class="card h-100">
                      <div class="card-header bg-<?= $proofType === 'bus_proof' ? 'primary' : 'success' ?> text-white">
                        <?= ucfirst(str_replace('_', ' ', $proofType)) ?>
                      </div>
                      <div class="card-body text-center">
                        <?php if(file_exists($absolutePath)): ?>
                          <img src="<?= $imagePath ?>" 
                               class="img-fluid rounded" 
                               alt="<?= $proofType ?> proof"
                               style="max-height: 300px;">
                        <?php else: ?>
                          <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            File not found!<br>
                            <small class="text-muted">
                              Server Path: <?= $absolutePath ?>
                            </small>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
            <?php if (!empty($record['checklist'])): 
              // Hatiin ang checklist string sa array at alisin ang extrang spaces
              $checklist_items = array_map('trim', explode(',', $record['checklist']));
            ?>
              <p><strong>Checklist:</strong></p>
              <div class="row">
                <?php foreach ($checklist_items as $item): 
                  // Palitan ang underscores ng space at gawing title case
                  $clean_item = ucwords(str_replace('_', ' ', $item));
                ?>
                  <div class="col-md-6 mb-2">
                    <p class="mb-0">
                      <i class="fas fa-check-circle text-success me-1"></i>
                      Check <?php echo $clean_item; ?>
                    </p>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="fas fa-times me-2"></i> Close
            </button>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>


<?php if (!empty($accident_reports)): ?>
  <?php foreach ($accident_reports as $report): ?>
    <?php 
      // I-format ang date at time
      $dt = strtotime($report['accident_date']);
      $formattedDate = date('F, d Y', $dt);  // Halimbawa: January, 06 2026
      $formattedTime = date('g:ia', $dt);      // Halimbawa: 1:46pm

      // Directory para sa image
      $baseDir = '/home/admin.nexfleetdynamics.com/public_html';
      if (!empty($report['image'])) {
          $filename = basename($report['image']);
          $absolutePath = $baseDir . '/private/employee/uploads/' . $filename;
          $imagePath = '/private/employee/uploads/' . $filename; // Relative URL
      }
    ?>
    <!-- Accident Report Details Modal -->
    <div class="modal fade" id="viewAccidentModal<?php echo $report['id']; ?>" tabindex="-1" aria-labelledby="viewAccidentModalLabel<?php echo $report['id']; ?>" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="viewAccidentModalLabel<?php echo $report['id']; ?>">Accident Report Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Two column layout para ibang fields -->
            <div class="row mt-2">
              <div class="col-md-6">
                <p><strong>Date:</strong> <?php echo $formattedDate; ?></p>
                <p><strong>Time:</strong> <?php echo $formattedTime; ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($report['location']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($report['status'] ?? 'Pending'); ?></p>
              </div>
              <div class="col-md-6">
                <p><strong>Number of Injuries:</strong> <?php echo $report['injuries']; ?></p>
                <p><strong>Severity:</strong> <?php echo htmlspecialchars($report['severity']); ?></p>
                <p><strong>Witness Name:</strong> <?php echo htmlspecialchars($report['witness_name']); ?></p>
                <p><strong>Witness Contact:</strong> <?php echo htmlspecialchars($report['witness_contact']); ?></p>
              </div>
            </div>
            <!-- Full width Description -->
            <div class="row mt-2">
              <div class="col-12">
                <p><strong>Description:</strong></p>
                <p><?php echo htmlspecialchars($report['description']); ?></p>
              </div>
            </div>
            <!-- Full width Additional Comments -->
            <div class="row mt-2">
              <div class="col-12">
                <p><strong>Additional Comments:</strong></p>
                <p><?php echo htmlspecialchars($report['additional_comments']); ?></p>
              </div>
            </div>
            <!-- Image (kung mayroon) -->
            <?php if (!empty($report['image'])): ?>
              <div class="row mt-3">
                <div class="col-12">
                  <p><strong>Image:</strong></p>
                  <?php if (file_exists($absolutePath)): ?>
                    <img src="<?php echo $imagePath; ?>" alt="Accident Image" class="img-fluid">
                  <?php else: ?>
                    <!-- SweetAlert alert kung hindi makita ang file -->
                    <script>
                      Swal.fire({
                        icon: 'error',
                        title: 'File not found!',
                        text: 'The image file for this accident report was not found on the server.'
                      });
                    </script>
                  <?php endif; ?>
                </div>
              </div>
            <?php endif; ?>
          </div>
          <div class="modal-footer">
            <!-- Button para buksan ang hiwalay na Update Status modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateStatusModal<?php echo $report['id']; ?>">Update Status</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Hiwalay na Update Status Modal -->
    <div class="modal fade" id="updateStatusModal<?php echo $report['id']; ?>" tabindex="-1" aria-labelledby="updateStatusModalLabel<?php echo $report['id']; ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="update_status.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="updateStatusModalLabel<?php echo $report['id']; ?>">Update Status</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
          <div class="mb-3">
            <label for="status<?php echo $report['id']; ?>" class="form-label"><strong>Status:</strong></label>
            <select name="status" id="status<?php echo $report['id']; ?>" class="form-select">
              <option value="Pending" <?php if(($report['status'] ?? 'Pending')=="Pending") echo 'selected'; ?>>Pending</option>
              <option value="Review Accident" <?php if(($report['status'] ?? 'Pending')=="Review Accident") echo 'selected'; ?>>Review Accident</option>
              <option value="Resolved" <?php if(($report['status'] ?? 'Pending')=="Resolved") echo 'selected'; ?>>Resolved</option>
              <option value="Rejected" <?php if(($report['status'] ?? 'Pending')=="Rejected") echo 'selected'; ?>>Rejected</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update Status</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

    
  <?php endforeach; ?>
<?php endif; ?>


  <script>
    $(document).ready(function() {
      $('#caseTable').DataTable();
      $('#complianceTable').DataTable();
      $('#safetyTable').DataTable();
    });
  </script>
  
  <!-- Bootstrap JS Bundle -->
</body>
</html>
