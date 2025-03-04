<?php 
session_start();
include 'db_connect.php'; // Ginagamit dito ang MySQLi connection ($conn)

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kunin ang Employee Information
    $employeeName = trim($_POST['employeeName'] ?? '');
    $employeeID = trim($_POST['employeeID'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $position = trim($_POST['position'] ?? '');

    // Kunin ang Document Information
    $documentTitle = trim($_POST['documentTitle'] ?? '');
    $documentDescription = trim($_POST['documentDescription'] ?? '');
    $docType = $_POST['docType'] ?? '';
    $confidential = isset($_POST['confidential']) ? 'Yes' : 'No';

    // Basic Validation
    if (empty($employeeName) || empty($employeeID) || empty($documentTitle)) {
        $message .= "Employee Name, Employee ID, at Document Title ay kinakailangan. ";
    }
    
    // Suriin kung may file na na-upload at walang error
    if (isset($_FILES['document'])) {
        if ($_FILES['document']['error'] !== UPLOAD_ERR_OK) {
            $message .= "Sorry, may error sa pag-upload ng file.";
        } else {
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            // I-sanitize ang filename gamit ang basename
            $filename = basename($_FILES["document"]["name"]);
            $target_file = $target_dir . $filename;
            $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            // Suriin kung umiiral na ang file
            if (file_exists($target_file)) {
                $message .= "File already exists. ";
            }
            
            // Pahintulutang file types
            $allowed = array("pdf", "doc", "docx", "xls", "xlsx");
            if (!in_array($fileType, $allowed)) {
                $message .= "Only PDF, DOC, DOCX, XLS, XLSX files are allowed. ";
            }
            
            // Kung walang error, ilipat ang file at i-save ang data sa database
            if (empty($message)) {
                if (move_uploaded_file($_FILES["document"]["tmp_name"], $target_file)) {
                    $sql = "INSERT INTO employee_documents 
                            (employee_name, employee_id, department, position, document_title, document_description, doc_type, confidential, file_path) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    if (!$stmt) {
                        die("Prepare failed: " . $conn->error);
                    }
                    // Bind parameters: lahat ay strings ("s")
                    $stmt->bind_param("sssssssss", $employeeName, $employeeID, $department, $position, $documentTitle, $documentDescription, $docType, $confidential, $target_file);
                    if ($stmt->execute()) {
                        $message .= "File <strong>" . htmlspecialchars($filename) . "</strong> successfully uploaded and stored in the database for <strong>" . htmlspecialchars($employeeName) . "</strong>.";
                    } else {
                        $message .= "Execute error: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $message .= "Sorry, may error sa pag-upload ng file.";
                }
            }
        }
    } else {
        $message .= "Walang file na na-upload.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Upload Employee Document</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome CDN for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="/styles/documents.css?v=<?php echo time(); ?>">
</head>

<body class="h-screen flex bg-gray-100">
  <?php include '../../include/sidebar2.php'; ?>
  <div class="flex-1 flex flex-col">
    <?php include '../../include/topbar.php'; ?>
    <!-- Wrapper para i-center ang form -->
    <div class="flex justify-center items-center flex-1">
      <!-- Pinalawak ang lapad ng form gamit ang max-w-4xl -->
      <div class="w-full max-w-4xl bg-white p-10 shadow-xl rounded-lg">
        <h2 class="text-3xl font-bold mb-8 text-gray-800 flex items-center gap-3">
          <i class="fa-solid fa-file-upload"></i>
          Upload New Employee Document
        </h2>
        
        <?php if (!empty($message)): ?>
          <div class="mb-6 p-4 bg-green-100 text-green-800 rounded shadow">
            <?php echo $message; ?>
          </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data" class="space-y-8">
          <!-- Employee Information -->
          <div class="p-6 border border-gray-200 rounded-lg shadow-sm">
            <h4 class="text-xl font-semibold mb-4 text-gray-800 flex items-center gap-2">
              <i class="fa-solid fa-user"></i>
              Employee Information
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="relative">
                <label for="employeeName" class="block text-sm font-medium text-gray-700 mb-1">
                  <i class="fa-solid fa-user"></i> Employee Name:
                </label>
                <input type="text" name="employeeName" id="employeeName" placeholder="Juan Dela Cruz" class="mt-1 block w-full pl-10 border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
              </div>
              <div class="relative">
                <label for="employeeID" class="block text-sm font-medium text-gray-700 mb-1">
                  <i class="fa-solid fa-id-badge"></i> Employee ID:
                </label>
                <input type="text" name="employeeID" id="employeeID" placeholder="EMP12345" class="mt-1 block w-full pl-10 border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
              </div>
              <div>
                <label for="department" class="block text-sm font-medium text-gray-700 mb-1">
                  <i class="fa-solid fa-building"></i> Department:
                </label>
                <select name="department" id="department" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                  <option value="HR">HR</option>
                  <option value="Finance">Finance</option>
                  <option value="IT">IT</option>
                  <option value="Operations">Operations</option>
                </select>
              </div>
              <div>
                <label for="position" class="block text-sm font-medium text-gray-700 mb-1">
                  <i class="fa-solid fa-briefcase"></i> Position:
                </label>
                <input type="text" name="position" id="position" placeholder="Job Title" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
              </div>
            </div>
          </div>

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
                <input type="text" name="documentTitle" id="documentTitle" placeholder="Hal. Employee Contract" class="mt-1 block w-full pl-10 border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
              </div>
              <div>
                <label for="docType" class="block text-sm font-medium text-gray-700 mb-1">
                  <i class="fa-solid fa-list"></i> Document Category:
                </label>
                <select name="docType" id="docType" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                  <option value="contract">Contract</option>
                  <option value="memo">Memo</option>
                  <option value="report">Report</option>
                  <option value="legal">Legal Document</option>
                  <option value="payslip">Payslip</option>
                  <option value="policy">Policy</option>
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
            </div>
          </div>

          <!-- File Upload -->
          <div class="p-6 border border-gray-200 rounded-lg shadow-sm">
            <label for="document" class="block text-sm font-medium text-gray-700 mb-1">
              <i class="fa-solid fa-file"></i> Select Document:
            </label>
            <input type="file" name="document" id="document" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
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
