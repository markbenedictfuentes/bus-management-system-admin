<?php
session_start();
include 'db_connect.php'; // Siguraduhing ang $conn ay MySQLi connection

$sql = "SELECT * FROM case_documents ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>View Case Documents</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <style>
    body, table, th, td {
      font-family: 'Poppins', sans-serif;
    }
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
    .dataTables_wrapper .dataTables_filter input {
      border: 1px solid #d1d5db;
      border-radius: 0.375rem;
      padding: 0.25rem 0.5rem;
      font-size: 0.7rem;
    }
    #documentTable tbody td {
      font-size: 0.8rem;
    }
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
      font-size: 0.7rem;
    }
    .dataTables_wrapper .dataTables_length label {
      font-size: 0.7rem;
      color: #4a5568;
      font-weight: 500;
    }
    .dataTables_wrapper .dataTables_length select {
      padding: 0.25rem 0.5rem;
      font-size: 0.7rem;
      border: 1px solid #d1d5db;
      border-radius: 0.375rem;
      background-color: #f7fafc;
      color: #4a5568;
      outline: none;
    }
    .modal {
      position: fixed;
      inset: 0;
      display: none;
      align-items: center;
      justify-content: center;
      background-color: rgba(0,0,0,0.5);
      z-index: 50;
    }
    .modal.active {
      display: flex;
    }
  </style>
</head>
<body class="h-screen flex bg-gray-100">
  <?php include '../../include/sidebar2.php'; ?>
  <div class="flex-1 flex flex-col">
    <?php include '../../include/topbar2.php'; ?>
    <div class="p-6">
      <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-500 px-6 py-4">
          <h3 class="text-xl font-semibold text-white">Document List</h3>
        </div>
        <div class="overflow-x-auto p-6" style="margin-bottom: 20px">
          <table id="documentTable" class="min-w-full divide-y divide-gray-300">
            <thead class="bg-blue-500">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Document Title</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Document Type</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Document Date</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Confidential</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Created At</th>
                <th class="px-4 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-xs font-light text-gray-600">
              <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                  <tr class="hover:bg-gray-50 transition duration-200">
                    <td class="px-4 py-3 whitespace-nowrap"><?php echo htmlspecialchars($row['document_title']); ?></td>
                    <td class="px-4 py-3 whitespace-nowrap"><?php echo htmlspecialchars($row['doc_type']); ?></td>
                    <td class="px-4 py-3 whitespace-nowrap">
                      <?php echo strtolower(date("F, d Y", strtotime($row['document_date']))); ?>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap"><?php echo htmlspecialchars($row['confidential']); ?></td>
                    <td class="px-4 py-3 whitespace-nowrap">
                      <?php echo strtolower(date("F, d Y g:iA", strtotime($row['created_at']))); ?>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-center">
                      <div class="flex items-center justify-center space-x-3">
                        <?php if ($row['confidential'] === 'Yes'): ?>
                          <button class="download-btn text-purple-500 hover:text-purple-700" 
                                  data-id="<?php echo $row['id']; ?>" 
                                  data-file="<?php echo htmlspecialchars($row['file_path']); ?>"
                                  title="Download">
                            <i class="fa-solid fa-download"></i>
                          </button>
                        <?php else: ?>
                          <a href="<?php echo htmlspecialchars($row['file_path']); ?>" download class="text-purple-500 hover:text-purple-700" title="Download">
                            <i class="fa-solid fa-download"></i>
                          </a>
                        <?php endif; ?>
                        <a href="update_docu.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:text-blue-700" title="Update">
                          <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td class="px-4 py-3 text-center" colspan="6">No records found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  
  <div id="downloadModal" class="modal">
    <div class="bg-white rounded-lg shadow-xl p-6 w-11/12 md:w-1/3">
      <h3 class="text-xl font-bold mb-4">Enter Document Password</h3>
      <input type="password" id="modalPassword" class="w-full p-2 border border-gray-300 rounded-lg mb-4" placeholder="Password">
      <div class="flex justify-end space-x-2">
        <button id="modalCancel" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600">Cancel</button>
        <button id="modalSubmit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Submit</button>
      </div>
      <input type="hidden" id="docId">
      <input type="hidden" id="docFile">
    </div>
  </div>
  
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    $(document).ready(function() {
      $('#documentTable').DataTable({
        "pagingType": "simple_numbers",
        "language": {
          "search": "",
          "searchPlaceholder": "Search records..."
        }
      });
      
      $('.download-btn').on('click', function() {
        const docId = $(this).data('id');
        const docFile = $(this).data('file');
        $('#docId').val(docId);
        $('#docFile').val(docFile);
        $('#modalPassword').val('');
        $('#downloadModal').addClass('active');
      });
      
      $('#modalCancel').on('click', function() {
        $('#downloadModal').removeClass('active');
      });
      
      $('#modalSubmit').on('click', function() {
        const docId = $('#docId').val();
        const docFile = $('#docFile').val();
        const password = $('#modalPassword').val();
        
        $.ajax({
          url: 'verify_download.php',
          type: 'POST',
          dataType: 'json',
          data: {
            id: docId,
            password: password
          },
          success: function(response) {
            if (response.success) {
              window.location.href = docFile;
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Invalid Password',
                text: response.message
              });
            }
          },
          error: function() {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'May error sa pag-verify ng password.'
            });
          },
          complete: function() {
            $('#downloadModal').removeClass('active');
          }
        });
      });
    });
  </script>
</body>
</html>
