<?php
session_start();
include 'db_connect.php';

// I-load ang PHPMailer gamit ang Composer autoloader
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$successMessage = '';
$errorMessage = '';

// Function para mag-send ng email gamit ang PHPMailer
function sendEmail($to, $name, $subject, $message) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'pagadora021@gmail.com';      // Ipasok ang iyong Gmail address
        $mail->Password   = 'abpx efyv fdtv roph';           // Ipasok ang iyong Gmail app password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Recipients (gamitin mo ang parehong Gmail address bilang sender)
        $mail->setFrom('pagadora021@gmail.com', 'BTMS Admin');
        $mail->addAddress($to, $name);

        // Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Maaari mong i-log ang error: $mail->ErrorInfo
        return false;
    }
}

// Process status update actions (acknowledge/resolve)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == 'acknowledge') {
        $sql = "UPDATE complaints SET status='In-Progress' WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            // Kunin ang detalye ng complaint para sa email
            $sql_email = "SELECT email, name FROM complaints WHERE id=?";
            $stmt_email = $conn->prepare($sql_email);
            $stmt_email->bind_param("i", $id);
            $stmt_email->execute();
            $result_email = $stmt_email->get_result();
            if ($row_email = $result_email->fetch_assoc()){
                $to = $row_email['email'];
                $subject = "Your Complaint is Under Review";
                $message = "Dear " . $row_email['name'] . ",\n\nThank you for submitting your complaint. We have received your concern and it is now under review. Our team will contact you shortly to schedule a meeting at our office to discuss your complaint further.\n\nRegards,\nBTMS Admin Team";
                // Send email notification gamit ang PHPMailer
                sendEmail($to, $row_email['name'], $subject, $message);
            }
            $stmt_email->close();
            $_SESSION['success'] = "Complaint #$id has been updated successfully.";
        } else {
            $_SESSION['error'] = "Error updating complaint #$id: " . $stmt->error;
        }
        $stmt->close();
        header("Location: complaints_sol.php");
        exit();
    } elseif ($action == 'resolve') {
        $sql = "UPDATE complaints SET status='Resolved' WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            // Kunin ang detalye ng complaint para sa email
            $sql_email = "SELECT email, name FROM complaints WHERE id=?";
            $stmt_email = $conn->prepare($sql_email);
            $stmt_email->bind_param("i", $id);
            $stmt_email->execute();
            $result_email = $stmt_email->get_result();
            if ($row_email = $result_email->fetch_assoc()){
                $to = $row_email['email'];
                $subject = "Your Complaint Has Been Resolved";
                $message = "Dear " . $row_email['name'] . ",\n\nWe are pleased to inform you that your complaint has been resolved. If you have any further questions or would like to discuss the resolution, please contact our office. Thank you for helping us improve our service.\n\nRegards,\nBTMS Admin Team";
                // Send email notification gamit ang PHPMailer
                sendEmail($to, $row_email['name'], $subject, $message);
            }
            $stmt_email->close();
            $_SESSION['success'] = "Complaint #$id has been updated successfully.";
        } else {
            $_SESSION['error'] = "Error updating complaint #$id: " . $stmt->error;
        }
        $stmt->close();
        header("Location: complaints_sol.php");
        exit();
    }
}

// Kunin lahat ng complaints mula sa database
$sql = "SELECT * FROM complaints ORDER BY created_at DESC";
$result = $conn->query($sql);
$complaints = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $complaints[] = $row;
    }
}

// I-extract ang session messages para magamit sa JS (kung meron)
if(isset($_SESSION['success'])) {
    $successMessage = $_SESSION['success'];
    unset($_SESSION['success']);
}
if(isset($_SESSION['error'])) {
    $errorMessage = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Complaints - BTMS Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="/styles/legal.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="/styles/poppins.css?v=<?php echo time(); ?>">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
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
      margin-bottom: 10px;
    }
    .dataTables_wrapper .dataTables_filter input {
      border: 1px solid #d1d5db;
      border-radius: 0.375rem;
      padding: 0.4rem 0.6rem;
      font-size: 0.7rem;
    }
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
      font-size: 0.7rem;
    }
    .dataTables_wrapper .dataTables_length {
      margin-bottom: 10px;
    }
    .dataTables_wrapper .dataTables_length label {
      font-size: 0.7rem;
      color: #4a5568;
      font-weight: 500;
    }
    .dataTables_wrapper .dataTables_length select {
      padding: 0.4rem 0.6rem;
      font-size: 0.7rem;
      border: 1px solid #d1d5db;
      border-radius: 0.375rem;
      background-color: #f7fafc;
      color: #4a5568;
      outline: none;
    }
    .status-new { color: #fff; background-color: #dc3545; font-size: 10px; text-align: center; display: inline-block; margin: 0 auto; padding: 3px 5px; border-radius: 50px; }
    .status-in-progress { color: #fff; background-color: #ffc107; font-size: 10px; text-align: center;  display: inline-block; margin: 0 auto;padding: 3px 5px; border-radius: 50px; }
    .status-resolved { color: #fff; background-color: #28a745; font-size: 10px; text-align: center;  display: inline-block; margin: 0 auto;padding: 3px 5px; border-radius: 50px; }

    /* Limitahan ang max width ng modal kung gusto mong mas malapad konti */
    .modal-dialog {
      max-width: 900px;
    }
    .modal-header {
      background-color: #0d6efd;
      color: #fff;
      border-bottom: none;
    }
    .modal-title {
      font-weight: 600;
    }
    .btn-close {
      filter: invert(1);
    }
    .modal-body {
      padding: 1rem 1.5rem;
    }
    .complaint-label {
      font-weight: 600;
      color: #4a5568;
      min-width: 120px;
      display: inline-block; 
    }
    .complaint-value {
      color: #111;
      margin-left: 5px;
    }
    .complaint-row {
      margin-bottom: 1rem;
    }
  </style>
</head>
<body class="h-screen flex bg-gray-100">
  <?php include '../../include/sidebar2.php'; ?>
  <div class="flex-1 flex flex-col">
    <?php include '../../include/topbar2.php'; ?>
    <div class="container mt-4">
      <div class="card-header bg-gray-200 mb-2 p-2 rounded">
          <h2 class="text-lg font-semibold">Complaint List</h2>
      </div>
      <div class="card p-4">
        <div class="table-responsive p-3 rounded shadow-sm">
          <table id="complaintsTable" class="table table-striped table-bordered" style="width:100%">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Bus #</th>
                <th>Subject</th>
                <th>Classification</th>
                <th>Date Submitted</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if(count($complaints) > 0): ?>
                <?php foreach ($complaints as $complaint): ?>
                  <tr>
                    <td><?php echo $complaint['id']; ?></td>
                    <td><?php echo htmlspecialchars($complaint['name']); ?></td>
                    <td><?php echo htmlspecialchars($complaint['bus_number']); ?></td>
                    <td><?php echo htmlspecialchars($complaint['subject']); ?></td>
                    <td><?php echo !empty($complaint['classification']) ? htmlspecialchars($complaint['classification']) : 'Not Classified'; ?></td>
                    <td><?php echo date("M d, Y H:i", strtotime($complaint['created_at'])); ?></td>
                    <td>
                      <div style="display: flex; justify-content: center; align-items: center;">
                        <?php 
                          $status = $complaint['status'];
                          if($status == 'New'){
                            echo "<span class='status-new'>New</span>";
                          } elseif($status == 'In-Progress'){
                            echo "<span class='status-in-progress'>In-Progress</span>";
                          } else {
                            echo "<span class='status-resolved'>Resolved</span>";
                          }
                        ?>
                      </div>
                    </td>
                    <td>
                      <div class="d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-info d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#complaintModal_<?php echo $complaint['id']; ?>">
                          <i class="fas fa-eye"></i>
                        </button>
                        <?php if($status == 'New'): ?>
                          <a href="complaints_sol.php?action=acknowledge&id=<?php echo $complaint['id']; ?>" 
                              class="btn btn-sm btn-warning swal-ack d-flex align-items-center justify-content-center">
                              Noted
                          </a>
                        <?php endif; ?>
                        <?php if($status == 'In-Progress'): ?>
                          <a href="complaints_sol.php?action=resolve&id=<?php echo $complaint['id']; ?>" 
                              class="btn btn-sm btn-success swal-resolve d-flex align-items-center justify-content-center p-1" 
                              style="width: 30px; height: 30px; border-radius: 50%;">
                              <i class="fas fa-check"></i>
                          </a>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="8" class="text-center">No complaints found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Complaint Details Modals (isa-isa para sa bawat complaint) -->
  <?php foreach ($complaints as $complaint): 
    if (!empty($complaint['attachment'])) {
        $attachmentFile = basename($complaint['attachment']);
        $attachmentUrl = '/bus-management-system-admin/private/visitors/uploads/complaints/' . $attachmentFile;
    }
  ?>
  <div class="modal fade" id="complaintModal_<?php echo $complaint['id']; ?>" tabindex="-1" aria-labelledby="complaintModalLabel_<?php echo $complaint['id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="complaintModalLabel_<?php echo $complaint['id']; ?>">Complaint #<?php echo $complaint['id']; ?> Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Row 1: Subject & Name -->
          <div class="row">
            <div class="col-md-6">
             <p class="mt-2"><strong>Subject:</strong> <?php echo htmlspecialchars($complaint['subject']); ?></p>
            </div>
            <div class="col-md-6">
             <p class="mt-2"><strong>Name:</strong> <?php echo htmlspecialchars($complaint['name']); ?></p>
            </div>
          </div>
          <!-- Row 2: Email & Bus Number -->
          <div class="row">
            <div class="col-md-6">
             <p class="mt-2"><strong>Email:</strong> <?php echo htmlspecialchars($complaint['email']); ?></p>
            </div>
            <div class="col-md-6">
             <p class="mt-2"><strong>Bus Number:</strong> <?php echo htmlspecialchars($complaint['bus_number']); ?></p>
            </div>
          </div>
          <!-- Row 3: Classification -->
          <div class="row">
            <div class="col-12">
             <p class="mt-2"><strong>Classification:</strong> <?php echo !empty($complaint['classification']) ? htmlspecialchars($complaint['classification']) : 'Not Classified'; ?></p>
            </div>
          </div>
          <!-- Row 4: Complaint -->
          <div class="row">
            <div class="col-12">
             <p class="mt-2"><strong>Complaint:</strong> <?php echo nl2br(htmlspecialchars($complaint['complaint'])); ?></p>
            </div>
          </div>
          <!-- Row 5: Detailed Complaint -->
          <div class="row">
            <div class="col-12">
             <p class="mt-2"><strong>Detailed Complaint:</strong> <?php echo nl2br(htmlspecialchars($complaint['detailed_complaint'])); ?></p>
            </div>
          </div>
          <!-- Row 6: Incident Date/Time & Location -->
          <div class="row">
            <div class="col-md-6">
             <p class="mt-2"><strong>Incident Date/Time:</strong> <?php echo htmlspecialchars($complaint['incident_datetime']); ?></p>
            </div>
            <div class="col-md-6">
             <p class="mt-2"><strong>Incident Location:</strong> <?php echo htmlspecialchars($complaint['incident_location']); ?></p>
            </div>
          </div>
          <!-- Row 7: Attachment (kung meron) -->
          <?php if (!empty($complaint['attachment'])): ?>
          <div class="row">
            <div class="col-12">
             <p class="mt-2"><strong>Attachment:</strong></p>
              <a href="download.php?file=<?php echo urlencode($attachmentFile); ?>" class="btn btn-secondary btn-sm">Download</a>
            </div>
          </div>
          <?php endif; ?>
          <!-- Row 8: Date Submitted & Status -->
          <div class="row">
            <div class="col-md-6">
              <p class="mt-2"><strong>Date Submitted:</strong> <?php echo date("M d, Y H:i", strtotime($complaint['created_at'])); ?></p>
            </div>
            <div class="col-md-6">
              <p class="mt-2"><strong>Status:</strong> <?php echo htmlspecialchars($complaint['status']); ?></p>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>

  <!-- Bootstrap Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Initialize DataTables -->
  <script>
    $(document).ready(function() {
        $('#complaintsTable').DataTable({
            responsive: true
        });

        // SweetAlert confirmation para sa "Noted" action
        $('.swal-ack').on('click', function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure?',
                text: "Acknowledge this complaint?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#FFC107',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Noted it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });

        // SweetAlert confirmation para sa "Resolve" action
        $('.swal-resolve').on('click', function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure?',
                text: "Mark this complaint as Resolved?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Resolve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
  </script>
  <!-- Ipakita ang SweetAlert notifications para sa success/error -->
  <script>
    $(document).ready(function(){
      <?php if(!empty($successMessage)): ?>
        Swal.fire({
          icon: 'success',
          title: 'Success',
          text: '<?php echo addslashes($successMessage); ?>'
        });
      <?php endif; ?>
      <?php if(!empty($errorMessage)): ?>
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: '<?php echo addslashes($errorMessage); ?>'
        });
      <?php endif; ?>
    });
  </script>
</body>
</html>
