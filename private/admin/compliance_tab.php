<div class="card p-4">
  <div class="card">
    <div class="card-header bg-light">
      <h2 class="h5 mb-0">Compliance Data</h2>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="complianceTable" class="table table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Bus Name</th>
              <th>Plate Number</th>
              <th>Status</th>
              <th>Notes</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($compliance_records as $record): ?>
              <tr>
                <td><?php echo $record['id']; ?></td>
                <td><?php echo htmlspecialchars($record['bus_name']); ?></td>
                <td><?php echo htmlspecialchars($record['plate_number']); ?></td>
                <td><?php echo ucfirst($record['status']); ?></td>
                <td><?php echo htmlspecialchars($record['notes']); ?></td>
                <td>
                  <button type="button" class="btn btn-link text-primary"
                          data-bs-toggle="modal"
                          data-bs-target="#viewComplianceModal<?php echo $record['id']; ?>">
                    <i class="fas fa-eye"></i>
                  </button>
                  <?php if (!empty($record['document'])): ?>
                    <a href="/private/employee/uploads/<?php echo basename($record['document']); ?>" 
                       download="<?php echo basename($record['document']); ?>" 
                       class="btn btn-link text-primary">
                      <i class="fas fa-download"></i>
                    </a>
                  <?php else: ?>
                    <span class="text-muted"><i class="fas fa-download"></i></span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
