<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/src/Exception.php';
require 'vendor/src/PHPMailer.php';
require 'vendor/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Check if email exists in the database
    $conn = new mysqli("localhost", "admin_macmac", "macmac2323", "admi_admin");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id FROM login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        // Generate reset token and expiry time
        $token = bin2hex(random_bytes(32));
        $expire = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Store the reset token and expiry time in the database
        $stmt->close();
        $stmt = $conn->prepare("UPDATE login SET token = ?, expire = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expire, $email);
        $stmt->execute();

        // Send the reset email
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'admin.nexfleetdynamics.com'; // Set the SMTP server to send through
            $mail->SMTPAuth = true;
            $mail->Username = 'macmac@mail.admin.nexfleetdynamics.com'; // SMTP username
            $mail->Password = 'macmac'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('your_email@example.com', 'NexFleet Dynamics');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = 'Click on the link below to reset your password: <br><br>' .
                '<a href="https://admin.nexfleetdynamics.com/reset_password.php?token=' . $token . '">Reset Password</a><br><br>' .
                'This link will expire in 1 hour.';

            $mail->send();
            echo 'Password reset link has been sent to your email address.';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo 'No user found with that email address.';
    }

    $stmt->close();
    $conn->close();
}
?>
