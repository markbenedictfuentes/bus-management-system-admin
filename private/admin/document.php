<?php 
session_start();
include 'db_connect.php'; // Gamit ang MySQLi connection ($conn)

$message = "";
$maxFileSize = 50 * 1024 * 1024; // Maximum file size: 50MB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kunin ang Document Information
    $documentTitle       = trim($_POST['documentTitle'] ?? '');
    $documentDescription = trim($_POST['documentDescription'] ?? '');
    $docType             = $_POST['docType'] ?? '';
    $confidential        = isset($_POST['confidential']) ? 'Yes' : 'No';
    $referenceID         = trim($_POST['referenceID'] ?? '');
    $documentDate        = trim($_POST['documentDate'] ?? '');
    
    // Kunin ang document password kung confidential
    $documentPassword    = trim($_POST['documentPassword'] ?? '');
    
    // Basic Validation: Document Title ay required
    if (empty($documentTitle)) {
        $message .= "Document Title ay kinakailangan. ";
    }
    
    // Kapag confidential, tiyakin na mayroon password
    if ($confidential === 'Yes' && empty($documentPassword)) {
        $message .= "Kapag confidential, kailangan ilagay ang password para sa dokumento. ";
    }
    
    // Suriin kung may file na na-upload at walang error
    if (isset($_FILES['document'])) {
        if ($_FILES['document']['error'] !== UPLOAD_ERR_OK) {
            $message .= "Sorry, may error sa pag-upload ng file. ";
        } else {
            // File size validation
            if ($_FILES['document']['size'] > $maxFileSize) {
                $message .= "File size exceeds maximum allowed limit of 50MB. ";
            }
            
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            // Kunin ang original filename at extension
            $originalFilename = basename($_FILES["document"]["name"]);
            $fileExtension    = strtolower(pathinfo($originalFilename, PATHINFO_EXTENSION));
            
            // Unique naming: gamit ang uniqid para hindi mag-overwrite
            $uniqueFilename = uniqid("doc_", true) . "." . $fileExtension;
            $target_file    = $target_dir . $uniqueFilename;
            
            // Allowed file extensions
            $allowedExtensions = array("pdf", "doc", "docx", "xls", "xlsx");
            if (!in_array($fileExtension, $allowedExtensions)) {
                $message .= "Only PDF, DOC, DOCX, XLS, XLSX files are allowed. ";
            }
            
            // MIME type validation gamit ang finfo
            $finfo    = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $_FILES["document"]["tmp_name"]);
            finfo_close($finfo);
            $allowedMimeTypes = array(
                "application/pdf",
                "application/msword",
                "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                "application/vnd.ms-excel",
                "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
            );
            if (!in_array($mimeType, $allowedMimeTypes)) {
                $message .= "Invalid file type uploaded. ";
            }
            
            // Kung walang error, ilipat ang file at i-save ang data sa database
            if (empty($message)) {
                if (move_uploaded_file($_FILES["document"]["tmp_name"], $target_file)) {
                    // Kung confidential, hash muna ang password bago i-save
                    $passwordHash = ($confidential === 'Yes') ? password_hash($documentPassword, PASSWORD_DEFAULT) : null;
                    
                    $sql = "INSERT INTO case_documents 
                            (document_title, document_description, doc_type, confidential, file_path, reference_id, document_date, document_password)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    if (!$stmt) {
                        die("Prepare failed: " . $conn->error);
                    }
                    // Lahat ay strings: bind_param("ssssssss", ...)
                    $stmt->bind_param("ssssssss", $documentTitle, $documentDescription, $docType, $confidential, $target_file, $referenceID, $documentDate, $passwordHash);
                    if ($stmt->execute()) {
                        $message .= "File <strong>" . htmlspecialchars($originalFilename) . "</strong> successfully uploaded as <strong>" . htmlspecialchars($uniqueFilename) . "</strong> and stored in the database.";
                    } else {
                        $message .= "Execute error: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $message .= "Sorry, may error sa pag-upload ng file. ";
                }
            }
        }
    } else {
        $message .= "Walang file na na-upload. ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Upload Document for Case Management</title>
  <!-- Tailwind CSS CDN (para sa development lang) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome CDN for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="/styles/documents.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="/styles/poppins.css?v=<?php echo time(); ?>">
  <script>
    // Ipakita o itago ang password input base sa confidential checkbox
    document.addEventListener("DOMContentLoaded", function() {
      const confidentialCheckbox = document.getElementById("confidential");
      const passwordField = document.getElementById("passwordField");
      
      function togglePasswordField() {
        if (confidentialCheckbox.checked) {
          passwordField.style.display = "block";
        } else {
          passwordField.style.display = "none";
        }
      }
      
      confidentialCheckbox.addEventListener("change", togglePasswordField);
      // Set initial state
      togglePasswordField();
    });
  </script>
</head>

<body class="h-screen flex bg-gray-100">
  <?php include '../../include/sidebar2.php'; ?>
  <div class="flex-1 flex flex-col">
    <?php include '../../include/topbar2.php'; ?>
    <!-- Wrapper para i-center ang form -->
    <div class="flex justify-center items-center flex-1">
      <!-- Pinalawak ang lapad ng form gamit ang max-w-4xl -->
      <div class="w-full max-w-4xl bg-white p-10 shadow-xl rounded-lg">
        <h2 class="text-3xl font-bold mb-8 text-gray-800 flex items-center gap-3">
          <i class="fa-solid fa-file-upload"></i>
          Upload New Document
        </h2>
        
        <!-- Ipapakita ang SweetAlert alert kung may message -->
        <?php if (!empty($message)): ?>
        <script>
          // Tukuyin kung icon ay success o error base sa message (simpleng check)
          let swalIcon = <?= strpos($message, 'successfully uploaded') !== false ? "'success'" : "'error'" ?>;
          Swal.fire({
            title: 'Upload Result',
            html: '<?= addslashes($message) ?>',
            icon: swalIcon,
            confirmButtonText: 'OK'
          });
        </script>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data" class="space-y-8">
          <!-- Document Information -->
          <div class="p-6 border border-gray-200 rounded-lg shadow-sm">
            <h4 class="text-xl font-semibold mb-4 text-gray-800 flex items-center gap-2">
              <i class="fa-solid fa-file-lines"></i>
              Document Information
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="relative">
                <label for="documentTitle" class="block text-sm font-medium text-gray-700 mb-1">
                  <i class="fa-solid fa-heading"></i> Document Title:
                </label>
                <input type="text" name="documentTitle" id="documentTitle" placeholder="Hal. Bus Contract, Case File" class="mt-1 block w-full pl-10 border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
              </div>
              <div>
                <label for="docType" class="block text-sm font-medium text-gray-700 mb-1">
                  <i class="fa-solid fa-list"></i> Document Category:
                </label>
                <select name="docType" id="docType" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                  <option value="bus">Bus Document</option>
                  <option value="case">Case Management File</option>
                  <option value="legal">Legal Document</option>
                  <option value="others">Others</option>
                </select>
              </div>
              <div class="md:col-span-2">
                <label for="documentDescription" class="block text-sm font-medium text-gray-700 mb-1">
                  <i class="fa-solid fa-comment"></i> Document Description:
                </label>
                <textarea name="documentDescription" id="documentDescription" rows="3" placeholder="Maikling paliwanag o nilalaman ng dokumento..." class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
              </div>
              <div class="flex items-center">
                <input type="checkbox" name="confidential" id="confidential" class="form-checkbox text-blue-600">
                <label for="confidential" class="ml-2 text-gray-700">
                  <i class="fa-solid fa-lock"></i> Confidential (Authorized Users Only)
                </label>
              </div>
              <!-- Password field para sa confidential document -->
              <div id="passwordField" class="relative md:col-span-2" style="display:none;">
                <label for="documentPassword" class="block text-sm font-medium text-gray-700 mb-1">
                  <i class="fa-solid fa-key"></i> Document Password:
                </label>
                <input type="password" name="documentPassword" id="documentPassword" placeholder="Ilagay ang password para sa dokumento" class="mt-1 block w-full pl-10 border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
              </div>
            </div>
          </div>

          <!-- Additional Document Details -->
          <div class="p-6 border border-gray-200 rounded-lg shadow-sm">
            <h4 class="text-xl font-semibold mb-4 text-gray-800 flex items-center gap-2">
              <i class="fa-solid fa-info-circle"></i> Additional Details
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="relative">
                <label for="referenceID" class="block text-sm font-medium text-gray-700 mb-1">
                  <i class="fa-solid fa-hashtag"></i> Reference ID:
                </label>
                <input type="text" name="referenceID" id="referenceID" placeholder="Optional reference or case number" class="mt-1 block w-full pl-10 border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
              </div>
              <div class="relative">
                <label for="documentDate" class="block text-sm font-medium text-gray-700 mb-1">
                  <i class="fa-solid fa-calendar"></i> Document Date:
                </label>
                <input type="date" name="documentDate" id="documentDate" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
              </div>
            </div>
          </div>

          <!-- File Upload -->
          <div class="p-6 border border-gray-200 rounded-lg shadow-sm">
            <label for="document" class="block text-sm font-medium text-gray-700 mb-1">
              <i class="fa-solid fa-file"></i> Select Document:
            </label>
            <input type="file" name="document" id="document" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <p class="text-xs text-gray-500 mt-2">Allowed file types: PDF, DOC, DOCX, XLS, XLSX. Maximum file size: 50MB.</p>
          </div>

          <div>
            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-200 text-lg font-semibold">
              <i class="fa-solid fa-upload"></i> Upload Document
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
