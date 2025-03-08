<div class="card p-4">
<div class="card">
  <div class="card-header bg-light">
    <h2 class="h5 mb-0">Safety Regulation</h2>
  </div>
  <div class="card-body">
    <!-- Print Button -->
    <div class="text-end mb-3">
      <a href="print_records.php" target="_blank" class="btn btn-secondary">
        <i class="fas fa-print"></i> Print Records
      </a>
    </div>
    <div class="table-responsive">
      <table id="safetyTable" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Bus Name</th>
            <th>Bus Plate</th>
            <th>Inspection Date</th>
            <th>Safety Score</th>
            <th>Comments</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($safety_records)): ?>
            <?php foreach ($safety_records as $record): ?>
              <tr>
                <td><?php echo $record['id']; ?></td>
                <td><?php echo htmlspecialchars($record['bus_number']); ?></td>
                <td><?php echo htmlspecialchars($record['plate_number']); ?></td>
                <td><?php echo date("F d Y", strtotime($record['inspection_date'])); ?></td>
                <td>
                  <span class="badge bg-<?php echo ($record['safety_score'] >= 80) ? 'success' : 'danger'; ?>">
                    <?php echo htmlspecialchars($record['safety_score']); ?>
                  </span>
                </td>
                <td><?php echo htmlspecialchars($record['comments']); ?></td>
                <td>
                  <?php if (!empty($record['bus_proof']) || !empty($record['passenger_proof'])): ?>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewSafetyModal<?php echo $record['id']; ?>">
                      <i class="fas fa-eye"></i> View
                    </button>
                  <?php else: ?>
                    <span class="text-muted"><i class="fas fa-eye-slash"></i> No Proof</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center">No safety records found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>