<div class="card p-4">

  <!-- (Opsyonal) Kung gusto mo magpakita ng success alert kapag nag-update ng status -->
  <?php if (isset($_GET['update']) && $_GET['update'] === 'success'): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Status Updated',
        text: 'Accident report status has been successfully updated.'
      });
    </script>
  <?php endif; ?>

  <div class="card mb-4">
    <div class="card-header bg-light">
      <h2 class="h5 mb-0">Accident Reports</h2>
    </div>
    <div class="card-body">
      <?php if (!empty($accident_reports)): ?>
        <div class="table-responsive">
          <table id="accidentTable" class="table table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>Employee ID</th>
                <th>Accident Date</th>
                <th>Location</th>
                <th>Description</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($accident_reports as $report): ?>
                <?php 
                  // I-format ang date at time
                  $dt = strtotime($report['accident_date']);
                  $formattedDate = date('F, d Y', $dt);  
                  $formattedTime = date('g:ia', $dt);      

                  // Tukuyin ang status; default ay "Pending" kung walang laman
                  $status = $report['status'] ?? 'Pending';
                  $badgeClass = '';

                  switch ($status) {
                    case 'Pending':
                      $badgeClass = 'bg-warning';
                      break;
                    case 'Review Accident':
                      $badgeClass = 'bg-info';
                      break;
                    case 'Resolved':
                      $badgeClass = 'bg-success';
                      break;
                    case 'Rejected':
                      $badgeClass = 'bg-danger';
                      break;
                    default:
                      $badgeClass = 'bg-secondary';
                      break;
                  }
                ?>
                <tr>
                  <td><?php echo $report['id']; ?></td>
                  <td><?php echo $report['employee_id']; ?></td>
                  <td><?php echo $formattedDate . ' ' . $formattedTime; ?></td>
                  <td><?php echo htmlspecialchars($report['location']); ?></td>
                  <td><?php echo substr($report['description'], 0, 50) . '...'; ?></td>
                  <td>
                    <span class="badge <?php echo $badgeClass; ?>">
                      <?php echo htmlspecialchars($status); ?>
                    </span>
                  </td>
                  <td>
                    <button type="button" class="btn btn-link text-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#viewAccidentModal<?php echo $report['id']; ?>">
                      <i class="fas fa-eye"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="text-muted">No accident reports found.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
  // I-initialize ang DataTable kung may laman ang accidentTable
  $(document).ready(function() {
    var table = $('#accidentTable');
    if (table.length) {
      table.DataTable({
        "order": [[ 0, "desc" ]]
      });
    }
  });
</script>
