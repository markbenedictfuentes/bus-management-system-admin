<?php
session_start();
include 'db_connect.php';  // Siguraduhing tama ang path ng iyong DB connection

// Load PHPMailer via Composer's autoloader
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kunin ang inputs mula sa form
    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $address = $_POST['address'];
    $role    = $_POST['role'];

    // Awtomatikong mag-generate ng random password (8 characters)
    $password = bin2hex(random_bytes(4)); // halimbawa: "a3f5c9d2"
    
    // I-hash ang password bago ito i-save sa database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // I-save ang user sa database (siguraduhing tugma ang column names)
    $sql = "INSERT INTO login (name, email, address, password, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $address, $hashed_password, $role);

    if ($stmt->execute()) {
        // Magpadala ng email gamit ang PHPMailer
        $mail = new PHPMailer(true);
        try {
            // SMTP settings para sa Gmail
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'pagadora021@gmail.com'; // Palitan ng iyong Gmail address
            $mail->Password   = 'abpx efyv fdtv roph';     // Palitan ng iyong Gmail App Password
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
            $_SESSION['error'] = "Registration successful, but failed to send email. Error: {$mail->ErrorInfo}";
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
$conn->close();
?>

<?php include 'include/header.php'; ?>


<body class="bg-gray-100">
  <!-- Registration Form Container -->
  <div class="container mx-auto px-4 py-12">
    <!-- Pinalaking form container gamit ang max-w-xl at dagdag na padding -->
    <div class="bg-white rounded shadow-md max-w-xl mx-auto p-10">
      <div class="text-center mb-6">
        <div class="icon-circle">
          <i class="fas fa-user-plus"></i>
        </div>
        <h2 class="text-2xl font-bold text-center">Registration Form</h2>
      </div>
      
      <?php
        if(isset($_SESSION['error'])) {
          echo "<div class='mb-4 p-3 bg-red-200 text-red-800 rounded'><i class='fas fa-exclamation-circle mr-2'></i>" . $_SESSION['error'] . "</div>";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])) {
          echo "<div class='mb-4 p-3 bg-green-200 text-green-800 rounded'><i class='fas fa-check-circle mr-2'></i>" . $_SESSION['success'] . "</div>";
          unset($_SESSION['success']);
        }
      ?>
      
      <form method="POST" action="registration.php" class="space-y-4">
        <div class="input-container">
          <label class="block text-gray-700">User Name:</label>
          <div class="input-with-icon">
            <i class="fas fa-user"></i>
            <input type="text" name="name" required class="mt-1 block w-full border border-gray-300 rounded p-2 pl-10">
          </div>
        </div>
        
        <div class="input-container">
          <label class="block text-gray-700">Email:</label>
          <div class="input-with-icon">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" required class="mt-1 block w-full border border-gray-300 rounded p-2 pl-10">
          </div>
        </div>
        
        <div class="input-container">
          <label class="block text-gray-700">Address:</label>
          <div class="input-with-icon">
            <i class="fas fa-map-marker-alt"></i>
            <input type="text" name="address" required class="mt-1 block w-full border border-gray-300 rounded p-2 pl-10">
          </div>
        </div>
        
        <div class="input-container">
          <label class="block text-gray-700">Role:</label>
          <div class="input-with-icon">
            <i class="fas fa-user-tag"></i>
            <select name="role" required class="mt-1 block w-full border border-gray-300 rounded p-2 pl-10">
              <option value="admin">Admin</option>
              <option value="staff">Staff</option>
              <option value="employee">Employee</option>
              <option value="visitor">Visitor</option>
            </select>
          </div>
        </div>
        
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
          <i class="fas fa-user-plus mr-2"></i> Register
        </button>
      </form>
      
    </div>
  </div>
</body>
</html>
