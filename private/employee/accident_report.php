<?php
session_start();
include 'db_connect.php';

// Variable para sa feedback message at error collection
$message = "";
$errors = [];

// Kung POST request, iproseso ang form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kunin at i-trim ang input values
    $employee_id         = isset($_POST['employee_id']) ? trim($_POST['employee_id']) : "";
    $accident_date       = isset($_POST['accident_date']) ? trim($_POST['accident_date']) : "";
    $location            = isset($_POST['location']) ? trim($_POST['location']) : "";
    $description         = isset($_POST['description']) ? trim($_POST['description']) : "";
    $injuries            = isset($_POST['injuries']) ? trim($_POST['injuries']) : 0;
    $severity            = isset($_POST['severity']) ? trim($_POST['severity']) : "Minor";
    $witness_name        = isset($_POST['witness_name']) ? trim($_POST['witness_name']) : "";
    $witness_contact     = isset($_POST['witness_contact']) ? trim($_POST['witness_contact']) : "";
    $additional_comments = isset($_POST['additional_comments']) ? trim($_POST['additional_comments']) : "";
    
    // Basic validations para sa required fields
    if (empty($accident_date)) {
        $errors[] = "Accident date & time is required.";
    }
    if (empty($location)) {
        $errors[] = "Location is required.";
    }
    if (empty($description)) {
        $errors[] = "Description is required.";
    }
    
    // Optional: I-validate ang injuries bilang numeric value
    if (!is_numeric($injuries)) {
        $errors[] = "Number of injuries must be a number.";
    }
    
    // File upload (optional)
    $imagePath = NULL;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = array("jpg", "jpeg", "png", "gif");
        $fileName = basename($_FILES['image']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (!in_array($fileExt, $allowed)) {
            $errors[] = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        } else {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            // Sanitize filename at add timestamp
            $sanitizedFileName = preg_replace("/[^a-zA-Z0-9\.]/", "", $fileName);
            $targetFile = $targetDir . time() . "_" . $sanitizedFileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = $targetFile;
            } else {
                $errors[] = "Error uploading the file.";
            }
        }
    }
    
    // Kung walang errors, magpatuloy sa pag-insert sa database
    if (empty($errors)) {
        $sql = "INSERT INTO accidents (employee_id, accident_date, location, description, image, injuries, severity, witness_name, witness_contact, additional_comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $message = "Database error: " . $conn->error;
        } else {
            // I-bind ang mga parameter; employee_id at injuries bilang integer, ang iba ay string
            $stmt->bind_param("issssissss", $employee_id, $accident_date, $location, $description, $imagePath, $injuries, $severity, $witness_name, $witness_contact, $additional_comments);
            if ($stmt->execute()) {
                $message = "Accident report submitted successfully.";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        // Pag-combine ng error messages kung may validation errors
        $message = implode("<br>", $errors);
    }
    // Ipapakita lang ang feedback sa page; hindi magre-redirect
}

// Para sa demonstration, kung wala pang naka-set sa session, gagamit tayo ng default na employee_id
$employee_id = $_SESSION['employee_id'] ?? 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Accident Report</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/styles/accident.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="/styles/poppins.css?v=<?php echo time(); ?>">
</head>
<body class="h-screen flex bg-gray-100">
  <?php include '../../include/sidebar2.php'; ?>
  <div class="flex-1 flex flex-col">
    <?php include '../../include/topbar2.php'; ?>
    
    <div class="container-fluid">
      <div class="card shadow">
        <div class="card-header text-center bg-primary text-white">
          <h2>Accident Report Form</h2>
        </div>
        <div class="card-body">
          <!-- Feedback message -->
          <?php if (!empty($message)): ?>
            <div class="alert <?php echo (strpos($message, "successfully") !== false) ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
              <?php echo $message; ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>
          <form action="accident_report.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($employee_id); ?>">
            <div class="row">
              <!-- Left Column -->
              <div class="col-md-6">
                <!-- Accident Date & Time -->
                <div class="mb-3">
                  <label for="accident_date" class="form-label">Accident Date & Time:</label>
                  <input type="datetime-local" id="accident_date" name="accident_date" required class="form-control">
                </div>
                <!-- Location -->
                <div class="mb-3">
                  <label for="location" class="form-label">Location:</label>
                  <input type="text" id="location" name="location" required placeholder="Enter location" class="form-control">
                </div>
                <!-- Description -->
                <div class="mb-3">
                  <label for="description" class="form-label">Description:</label>
                  <textarea id="description" name="description" required rows="4" placeholder="Describe the accident in detail" class="form-control"></textarea>
                </div>
                <!-- Number of Injuries -->
                <div class="mb-3">
                  <label for="injuries" class="form-label">Number of Injuries:</label>
                  <input type="number" id="injuries" name="injuries" min="0" placeholder="0" class="form-control">
                </div>
                <!-- Severity -->
                <div class="mb-3">
                  <label for="severity" class="form-label">Severity of Accident:</label>
                  <select id="severity" name="severity" class="form-select">
                    <option value="Minor">Minor</option>
                    <option value="Moderate">Moderate</option>
                    <option value="Severe">Severe</option>
                  </select>
                </div>
              </div>
              <!-- Right Column -->
              <div class="col-md-6">
                <!-- Witness Information -->
                <div class="mb-3">
                  <label for="witness_name" class="form-label">Witness Name:</label>
                  <input type="text" id="witness_name" name="witness_name" placeholder="Enter witness name (if any)" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="witness_contact" class="form-label">Witness Contact:</label>
                  <input type="text" id="witness_contact" name="witness_contact" placeholder="Enter witness contact details" class="form-control">
                </div>
                <!-- Additional Comments -->
                <div class="mb-3">
                  <label for="additional_comments" class="form-label">Additional Comments:</label>
                  <textarea id="additional_comments" name="additional_comments" rows="3" placeholder="Any additional information" class="form-control"></textarea>
                </div>
                <!-- Image Upload -->
                <div class="mb-4">
                  <label for="image" class="form-label">Upload Image (optional):</label>
                  <input type="file" id="image" name="image" accept="image/*" class="form-control">
                </div>
              </div>
            </div>
            <!-- Submit Button -->
            <div class="text-center">
              <button type="submit" class="btn btn-primary px-4 py-2">
                Submit Accident Report
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap JS Bundle (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
