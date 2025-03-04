<?php
// Filename: admin_verify_otp.php

include 'db_connect.php';  // Database connection

$error = "";  // Initialize error message variable

// Siguraduhing may email parameter sa URL
if (isset($_GET['email']) && !empty($_GET['email'])) {
    $email = htmlspecialchars($_GET['email']);  // Sanitize the email
} else {
    echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Error</title>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
</head>
<body>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Email parameter is missing. Please try again.'
        }).then(() => {
            window.location.href = '/admin_login/admin_reset_pass.php';
        });
    </script>
</body>
</html>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp = $_POST['otp'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate if passwords match
    if ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match.";
    } elseif (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', $newPassword)) {
        $error = "Password must be 8-12 characters long, include letters, numbers, and special characters.";
    } else {
        // Check if OTP is valid
        $stmt = $conn->prepare("SELECT otp, otp_expiration, password FROM login WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user && $user['otp'] === $otp && strtotime($user['otp_expiration']) > time()) {
                // Check if new password is same as old one
                if (password_verify($newPassword, $user['password'])) {
                    $error = "New password cannot be the same as the old password.";
                } else {
                    // OTP is valid and new password is different from the old one
                    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                    // Update password and clear OTP
                    $update_stmt = $conn->prepare("UPDATE login SET password = ?, otp = NULL, otp_expiration = NULL WHERE email = ?");
                    if ($update_stmt) {
                        $update_stmt->bind_param("ss", $newPasswordHash, $email);
                        if ($update_stmt->execute()) {
                            echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Password Reset</title>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
</head>
<body>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Password reset successful!'
        }).then(() => {
            window.location.href = 'index.php';
        });
    </script>
</body>
</html>";
                            exit();
                        } else {
                            $error = "Failed to update password.";
                        }
                    }
                }
            } else {
                $error = "Invalid or expired OTP.";
            }
            $stmt->close();
        } else {
            $error = "Failed to execute query.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password | Your Company</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" 
          integrity="sha512-Fo3rlrZj/k7ujTTXRNv6+2W6Mk+T8KxN5wC8xErzW8T5zJQWj8G6P4qT6ZkO9nUqvF5wFj5Cj5Q+7B3d1Bb5FQ==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Custom CSS (kung mayroon) -->
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/login.css">
</head>
<body class="bg-gray-100 font-poppins">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Left side: Background image (palitan ang URL ng iyong image) -->
        <div class="hidden lg:block lg:w-2/5 bg-cover" style="background-image: url('your-image.jpg');">
        </div>
        <!-- Right side: Verify OTP Form -->
        <div class="flex flex-col justify-center items-center w-full lg:w-3/5 p-6">
            <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold text-center text-[#00446b]">Reset Password</h2>
                <p class="text-center text-gray-600 mt-2">Enter your OTP and new password below</p>
                <?php if(!empty($error)): ?>
                    <div class="mt-4 bg-red-200 text-red-700 p-2 rounded text-center">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <form action="verify_otp.php?email=<?php echo urlencode($email); ?>" method="POST" class="mt-6 space-y-4">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    
                    <!-- OTP Field -->
                    <div>
                        <input type="text" name="otp" placeholder="Enter OTP" required class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($otp) ? htmlspecialchars($otp) : ''; ?>">
                    </div>
                    
                    <!-- New Password Field -->
                    <div class="relative">
                        <input type="password" id="newPassword" name="newPassword" placeholder="New Password" required class="w-full p-2 border border-gray-300 rounded pr-10">
                        <button type="button" onclick="togglePasswordVisibility('newPassword')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600">
                            <i class="fas fa-eye" id="icon-newPassword"></i>
                        </button>
                    </div>
                    
                    <!-- Confirm New Password Field -->
                    <div class="relative">
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm New Password" required class="w-full p-2 border border-gray-300 rounded pr-10">
                        <button type="button" onclick="togglePasswordVisibility('confirmPassword')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600">
                            <i class="fas fa-eye" id="icon-confirmPassword"></i>
                        </button>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-[#00446b] text-white p-2 rounded hover:bg-blue-700 transition">
                        Reset Password
                    </button>
                </form>
                <div class="text-center mt-6">
                    <a href="index.php" class="text-sm text-[#00446b] hover:underline">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Toggle Password Visibility Script -->
    <script>
        function togglePasswordVisibility(id) {
            const passwordField = document.getElementById(id);
            const icon = document.getElementById("icon-" + id);
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>
