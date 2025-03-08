<div class="card p-4">
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center bg-light">
    <h2 class="h5 mb-0">Case Management</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newCaseModal">
      <i class="fas fa-plus me-2"></i>Add New Case
    </button>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="caseTable" class="table table-striped">
        <thead>
          <tr>
            <th>Case ID</th>
            <th>Title</th>
            <th>Status</th>
            <th>Assigned</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>101</td>
            <td>Case Example</td>
            <td>Ongoing</td>
            <td>Lawyer 1</td>
            <td>2023-03-15</td>
          </tr>
          <!-- More rows as needed -->
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
