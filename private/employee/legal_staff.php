<?php
session_start();
include 'db_connect.php'; // Siguraduhin na tama ang path ng db_connect.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kumuha ng mga field mula sa form
    $document_title = trim($_POST['document_title'] ?? '');
    $document_type = trim($_POST['document_type'] ?? '');
    $document_description = trim($_POST['document_description'] ?? '');
    $effective_date = trim($_POST['effective_date'] ?? '');
    $expiry_date = trim($_POST['expiry_date'] ?? '');
    $document_status = trim($_POST['document_status'] ?? '');
    $reviewer_comments = trim($_POST['reviewer_comments'] ?? '');
    
    // File upload para sa document file
    $document_file = NULL;
    if (isset($_FILES['document_file']) && $_FILES['document_file']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        // Lagyan ng timestamp ang filename upang maiwasan ang duplicate
        $filename = time() . "_" . basename($_FILES["document_file"]["name"]);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES["document_file"]["tmp_name"], $target_file)) {
            $document_file = $target_file;
        } else {
            echo "May error sa pag-upload ng document file.";
            exit();
        }
    } else {
        echo "Walang file na na-upload.";
        exit();
    }
    
    // Ihanda ang SQL statement gamit ang prepared statement
    $stmt = $conn->prepare("INSERT INTO legal_documents (document_title, document_type, document_description, effective_date, expiry_date, document_file, document_status, reviewer_comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("ssssssss", $document_title, $document_type, $document_description, $effective_date, $expiry_date, $document_file, $document_status, $reviewer_comments);
    
    if ($stmt->execute()) {
        // Redirect pabalik sa parehong page na may success GET parameter
        header("Location: legal_staff.php?success=Document uploaded successfully");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Legal Department Dashboard - BTMS Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/styles/form.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/styles/poppins.css?v=<?php echo time(); ?>">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="h-screen flex bg-gray-100">

  <?php include '../../include/sidebar2.php'; ?>
  
  <div class="flex-1 flex flex-col">
    <!-- Topbar gamit ang Tailwind -->
    <?php include '../../include/topbar2.php'; ?>
    
    <main class="p-6">
      <h1 class="mb-4 text-3xl font-bold text-blue-600">Legal Department Dashboard</h1>
      <!-- SweetAlert para sa success message -->
      <?php if (isset($_GET['success'])): ?>
      <script>
          Swal.fire({
              icon: 'success',
              title: 'Success!',
              text: '<?php echo $_GET['success']; ?>',
              confirmButtonText: 'Ok'
          });
      </script>
      <?php endif; ?>
      
      <!-- Legal Document Upload Form -->
      <div class="card">
        <div class="card-header bg-primary text-white">
          <h2 class="h5 mb-0">Upload Legal Document</h2>
        </div>
        <div class="card-body">
          <form action="legal_staff.php" method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="document_title" class="form-label">Document Title</label>
                <input type="text" id="document_title" name="document_title" class="form-control" placeholder="Enter document title" required>
              </div>
              <div class="col-md-6">
                <label for="document_type" class="form-label">Document Type</label>
                <select id="document_type" name="document_type" class="form-select" required>
                  <option value="">Select type</option>
                  <option value="contract">Contract</option>
                  <option value="regulation">Regulation</option>
                  <option value="policy">Policy</option>
                </select>
              </div>
            </div>
            
            <div class="mb-3">
              <label for="document_description" class="form-label">Document Description</label>
              <textarea id="document_description" name="document_description" class="form-control" placeholder="Provide a detailed description of the document" required></textarea>
            </div>
            
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="effective_date" class="form-label">Effective Date</label>
                <input type="date" id="effective_date" name="effective_date" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label for="expiry_date" class="form-label">Expiry Date</label>
                <input type="date" id="expiry_date" name="expiry_date" class="form-control">
                <div class="form-text">Optional: Ilagay kung kailan mawawala ang bisa ng dokumento.</div>
              </div>
            </div>
            
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="document_file" class="form-label">Upload File</label>
                <input type="file" id="document_file" name="document_file" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label for="document_status" class="form-label">Document Status</label>
                <select id="document_status" name="document_status" class="form-select" required>
                  <option value="">Select status</option>
                  <option value="active">Active</option>
                  <option value="archived">Archived</option>
                  <option value="pending_review">Pending Review</option>
                </select>
              </div>
            </div>
            
            <div class="mb-3">
              <label for="reviewer_comments" class="form-label">Reviewer Comments</label>
              <textarea id="reviewer_comments" name="reviewer_comments" class="form-control" placeholder="Enter any reviewer comments or notes"></textarea>
            </div>
            
            <div class="d-flex justify-content-end">
              <button type="submit" class="btn btn-success">Upload Document</button>
            </div>
          </form>
        </div>
      </div>
      
    </main>
  </div>
  
  <!-- Bootstrap JS Bundle (kasama ang Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
