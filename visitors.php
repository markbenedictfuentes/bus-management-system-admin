<?php
session_start();
include 'db_connect.php';

require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $address = $_POST['address'];
    $role    = "visitor"; // Awtomatikong visitor lang
    $password = bin2hex(random_bytes(4)); // Random generated password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO login (name, email, address, password, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $address, $hashed_password, $role);

    if ($stmt->execute()) {
        $mail = new PHPMailer(true);
        try {
            // SMTP settings (i-adjust ayon sa iyong configuration)
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'your_gmail@gmail.com'; // Palitan ng iyong Gmail address
            $mail->Password   = 'your_app_password';      // Palitan ng iyong Gmail App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('your_gmail@gmail.com', 'Your Company');
            $mail->addAddress($email, $name);

            $mail->isHTML(true);
            $mail->Subject = 'Your Account Details';
            $mail->Body    = "<p>Hi $name,</p>
                              <p>Your visitor account has been created successfully.</p>
                              <p><strong>Your password is: $password</strong></p>
                              <p>Please log in and change your password immediately for security reasons.</p>";
            $mail->AltBody = "Hi $name,\nYour visitor account has been created successfully.\nYour password is: $password\nPlease log in and change your password.";
            $mail->send();
            $_SESSION['success'] = "Registration successful! Your login details have been sent via email.";
        } catch (Exception $e) {
            $_SESSION['error'] = "Registration successful, but failed to send email. Error: " . $mail->ErrorInfo;
        }
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Registration failed: " . $stmt->error;
        header("Location: visitors.php");
        exit();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Global CSS at login.css (adjust paths ayon sa iyong setup) -->
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/login.css">
    <title>Visitor Registration</title>
</head>
<body>
    <div class="h-screen flex md:flex-row flex-col">
        <div class="lg:w-3/5 h-screen custom-py-1p lg:block hidden">
            <div class="bus-background bg-cover w-full h-full rounded-r-3xl"></div>
        </div>

        <div class="flex flex-col py-4 md:1/2 lg:w-2/5 w-full items-center">
            <p class="font-bold lg:text-4xl text-2xl w-full text-center text-[#00446b]">
                Bus Transportation Management System
            </p>
            <p class="font-semibold lg:text-3xl text-xl text-center mt-10 text-[#00446b]">
                &lt;Visitor Registration&gt;
            </p>

            <form class="xl:w-4/6 lg:w-5/6 sm:w-2/3 py-4 rounded-3xl shadow-lg shad mt-10 flex flex-col items-center border" action="registration.php" method="POST">
                <p class="text-center mb-4 text-xl text-[#00446b]">Register</p>
                <hr class="border w-full border-[#00446b]">

                <?php if (isset($_SESSION['error'])): ?>
                <div class="w-full bg-red-100 text-red-700 text-center p-2 rounded-md">
                    <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']); 
                    ?>
                </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])): ?>
                <div class="w-full bg-green-100 text-green-700 text-center p-2 rounded-md">
                    <?php
                    echo $_SESSION['success'];
                    unset($_SESSION['success']); 
                    ?>
                </div>
                <?php endif; ?>

                <div class="mt-8 w-4/5">
                    <input class="mt-1 block w-full bg-transparent rounded-md border p-2" type="text" name="name" placeholder="User Name" required>
                </div>
                <div class="mt-4 w-4/5">
                    <input class="mt-1 block w-full bg-transparent rounded-md border p-2" type="email" name="email" placeholder="Email" required>
                </div>
                <div class="mt-4 w-4/5">
                    <input class="mt-1 block w-full bg-transparent rounded-md border p-2" type="text" name="address" placeholder="Address" required>
                </div>
                <div class="flex items-center mt-4 mb-8 w-4/5">
                    <button type="submit" class="w-full font-medium p-2 rounded-md border bg-[#00446b]">
                        <p class="text-center text-white">Register</p>
                    </button>
                </div>
                <a class="text-sm hover:text-gray-300/50 rounded-md text-[#00446b]" href="index.php">Back to Login</a>
            </form>
        </div>
    </div>
</body>
</html>
