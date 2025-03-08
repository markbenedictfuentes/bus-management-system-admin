<?php
session_start();
include 'db_connect.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM login WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];    
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role']; // inirehistro ang role

        // Role-based redirection
        if ($user['role'] === 'admin') {
            header("Location: dashboard.php"); // Admin dashboard (root folder)
        } elseif ($user['role'] === 'staff') {
            header("Location: private/employee/dashboard.php");
        } elseif ($user['role'] === 'visitor') {
            header("Location: private/visitors/dashboard.php");
        } elseif ($user['role'] === 'employee') {
            header("Location: private/employee/dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $_SESSION['error'] = "Incorrect password. Please try again.";
        header("Location: index.php");
        exit();
    }
} else {
    $_SESSION['error'] = "No account found with that email.";
    header("Location: index.php");
    exit();
}

$stmt->close();
$conn->close();
?>
