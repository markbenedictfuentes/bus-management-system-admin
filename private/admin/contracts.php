
<div class="card p-3">
  <div class="card-body">
  <div class="card-header bg-light mb-2">
    <h2 class="h5 mb-0">Bus List</h2>
  </div>
    <div class="table-responsive">
      <table class="table table-striped table-sm" id="busStatusTable">
        <thead>
          <tr>
            <th>Bus Number</th>
            <th>Plate Number</th>
            <th>Capacity</th>
            <th>Bus Type</th>
            <th>Make</th>
            <th>Model</th>
            <th>Year</th>
            <th>Mileage</th>
            <th>Engine Type</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="10" class="text-center text-muted">Loading bus data...</td>
          </tr>
        </tbody>
      </table>
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
          var row = '<tr>' +
            '<td>' + bus.BusNumber + '</td>' +
            '<td>' + bus.PlateNumber + '</td>' +
            '<td>' + bus.Capacity + '</td>' +
            '<td>' + bus.BusType + '</td>' +
            '<td>' + bus.Make + '</td>' +
            '<td>' + bus.Model + '</td>' +
            '<td>' + bus.Year + '</td>' +
            '<td>' + bus.Mileage + '</td>' +
            '<td>' + bus.EngineType + '</td>' +
            '<td>' + bus.Status + '</td>' +
            '</tr>';
          tbody.append(row);
        });
      } else {
        tbody.append('<tr><td colspan="10" class="text-center text-muted">Walang available na bus data.</td></tr>');
      }
      
      $('#busStatusTable').DataTable({
        responsive: true
      });
    },
    error: function(xhr, status, error) {
      console.error('Error fetching bus data:', error);
      $('#busStatusTable tbody').html('<tr><td colspan="10" class="text-center text-danger">Error fetching bus data.</td></tr>');
    }
  });
});
</script>

