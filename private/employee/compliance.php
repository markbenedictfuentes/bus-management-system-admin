<?php
session_start();
include 'db_connect.php'; // Siguraduhin na tama ang path ng db_connect.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kunin ang mga field mula sa form gamit ang bagong bus_name at plate_number
    $bus_name     = trim($_POST['bus_name'] ?? '');
    $plate_number = trim($_POST['plate_number'] ?? '');
    $status       = trim($_POST['status'] ?? '');
    $updated_at   = trim($_POST['updated_at'] ?? '');
    $maintenance  = trim($_POST['maintenance'] ?? '');
    $notes        = trim($_POST['notes'] ?? '');
    
    // Checklist: gawing comma-separated string kung may napili
    $checklist = isset($_POST['checklist']) ? implode(',', $_POST['checklist']) : '';
    
    // File upload para sa dokumento
    $document_filename = NULL;
    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $filename = time() . "_" . basename($_FILES["document"]["name"]);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES["document"]["tmp_name"], $target_file)) {
            $document_filename = $target_file;
        } else {
            echo "May error sa pag-upload ng dokumento.";
            exit();
        }
    }
    
    // In-update na SQL query: ginagamit na ang bus_name at plate_number
    $stmt = $conn->prepare("INSERT INTO compliance (bus_name, plate_number, status, updated_at, checklist, maintenance, notes, document) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("ssssssss", $bus_name, $plate_number, $status, $updated_at, $checklist, $maintenance, $notes, $document_filename);
    
    if ($stmt->execute()) {
        header("Location: compliance.php?success=Compliance updated successfully");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Compliance Officer Dashboard - BTMS Admin</title>
  <!-- Tailwind CSS para sa sidebar at topbar -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Bootstrap CSS para sa form (i-load pagkatapos ng Tailwind) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="/styles/form.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="/styles/poppins.css?v=<?php echo time(); ?>">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="h-screen flex bg-gray-100">
  <?php include '../../include/sidebar2.php'; ?>
  <div class="flex-1 flex flex-col">
    <?php include '../../include/topbar2.php'; ?>
    
    <main class="p-3">
      <?php if(isset($_GET['success'])): ?>
      <script>
          Swal.fire({
              icon: 'success',
              title: 'Success!',
              text: '<?php echo $_GET['success']; ?>',
              confirmButtonText: 'Ok'
          });
      </script>
      <?php endif; ?>
      
      <!-- Compliance Update Form gamit ang Bootstrap grid -->
      <div class="card">
        <div class="card-header bg-primary text-white">
          <h2 class="h5 mb-0">Update Detailed Compliance Status</h2>
        </div>
        <div class="card-body">
          <form action="compliance.php" method="POST" enctype="multipart/form-data">
            <!-- Row 1: Bus Name / Plate Number Drop Down at Compliance Status -->
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="bus_select" class="form-label">Bus Name / Plate Number</label>
                <select id="bus_select" name="bus_select" class="form-control" required>
                  <option value="">Pumili ng Bus</option>
                </select>
                <!-- Hidden inputs para sa bus_name at plate_number -->
                <input type="hidden" id="bus_name" name="bus_name">
                <input type="hidden" id="plate_number" name="plate_number">
              </div>
              <div class="col-md-6">
                <label for="status" class="form-label">Compliance Status</label>
                <select id="status" name="status" class="form-select" required>
                  <option value="">Select Status</option>
                  <option value="compliant">Compliant</option>
                  <option value="non-compliant">Non-Compliant</option>
                </select>
              </div>
            </div>
            <!-- Row 2: Last Checked Date and Document Upload -->
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="updated_at" class="form-label">Last Checked Date</label>
                <input type="datetime-local" id="updated_at" name="updated_at" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label for="document" class="form-label">Upload Supporting Document</label>
                <input type="file" id="document" name="document" class="form-control">
              </div>
            </div>
            <!-- Row 3: Maintenance and Repair Notes & Additional Notes -->
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="maintenance" class="form-label">Maintenance and Repair Notes</label>
                <textarea id="maintenance" name="maintenance" class="form-control" placeholder="Enter details about any maintenance or repair issues"></textarea>
              </div>
              <div class="col-md-6">
                <label for="notes" class="form-label">Additional Notes</label>
                <textarea id="notes" name="notes" class="form-control" placeholder="Enter any additional comments"></textarea>
              </div>
            </div>
            <!-- Row 4: Inspection Checklist arranged into two columns -->
            <div class="row mb-3">
              <div class="col-md-6">
                <div class="form-check mb-2">
                  <input type="checkbox" name="checklist[]" value="brakes" class="form-check-input" id="checkBrakes">
                  <label for="checkBrakes" class="form-check-label">Brake Functionality</label>
                </div>
                <div class="form-check mb-2">
                  <input type="checkbox" name="checklist[]" value="engine" class="form-check-input" id="checkEngine">
                  <label for="checkEngine" class="form-check-label">Engine Performance</label>
                </div>
                <div class="form-check mb-2">
                  <input type="checkbox" name="checklist[]" value="fire_extinguisher" class="form-check-input" id="checkFireExt">
                  <label for="checkFireExt" class="form-check-label">Fire Extinguisher Condition</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-check mb-2">
                  <input type="checkbox" name="checklist[]" value="emergency_exits" class="form-check-input" id="checkExits">
                  <label for="checkExits" class="form-check-label">Emergency Exits Functionality</label>
                </div>
                <div class="form-check mb-2">
                  <input type="checkbox" name="checklist[]" value="seatbelts" class="form-check-input" id="checkSeatbelts">
                  <label for="checkSeatbelts" class="form-check-label">Seatbelt Availability</label>
                </div>
              </div>
            </div>
            <!-- Submit Button -->
            <div class="d-flex justify-content-end">
              <button type="submit" class="btn btn-success">Submit Report</button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
  
  <!-- Fetch bus data from API at your integration endpoint -->
  <script>
    let busData = [];
    fetch('https://core1.nexfleetdynamics.com/api/buses')
      .then(response => response.json())
      .then(result => {
        if(result.success && Array.isArray(result.data)) {
          busData = result.data;
          const select = document.getElementById('bus_select');
          result.data.forEach(bus => {
            const option = document.createElement('option');
            // I-set ang value bilang concatenated string: BusNumber|PlateNumber
            option.value = bus.BusNumber + '|' + bus.PlateNumber;
            option.text = bus.BusNumber + ' - ' + bus.PlateNumber;
            select.appendChild(option);
          });
        } else {
          console.error('Walang valid na data na nakuha.');
        }
      })
      .catch(error => console.error('Error fetching bus data:', error));

    // Kapag nagbago ang pagpili ng bus, i-update ang hidden inputs
    document.getElementById('bus_select').addEventListener('change', function() {
      const value = this.value;
      if(value){
        const parts = value.split('|');
        document.getElementById('bus_name').value = parts[0];
        document.getElementById('plate_number').value = parts[1];
      } else {
        document.getElementById('bus_name').value = '';
        document.getElementById('plate_number').value = '';
      }
    });
  </script>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
