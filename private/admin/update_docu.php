<?php 
session_start();
include 'db_connect.php';

$message = "";
$record = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['id'] ?? '');
    $documentTitle       = trim($_POST['documentTitle'] ?? '');
    $documentDescription = trim($_POST['documentDescription'] ?? '');
    $docType             = $_POST['docType'] ?? '';
    $referenceID         = trim($_POST['referenceID'] ?? '');
    $documentDate        = trim($_POST['documentDate'] ?? '');
    
    if (empty($documentTitle)) {
        $message .= "Document Title ay kinakailangan. ";
    }
    
    if (empty($message)) {
        $sql = "UPDATE case_documents 
                SET document_title = ?, 
                    document_description = ?, 
                    doc_type = ?, 
                    reference_id = ?, 
                    document_date = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("sssssi", $documentTitle, $documentDescription, $docType, $referenceID, $documentDate, $id);
        if ($stmt->execute()) {
            $message .= "Document updated successfully.";
        } else {
            $message .= "Error updating document: " . $stmt->error;
        }
        $stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && isset($_GET['id'])) {
    $id = trim($_GET['id']);
    $sql = "SELECT * FROM case_documents WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
         $record = $result->fetch_assoc();
    } else {
         $message .= "Document not found.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Update Document</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="h-screen flex bg-gray-100">
  <?php include '../../include/sidebar2.php'; ?>
  <div class="flex-1 flex flex-col">
    <?php include '../../include/topbar2.php'; ?>
    <div class="flex justify-center items-center flex-1">
      <div class="w-full max-w-4xl bg-white p-10 shadow-xl rounded-lg">
        <h2 class="text-3xl font-bold mb-8 text-gray-800 flex items-center gap-3">
          <i class="fa-solid fa-file-pen"></i> Update Document
        </h2>
        <?php if (!empty($message)): ?>
        <script>
          let swalIcon = <?= strpos($message, 'successfully') !== false ? "'success'" : "'error'" ?>;
          Swal.fire({
            title: 'Update Result',
            html: '<?= addslashes($message) ?>',
            icon: swalIcon,
            confirmButtonText: 'OK'
          }).then(function(){ window.location = "all_documents.php"; });
        </script>
        <?php endif; ?>
        
        <?php if ($record): ?>
        <form action="" method="POST" class="space-y-8">
          <input type="hidden" name="id" value="<?= htmlspecialchars($record['id']) ?>">
          <div class="p-6 border border-gray-200 rounded-lg shadow-sm">
            <h4 class="text-xl font-semibold mb-4 text-gray-800 flex items-center gap-2">
              <i class="fa-solid fa-file-lines"></i> Document Information
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="relative">
                <label for="documentTitle" class="block text-sm font-medium text-gray-700 mb-1">
                  <i class="fa-solid fa-heading"></i> Document Title:
                </label>
                <input type="text" name="documentTitle" id="documentTitle" value="<?= htmlspecialchars($record['document_title']) ?>" placeholder="Hal. Bus Contract, Case File" class="mt-1 block w-full pl-10 border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
              </div>
              <div>
                <label for="docType" class="block text-sm font-medium text-gray-700 mb-1">
                  <i class="fa-solid fa-list"></i> Document Category:
                </label>
                <select name="docType" id="docType" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                  <option value="bus" <?= $record['doc_type'] == 'bus' ? 'selected' : '' ?>>Bus Document</option>
                  <option value="case" <?= $record['doc_type'] == 'case' ? 'selected' : '' ?>>Case Management File</option>
                  <option value="legal" <?= $record['doc_type'] == 'legal' ? 'selected' : '' ?>>Legal Document</option>
                  <option value="others" <?= $record['doc_type'] == 'others' ? 'selected' : '' ?>>Others</option>
                </select>
              </div>
              <div class="md:col-span-2">
                <label for="documentDescription" class="block text-sm font-medium text-gray-700 mb-1">
                  <i class="fa-solid fa-comment"></i> Document Description:
                </label>
                <textarea name="documentDescription" id="documentDescription" rows="3" placeholder="Maikling paliwanag o nilalaman ng dokumento..." class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($record['document_description']) ?></textarea>
              </div>
            </div>
          </div>
          <div class="p-6 border border-gray-200 rounded-lg shadow-sm">
            <h4 class="text-xl font-semibold mb-4 text-gray-800 flex items-center gap-2">
              <i class="fa-solid fa-info-circle"></i> Additional Details
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="relative">
                <label for="referenceID" class="block text-sm font-medium text-gray-700 mb-1">
                  <i class="fa-solid fa-hashtag"></i> Reference ID:
                </label>
                <input type="text" name="referenceID" id="referenceID" value="<?= htmlspecialchars($record['reference_id']) ?>" placeholder="Optional reference or case number" class="mt-1 block w-full pl-10 border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
              </div>
              <div class="relative">
                <label for="documentDate" class="block text-sm font-medium text-gray-700 mb-1">
                  <i class="fa-solid fa-calendar"></i> Document Date:
                </label>
                <input type="date" name="documentDate" id="documentDate" value="<?= htmlspecialchars($record['document_date']) ?>" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
              </div>
            </div>
          </div>
          <div>
            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-200 text-lg font-semibold">
              <i class="fa-solid fa-pen-to-square"></i> Update Document
            </button>
          </div>
        </form>
        <?php else: ?>
          <p class="text-center text-gray-600">Document not found.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
