<?php
session_start();
include 'db_connect.php'; // Siguraduhing tama ang path

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $id      = intval($_POST['id']);
    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $address = $_POST['address'];
    $role    = $_POST['role'];
    
    $sql = "UPDATE login SET name = ?, email = ?, address = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $email, $address, $role, $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "User updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update user: " . $stmt->error;
    }
    $stmt->close();
    header("Location: registration.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: registration.php");
    exit();
}
?>
