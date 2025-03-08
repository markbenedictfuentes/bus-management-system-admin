<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Bus List</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="/styles/legal.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="/styles/poppins.css?v=<?php echo time(); ?>">
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
      margin-bottom: 10px;
    }
    .dataTables_wrapper .dataTables_filter input {
      border: 1px solid #d1d5db;
      border-radius: 0.375rem;
      padding: 0.4rem 0.6rem;
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
    #busStatusTable th, #busStatusTable td {
      padding: 0.4rem;
      font-size: 0.85rem;
    }
  </style>
</head>
<body class="h-screen flex bg-gray-100">
  <?php include '../../include/sidebar2.php'; ?>
  <div class="flex-1 flex flex-col">
    <?php include '../../include/topbar2.php'; ?>

    <div class="card1 p-3">
      <div class="card-body">
        <div class="card-header bg-gray-200 mb-2 p-2 rounded">
          <h2 class="text-lg font-semibold">Bus List</h2>
        </div>
        <div class="table-responsive">
          <table class="min-w-full divide-y divide-gray-200" id="busStatusTable">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bus Number</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plate Number</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terminal</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr>
                <td colspan="5" class="px-4 py-2 text-center text-gray-500">Loading bus data...</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Route Information Modal -->
    <div class="modal fade" id="routeModal" tabindex="-1" aria-labelledby="routeModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 id="routeModalLabel" class="modal-title">Route Information</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Route details will be injected here -->
            <ul class="list-group" id="routeInfoList"></ul>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

  </div>

  <script>
  $(document).ready(function() {
    $.ajax({
      url: 'https://core1.nexfleetdynamics.com/api/buses',
      type: 'GET',
      dataType: 'json',
      success: function(response) {
        var tbody = $('#busStatusTable tbody');
        tbody.empty();
        if(response.success && response.data.length > 0) {
          $.each(response.data, function(index, bus) {
            // Get terminal name and route data from nested objects
            var terminalName = bus.current_terminal ? bus.current_terminal.terminal_name : '';
            var routeData = bus.current_route ? bus.current_route : {};
            
            var row = '<tr>' +
              '<td class="px-4 py-2">' + bus.BusNumber + '</td>' +
              '<td class="px-4 py-2">' + bus.PlateNumber + '</td>' +
              '<td class="px-4 py-2">' + bus.Capacity + '</td>' +
              '<td class="px-4 py-2">' + terminalName + '</td>' +
              '<td class="px-4 py-2 text-center">' +
                // Only one action button for Route Info.
                '<button class="btn btn-primary btn-sm view-route" data-route=\'' + JSON.stringify(routeData) + '\' title="View Route Info"><i class="bi bi-info-circle"></i></button>' +
              '</td>' +
              '</tr>';
            tbody.append(row);
          });
        } else {
          tbody.append('<tr><td colspan="5" class="px-4 py-2 text-center text-gray-500">No bus data available.</td></tr>');
        }
        
        $('#busStatusTable').DataTable({
          responsive: true
        });
      },
      error: function(xhr, status, error) {
        console.error('Error fetching bus data:', error);
        $('#busStatusTable tbody').html('<tr><td colspan="5" class="px-4 py-2 text-center text-red-500">Error fetching bus data.</td></tr>');
      }
    });

    // Event delegation for route info button click
    $(document).on('click', '.view-route', function() {
      var routeData = $(this).data('route');
      var $list = $('#routeInfoList');
      $list.empty();
      // Loop through routeData and skip the following keys:
      // created_at, updated_at, RouteID, and Route_ID
      $.each(routeData, function(key, value) {
        if(key === 'created_at' || key === 'updated_at' || key === 'RouteID' || key === 'Route_ID') return;
        $list.append('<li class="list-group-item"><strong>' + key + ':</strong> ' + value + '</li>');
      });
      // Show modal
      var routeModal = new bootstrap.Modal(document.getElementById('routeModal'));
      routeModal.show();
    });
  });
  </script>
</body>
</html>
