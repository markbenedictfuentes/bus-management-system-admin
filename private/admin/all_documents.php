<?php
session_start();
include 'db_connect.php'; // Siguraduhing ang $conn ay MySQLi connection

$sql = "SELECT * FROM employee_documents ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>View Employee Documents</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- DataTables CSS CDN -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
  <!-- Google Fonts: Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body, table, th, td {
      font-family: 'Poppins', sans-serif;
    }
    /* Custom DataTables styling */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
      background: #e2e8f0;
      border-radius: 0.375rem;
      margin: 0 0.25rem;
      padding: 0.25rem 0.5rem; /* pinaliit na padding */
      border: none;
      cursor: pointer;
      font-size: 0.7rem; /* pinaliit na font size */
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
      background: #4299e1;
      color: white !important;
      font-size: 0.7rem;
    }
    .dataTables_wrapper .dataTables_filter input {
      border: 1px solid #d1d5db;
      border-radius: 0.375rem;
      padding: 0.25rem 0.5rem; /* pinaliit na padding */
      font-size: 0.7rem; /* pinaliit na font size */
    }
    /* Custom CSS para paliitin ang text sa tbody cells */
    #employeeTable tbody td {
      font-size: 0.8rem;
    }
    /* Paliitin ang info text at pagination container text */
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
      font-size: 0.7rem;
    }
    .dataTables_wrapper .dataTables_length label {
  font-size: 0.7rem; /* Pinaliit ang font size */
  color: #4a5568; /* Gray-700 */
  font-weight: 500;
}
.dataTables_wrapper .dataTables_length select {
  padding: 0.25rem 0.5rem; /* Pinaliit na padding */
  font-size: 0.7rem; /* Pinaliit ang font size */
  border: 1px solid #d1d5db; /* Gray-300 border */
  border-radius: 0.375rem; /* Rounded corners */
  background-color: #f7fafc; /* Gray-100 background */
  color: #4a5568; /* Gray-700 */
  outline: none;
}

  </style>
</head>
<body class="h-screen flex bg-gray-100">
  <?php include '../../include/sidebar2.php'; ?>
  <div class="flex-1 flex flex-col">
    <?php include '../../include/topbar.php'; ?>
    <div class="p-6">
      <h2 class="text-3xl font-semibold mb-8 text-gray-800">Employee Documents</h2>
      <!-- Card Wrapper -->
      <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <!-- Table Header -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-500 px-6 py-4">
          <h3 class="text-xl font-semibold text-white">Document List</h3>
        </div>
        <!-- Table Container -->
        <div class="overflow-x-auto p-6">
          <table id="employeeTable" class="min-w-full divide-y divide-gray-300">
            <thead class="bg-blue-500">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Employee Name</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Employee ID</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Document Title</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Created At</th>
                <th class="px-4 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-xs font-light text-gray-600">
              <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                  <tr class="hover:bg-gray-50 transition duration-200">
                    <td class="px-4 py-3 whitespace-nowrap"><?php echo htmlspecialchars($row['employee_name']); ?></td>
                    <td class="px-4 py-3 whitespace-nowrap"><?php echo htmlspecialchars($row['employee_id']); ?></td>
                    <td class="px-4 py-3 whitespace-nowrap"><?php echo htmlspecialchars($row['document_title']); ?></td>
                    <td class="px-4 py-3 whitespace-nowrap"><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td class="px-4 py-3 whitespace-nowrap text-center">
                      <div class="flex items-center justify-center space-x-3">
                        <!-- Download Action -->
                        <a href="<?php echo htmlspecialchars($row['file_path']); ?>" download class="text-purple-500 hover:text-purple-700" title="Download">
                          <i class="fa-solid fa-download"></i>
                        </a>
                        <!-- Update Action -->
                        <a href="update.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:text-blue-700" title="Update">
                          <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <!-- Delete Action -->
                        <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?');" class="text-red-500 hover:text-red-700" title="Delete">
                          <i class="fa-solid fa-trash"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="px-4 py-3 text-center">No records found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- jQuery CDN -->
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <!-- DataTables JS CDN -->
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <!-- Font Awesome JS CDN for icons -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#employeeTable').DataTable({
        "pagingType": "simple_numbers",
        "language": {
          "search": "",
          "searchPlaceholder": "Search records..."
        }
      });
    });
  </script>
</body>
</html>
