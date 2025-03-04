<?php
session_start();
include 'db_connect.php';  // Siguraduhing tama ang path ng iyong DB connection

// Load PHPMailer classes
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to send OTP via email
function sendOTPEmail($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        // SMTP settings para sa Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'pagadora021@gmail.com ';    // Palitan ng iyong Gmail address
        $mail->Password   = 'abpx efyv fdtv roph';        // Palitan ng iyong Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender at recipient
        $mail->setFrom('pagadora021@gmail.com  ', 'SUPERBUS');
        $mail->addAddress($email);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "Your OTP for Password Reset";
        $mail->Body    = "<p>Your OTP for password reset is: <strong>$otp</strong></p>";
        $mail->AltBody = "Your OTP for password reset is: $otp";

        $mail->send();
        $_SESSION['success'] = "OTP sent successfully. Please check your email.";
    } catch (Exception $e) {
        $_SESSION['error'] = "Error sending OTP: {$mail->ErrorInfo}";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Hanapin ang user base sa email lang
    $stmt = $conn->prepare("SELECT * FROM login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Generate OTP (6-digit random number)
        $otp = rand(100000, 999999);
        $otpExpiration = date("Y-m-d H:i:s", strtotime("+15 minutes"));
        
        // I-update ang OTP at expiration sa database (siguraduhing may columns na 'otp' at 'otp_expiration' ang table mo)
        $update_stmt = $conn->prepare("UPDATE login SET otp = ?, otp_expiration = ? WHERE email = ?");
        $update_stmt->bind_param("sss", $otp, $otpExpiration, $email);
        if ($update_stmt->execute()) {
            sendOTPEmail($email, $otp);
            // I-redirect sa OTP verification page (halimbawa: verify_otp.php)
            header("Location: verify_otp.php?email=" . urlencode($email));
            exit();
        } else {
            $_SESSION['error'] = "Error saving OTP. Please try again.";
        }
        $update_stmt->close();
    } else {
        $_SESSION['error'] = "No account found with that email address.";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Your Company</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/login.css">
</head>
<body class="font-poppins">
    <div class="h-screen flex md:flex-row flex-col">
        <div class="lg:w-3/5 h-screen custom-py-1p lg:block hidden">
            <div class="bus-background bg-cover w-full h-full rounded-r-3xl"></div>
        </div>
        <!-- Right side: Forgot Password Form -->
        <div class="flex flex-col py-4 md:1/2 lg:w-2/5 w-full items-center">
            <p class="font-bold lg:text-4xl text-2xl w-full text-center text-[#00446b]">Bus Transportation Management System</p>
            <p class="font-semibold lg:text-3xl text-xl text-center mt-10 text-[#00446b]">&lt;Forgot Password&gt;</p>
            
            <form class="xl:w-4/6 lg:w-5/6 sm:w-2/3 py-4 rounded-3xl shadow-lg mt-10 flex flex-col items-center border" action="forgot.php" method="POST">
                <p class="text-center mb-4 text-xl text-[#00446b]">Reset Your Password</p>
                <hr class="border w-full border-[#00446b]">
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="w-full bg-red-100 text-red-700 text-center p-2 rounded-md mt-4">
                        <?php
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="w-full bg-green-100 text-green-700 text-center p-2 rounded-md mt-4">
                        <?php
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <div class="mt-8 w-4/5">
                    <input class="mt-1 block w-full bg-transparent rounded-md border p-2" type="email" name="email" placeholder="Enter your email address" required>
                </div>
                <div class="flex items-center mt-8 mb-8 w-4/5">
                    <button type="submit" class="w-full font-medium p-2 rounded-md border bg-[#00446b]">
                        <p class="text-center text-white">Request OTP</p>
                    </button>
                </div>
                <a class="text-sm hover:text-gray-300/50 rounded-md text-[#00446b]" href="login.php">Back to Login</a>
            </form>
        </div>
    </div>
</body>
</html>
