<?php
session_start();
include 'db_connect.php'; // Siguraduhin na tama ang path ng db_connect.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kumuha ng mga field mula sa form (for the safety table)
    $bus_number = trim($_POST['bus_number'] ?? '');
    $plate_number = trim($_POST['plate_number'] ?? '');
    $inspection_date = trim($_POST['inspection_date'] ?? '');
    $safety_score = trim($_POST['safety_score'] ?? '');
    $comments = trim($_POST['comments'] ?? '');
    
    // Checklist: gawing comma-separated string kung may napili
    $checklist = isset($_POST['checklist']) ? implode(',', $_POST['checklist']) : '';
    $bus_proof = NULL;
    if (isset($_FILES['bus_proof']) && $_FILES['bus_proof']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $filename = time() . "_" . basename($_FILES["bus_proof"]["name"]);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES["bus_proof"]["tmp_name"], $target_file)) {
            $bus_proof = $target_file;
        } else {
            echo "May error sa pag-upload ng proof para sa bus condition.";
            exit();
        }
    }
  
    $passenger_proof = NULL;
    if (isset($_FILES['passenger_proof']) && $_FILES['passenger_proof']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $filename = time() . "_" . basename($_FILES["passenger_proof"]["name"]);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES["passenger_proof"]["tmp_name"], $target_file)) {
            $passenger_proof = $target_file;
        } else {
            echo "May error sa pag-upload ng proof para sa passenger safety.";
            exit();
        }
    }
    
    // Prepare SQL for the safety table
    $stmt = $conn->prepare("
        INSERT INTO safety (
            bus_number, 
            plate_number, 
            inspection_date, 
            safety_score, 
            checklist, 
            comments, 
            bus_proof, 
            passenger_proof
        ) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "sssissss",
        $bus_number,
        $plate_number,
        $inspection_date,
        $safety_score,
        $checklist,
        $comments,
        $bus_proof,
        $passenger_proof
    );
    
    if ($stmt->execute()) {
        header("Location: safety.php?success=Safety inspection report submitted successfully");
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
  <title>Safety Manager Dashboard - BTMS Admin</title>
  <!-- Tailwind CSS for sidebar and topbar -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Bootstrap CSS for form (loaded after Tailwind) -->
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
    
    <main class="p-6">
      <h1 class="mb-4 text-3xl font-bold text-blue-600">Safety Manager Dashboard</h1>      
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
      
      <!-- Safety Inspection Report Form using Bootstrap grid -->
      <div class="card">
        <div class="card-header bg-primary text-white">
          <h2 class="h5 mb-0">Submit Safety Inspection Report</h2>
        </div>
        <div class="card-body">
          <form action="safety.php" method="POST" enctype="multipart/form-data">
            <!-- Row 1: Bus ID and Inspection Date -->
            <div class="row mb-3">
              <div class="col-md-6">
              <label for="bus_id" class="form-label">Bus ID / Plate Number</label>
              <select id="bus_id" name="bus_id" class="form-control" required>
                <option value="">Pumili ng Bus</option>
              </select>
              </div>
              <input type="hidden" id="bus_number" name="bus_number">
              <input type="hidden" id="plate_number" name="plate_number">
              <div class="col-md-6">
                <label for="inspection_date" class="form-label">Inspection Date</label>
                <input type="date" id="inspection_date" name="inspection_date" class="form-control" required>
              </div>
            </div>
            <!-- Row 2: Safety Score and Comments -->
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="safety_score" class="form-label">Safety Score</label>
                <input type="number" id="safety_score" name="safety_score" class="form-control" placeholder="Enter score (1-100)" min="1" max="100" required>
              </div>
              <div class="col-md-6">
                <label for="comments" class="form-label">Comments</label>
                <textarea id="comments" name="comments" class="form-control" placeholder="Enter comments about the inspection"></textarea>
              </div>
            </div>
            <!-- Row 3: Inspection Checklist (two columns) -->
            <div class="row mb-3">
              <div class="col-md-6">
                <div class="form-check mb-2">
                  <input type="checkbox" name="checklist[]" value="cctv_operation" class="form-check-input" id="checkCCTV">
                  <label for="checkCCTV" class="form-check-label">CCTV Operation Check</label>
                </div>
                <div class="form-check mb-2">
                  <input type="checkbox" name="checklist[]" value="emergency_equipment" class="form-check-input" id="checkEmergency">
                  <label for="checkEmergency" class="form-check-label">Emergency Equipment (Fire Extinguisher, First Aid Kit)</label>
                </div>
                <div class="form-check mb-2">
                  <input type="checkbox" name="checklist[]" value="passenger_seating" class="form-check-input" id="checkSeating">
                  <label for="checkSeating" class="form-check-label">Passenger Seating and Seatbelt Condition</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-check mb-2">
                  <input type="checkbox" name="checklist[]" value="exits_clear" class="form-check-input" id="checkExits">
                  <label for="checkExits" class="form-check-label">Emergency Exits Clear and Functional</label>
                </div>
                <div class="form-check mb-2">
                  <input type="checkbox" name="checklist[]" value="seatbelts" class="form-check-input" id="checkSeatbelts">
                  <label for="checkSeatbelts" class="form-check-label">Seatbelt Availability</label>
                </div>
              </div>
            </div>
            <!-- Row 4: Proof Uploads -->
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="bus_proof" class="form-label">Proof of Bus Condition</label>
                <input type="file" id="bus_proof" name="bus_proof" accept="image/*,application/pdf" class="form-control">
                <div class="form-text">Upload image or document for bus condition.</div>
              </div>
              <div class="col-md-6">
                <label for="passenger_proof" class="form-label">Proof of Passenger Safety</label>
                <input type="file" id="passenger_proof" name="passenger_proof" accept="image/*,application/pdf" class="form-control">
                <div class="form-text">Upload image or document for passenger safety.</div>
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
  
  <script>
  // Variable para isave ang bus data para magamit sa event listener
  let busData = [];

  // Kunin ang bus data mula sa API endpoint
  fetch('https://core1.nexfleetdynamics.com/api/buses')
    .then(response => response.json())
    .then(result => {
      if(result.success && Array.isArray(result.data)) {
        busData = result.data;
        const select = document.getElementById('bus_id');
        result.data.forEach(bus => {
          const option = document.createElement('option');
          option.value = bus.BusID;
          option.text = `${bus.BusNumber} - ${bus.PlateNumber}`;
          select.appendChild(option);
        });
      } else {
        console.error('Walang valid na data na nakuha.');
      }
    })
    .catch(error => console.error('Error fetching bus data:', error));

  // Kapag nagbago ang pagpili ng bus, i-update ang hidden inputs
  document.getElementById('bus_id').addEventListener('change', function() {
    const selectedId = this.value;
    const bus = busData.find(b => b.BusID == selectedId);
    if(bus) {
      document.getElementById('bus_number').value = bus.BusNumber;
      document.getElementById('plate_number').value = bus.PlateNumber;
    }
  });
</script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
