<?php
session_start();
include 'db_connect.php'; 

require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to add user (gamitin ang parehong function mo)
function addUser($conn) {
    // Kunin ang inputs mula sa form
    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $address = $_POST['address'];
    $role    = $_POST['role'];

    $password = bin2hex(random_bytes(4)); 

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO login (name, email, address, password, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $address, $hashed_password, $role);

    if ($stmt->execute()) {
        $mail = new PHPMailer(true);
        try {
            // SMTP settings para sa Gmail
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'pagadora021@gmail.com'; // Palitan ng iyong Gmail address
            $mail->Password   = 'abpxefyvfdtvroph';        // Palitan ng iyong Gmail App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('pagadora021@gmail.com', 'Your Company');
            $mail->addAddress($email, $name);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Your Account Details';
            $mail->Body    = "<p>Hi $name,</p>
                              <p>Your account has been created successfully.</p>
                              <p><strong>Your password is: $password</strong></p>
                              <p>Please log in and change your password immediately for security reasons.</p>";
            $mail->AltBody = "Hi $name,\nYour account has been created successfully.\nYour password is: $password\nPlease log in and change your password.";
            $mail->send();
            $_SESSION['success'] = "Registration successful! Your login details have been sent via email.";
        } catch (Exception $e) {
            $_SESSION['error'] = "Registration successful, but failed to send email. Error: " . $mail->ErrorInfo;
        }
        header("Location: registration.php");
        exit();
    } else {
        $_SESSION['error'] = "Registration failed: " . $stmt->error;
        header("Location: registration.php");
        exit();
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    addUser($conn);
}

$sql = "SELECT * FROM login";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Users</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
  <style>
     body, table, th, td {
      font-family: 'Poppins', sans-serif;
    }
    #userTable td {
      padding: 0.3rem !important;
      font-size: 0.8rem !important;
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
    .dataTables_wrapper .dataTables_filter input {
      border: 1px solid #d1d5db;
      border-radius: 0.375rem;
      padding: 0.25rem 0.5rem;
      font-size: 0.7rem;
    }
    #employeeTable tbody td {
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
    a:hover {
  text-decoration: underline;
  }

  </style>
</head>
<body>
  <!-- Header / Top Bar -->
  <?php include 'include/header.php'; ?>

  <div class="container flex-grow-1">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#registrationModal">
        <i class="fas fa-user-plus"></i> Add New User
      </button>
    </div>

    <?php
      if(isset($_SESSION['error'])) {
          echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
          unset($_SESSION['error']);
      }
      if(isset($_SESSION['success'])) {
          echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
          unset($_SESSION['success']);
      }
    ?>

    <!-- Card para sa table -->
    <div class="card">
      <div class="card-header bg-light">
        <h4 class="mb-0">User List</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="userTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?php echo $row['id']; ?></td>
                  <td><?php echo htmlspecialchars($row['name']); ?></td>
                  <td><?php echo htmlspecialchars($row['email']); ?></td>
                  <td><?php echo htmlspecialchars($row['address']); ?></td>
                  <td><?php echo htmlspecialchars($row['role']); ?></td>
                  <td>
                    <?php 
                      if(isset($row['is_disabled']) && $row['is_disabled'] == 1) {
                        echo '<span class="badge badge-danger">Disabled</span>';
                      } else {
                        echo '<span class="badge badge-success">Active</span>';
                      }
                    ?>
                  </td>
                  <td>
                    <!-- Update Button: Nagbubukas ng modal form -->
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#updateModal<?php echo $row['id']; ?>">
                      <i class="fas fa-edit"></i>
                    </button>
                    <!-- Disable/Enable Button gamit ang Swal confirmation -->
                    <?php if(isset($row['is_disabled']) && $row['is_disabled'] == 1): ?>
                      <button type="button" class="btn btn-danger btn-sm" onclick="handleUserStatus(<?php echo $row['id']; ?>, 'enable')">
                        <i class="fas fa-check"></i>
                      </button>
                    <?php else: ?>
                      <button type="button" class="btn btn-warning btn-sm" onclick="handleUserStatus(<?php echo $row['id']; ?>, 'disable')">
                        <i class="fas fa-ban"></i>
                      </button>
                    <?php endif; ?>
                  </td>
                </tr>

                <!-- Modal Update Form -->
                <div class="modal fade" id="updateModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <form method="POST" action="update_user.php">
                        <div class="modal-header">
                          <h5 class="modal-title" id="updateModalLabel<?php echo $row['id']; ?>">Update User</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                          <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" class="form-control" required>
                          </div>
                          <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" class="form-control" required>
                          </div>
                          <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" value="<?php echo htmlspecialchars($row['address']); ?>" class="form-control" required>
                          </div>
                          <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control" required>
                              <option value="admin" <?php echo ($row['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                              <option value="staff" <?php echo ($row['role'] == 'staff') ? 'selected' : ''; ?>>Staff</option>
                              <option value="employee" <?php echo ($row['role'] == 'employee') ? 'selected' : ''; ?>>Employee</option>
                              <option value="visitor" <?php echo ($row['role'] == 'visitor') ? 'selected' : ''; ?>>Visitor</option>
                            </select>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                          <button type="submit" name="update_user" class="btn btn-primary">Save Changes</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Registration Modal -->
  <div class="modal fade" id="registrationModal" tabindex="-1" role="dialog" aria-labelledby="registrationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form method="POST" action="registration.php">
          <div class="modal-header">
            <h5 class="modal-title" id="registrationModalLabel">Register New User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- Registration form fields -->
            <div class="form-group">
              <label>User Name:</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-user"></i></span>
                </div>
                <input type="text" name="name" required class="form-control" placeholder="Enter user name">
              </div>
            </div>
            <div class="form-group">
              <label>Email:</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <input type="email" name="email" required class="form-control" placeholder="Enter email">
              </div>
            </div>
            <div class="form-group">
              <label>Address:</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                </div>
                <input type="text" name="address" required class="form-control" placeholder="Enter address">
              </div>
            </div>
            <div class="form-group">
              <label>Role:</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                </div>
                <select name="role" required class="form-control">
                  <option value="admin">Admin</option>
                  <option value="staff">Staff</option>
                  <option value="employee">Employee</option>
                  <option value="visitor">Visitor</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-user-plus"></i> Register
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- jQuery, Popper.js, Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

  <script>
    // Initialize DataTables with responsive option
    $(document).ready(function() {
      $('#userTable').DataTable({
        responsive: true
      });
    });

    // Function gamit ang SweetAlert2 para sa confirmation ng disable/enable
    function handleUserStatus(id, action) {
      let textMsg = (action === 'disable') 
                    ? "Are you sure you want to disable this user? They will no longer be able to login." 
                    : "Are you sure you want to enable this user?";
      let confirmButtonText = (action === 'disable') ? "Yes, disable it!" : "Yes, enable it!";
      
      Swal.fire({
        title: 'Confirm Action',
        text: textMsg,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: confirmButtonText,
        cancelButtonText: "Cancel"
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "disabled_user.php?id=" + id + "&action=" + action;
        }
      });
    }
  </script>
</body>
</html>
