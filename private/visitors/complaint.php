<?php
session_start();
include 'db_connect.php';

require __DIR__ . '/../../vendor/autoload.php';
use Phpml\ModelManager;

// Function to check keyword-based classification
function classifyComplaintByKeyword($complaintText) {
    // Cleanliness Keywords (added more Filipino keywords and general terms)
    $cleanlinessKeywords = [
        'mabaho', 'dumi', 'amoy', 'basura', 'malinis', 'ipis', 'hindi malinis',
        'dirty', 'smelly', 'stained', 'cockroaches', 'stinky', 'trash', 'odor', 
        'unhygienic', 'messy', 'dusty', 'unsanitary', 'greasy', 'filthy', 'grimy',
        'untidy', 'smells bad', 'polluted', 'garbage', 'unclean', 'spilled liquids', 'stains',
        'hindi malinis', 'dumi sa sahig', 'mantsa', 'basura sa bus', 'hindi naayos', 'amoy ng basurahan'
    ];

    // Behavior Keywords (more expanded)
    $behaviorKeywords = [
        'bastos', 'galit', 'nagmumura', 'sumigaw', 'hindi magalang', 'disiplina', 'driver behavior',
        'rude', 'aggressive', 'yelled', 'cursing', 'arguing', 'careless', 'unprofessional', 'bad mood', 
        'unsafe', 'reckless', 'texting while driving', 'yelling', 'complaining', 'irritated', 
        'disrespectful', 'inconsiderate', 'short temper', 'anger', 'shouting', 'bullying',
        'walang galang', 'nag-aaway', 'bastos ang ugali', 'masama ang ugali', 'hindi marunong magkontrol ng emosyon', 'pagmumura'
    ];

    // Punctuality Keywords (extended with related terms)
    $punctualityKeywords = [
        'late', 'delayed', 'hintuan', 'huwag', 'hindi dumating', 'schedule', 'on time', 'arrived late',
        'skipped stop', 'cancelled', 'traffic', 'delay', 'off schedule', 'missed stop', 
        'late arrival', 'early departure', 'unpredictable schedule', 'late service', 'missed connection',
        'changed route', 'no show', 'sudden change', 'waited too long', 'hindi dumating sa oras', 'hindi tumigil sa hintuan',
        'nawalang biyahe', 'huli', 'nawala ang oras', 'pinalitan ang ruta', 'hindi sinunod ang oras ng pag-alis', 'walang abiso'
    ];

    $complaintText = strtolower($complaintText); // Convert to lowercase

    // Check if cleanliness keywords are present
    foreach ($cleanlinessKeywords as $keyword) {
        if (strpos($complaintText, $keyword) !== false) {
            return 'Cleanliness';
        }
    }

    // Check if behavior keywords are present
    foreach ($behaviorKeywords as $keyword) {
        if (strpos($complaintText, $keyword) !== false) {
            return 'Behavior';
        }
    }

    // Check if punctuality keywords are present
    foreach ($punctualityKeywords as $keyword) {
        if (strpos($complaintText, $keyword) !== false) {
            return 'Punctuality';
        }
    }

    // Default fallback if no matching keyword found
    return 'Unclassified';
}

// Process form submission only if method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $busData = explode("|", trim($_POST['bus_number']));
    $busNumber = $busData[0];
    $busPlate = isset($busData[1]) ? $busData[1] : '';
    $subject = trim($_POST['subject']);
    $complaint = trim($_POST['complaint']);
    $detailed_complaint = trim($_POST['detailed_complaint'] ?? '');
    $incident_datetime = trim($_POST['incident_datetime'] ?? '');
    $incident_location = trim($_POST['incident_location'] ?? '');
    
    $attachment_path = NULL;
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $upload_dir = "uploads/complaints/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = time() . "_" . basename($_FILES['attachment']['name']);
        $file_tmp = $_FILES['attachment']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
        
        if (in_array($file_ext, $allowed_types)) {
            $target_path = $upload_dir . $file_name;
            if (move_uploaded_file($file_tmp, $target_path)) {
                $attachment_path = $target_path;
            } else {
                $_SESSION['error'] = "Error uploading attachment.";
                header("Location: complaint.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid file type. Allowed types: JPG, JPEG, PNG, GIF, PDF.";
            header("Location: complaint.php");
            exit();
        }
    }

    // Classify the complaint based on keywords
    $predictedLabel = classifyComplaintByKeyword($complaint);

    // Insert complaint into database with classification
    $sql = "INSERT INTO complaints (name, email, bus_number, plate_number, subject, complaint, detailed_complaint, incident_datetime, incident_location, attachment, classification, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $_SESSION['error'] = "Database error: " . $conn->error;
        header("Location: complaint.php");
        exit();
    }
    $stmt->bind_param("sssssssssss", $name, $email, $busNumber, $busPlate, $subject, $complaint, $detailed_complaint, $incident_datetime, $incident_location, $attachment_path, $predictedLabel);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Complaint submitted successfully. Classified as: " . $predictedLabel;
    } else {
        $_SESSION['error'] = "Error submitting complaint: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    header("Location: complaint.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Bus Complaint Form - BTMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="/styles/form.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="/styles/poppins.css?v=<?php echo time(); ?>">
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="h-screen flex bg-gray-100">
  <?php include '../../include/sidebar2.php'; ?>
  <div class="flex-1 flex flex-col">
    <?php include '../../include/topbar2.php'; ?>

    <div class="container-fluid">
      <!-- SweetAlert for session messages -->
      <?php if(isset($_SESSION['success'])): ?>
      <script>
        document.addEventListener("DOMContentLoaded", function(){
          Swal.fire({
            title: 'Success!',
            text: "<?php echo $_SESSION['success']; ?>",
            icon: 'success',
            confirmButtonText: 'OK'
          });
        });
      </script>
      <?php unset($_SESSION['success']); endif; ?>
      
      <?php if(isset($_SESSION['error'])): ?>
      <script>
        document.addEventListener("DOMContentLoaded", function(){
          Swal.fire({
            title: 'Error!',
            text: "<?php echo $_SESSION['error']; ?>",
            icon: 'error',
            confirmButtonText: 'OK'
          });
        });
      </script>
      <?php unset($_SESSION['error']); endif; ?>
      
      <!-- Card with header -->
      <div class="card bg-white p-0 rounded shadow-sm">
        <div class="card-header">
          <h3 class="mb-0">Complaint Form</h3>
        </div>
        <div class="card-body p-4">
          <form action="complaint.php" method="POST" enctype="multipart/form-data">
            <!-- Two Column Layout for Basic Info -->
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="name" class="form-label fw-bold">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your full name" required class="form-control">
              </div>
              <div class="col-md-6">
                <label for="email" class="form-label fw-bold">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required class="form-control">
              </div>
            </div>
            
            <!-- Second Row: Bus Number Dropdown and Subject -->
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="bus_number" class="form-label fw-bold">Bus Number / Plate Number</label>
                <select id="bus_number" name="bus_number" class="form-control" required>
                  <option value="">Select Bus</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="subject" class="form-label fw-bold">Subject</label>
                <input type="text" id="subject" name="subject" placeholder="Enter subject of complaint" required class="form-control">
              </div>
            </div>
            
            <!-- Full-Width Row: Complaint Details -->
            <div class="mb-3">
              <label for="complaint" class="form-label fw-bold">Complaint Details</label>
              <textarea id="complaint" name="complaint" placeholder="Provide a description of your complaint" rows="5" required class="form-control"></textarea>
            </div>
            
            <!-- New Field: Detailed Incident Description -->
            <div class="mb-3">
              <label for="detailed_complaint" class="form-label fw-bold">Additional Details (Bus Misconduct)</label>
              <textarea id="detailed_complaint" name="detailed_complaint" placeholder="Describe in detail any incidents or misconduct observed on the bus" rows="5" class="form-control"></textarea>
            </div>
            
            <!-- New Field: Incident Date and Time & Location -->
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="incident_datetime" class="form-label fw-bold">Incident Date and Time</label>
                <input type="datetime-local" id="incident_datetime" name="incident_datetime" class="form-control">
              </div>
              <div class="col-md-6">
                <label for="incident_location" class="form-label fw-bold">Incident Location</label>
                <input type="text" id="incident_location" name="incident_location" placeholder="Enter the exact location of the incident" class="form-control">
              </div>
            </div>
            
            <!-- Full-Width Row: Attachment -->
            <div class="mb-3">
              <label for="attachment" class="form-label fw-bold">Attachment (Optional)</label>
              <input type="file" id="attachment" name="attachment" accept="image/*,application/pdf" class="form-control">
            </div>
            
            <!-- Submit Button -->
            <div class="text-end">
              <button type="submit" class="btn btn-primary">Submit Complaint</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery AJAX script to populate the Bus dropdown -->
  <script>
  $(document).ready(function(){
    $.ajax({
      url: 'https://core1.nexfleetdynamics.com/api/buses',
      type: 'GET',
      dataType: 'json',
      success: function(response){
        var select = $('#bus_number');
        select.empty();
        select.append('<option value="">Select Bus</option>');
        if(response.success && response.data.length > 0) {
          $.each(response.data, function(index, bus){
            // Ang value ay may kasamang BusNumber at PlateNumber na pinaghihiwalay ng |
            select.append('<option value="'+bus.BusNumber+'|'+bus.PlateNumber+'">'+bus.BusNumber+' / '+bus.PlateNumber+'</option>');
          });
        } else {
          select.append('<option value="">No bus data available</option>');
        }
      },
      error: function(xhr, status, error){
        $('#bus_number').html('<option value="">Error fetching bus data</option>');
      }
    });
  });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
