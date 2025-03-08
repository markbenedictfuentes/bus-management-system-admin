<?php
session_start();
include 'db_connect.php'; // Siguraduhing tama ang path

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    
    if ($action === 'disable') {
        // I-update ang user para maging disabled
        $sql = "UPDATE login SET is_disabled = 1 WHERE id = ?";
    } elseif ($action === 'enable') {
        // I-update ang user para maging active
        $sql = "UPDATE login SET is_disabled = 0 WHERE id = ?";
    } else {
        $_SESSION['error'] = "Invalid action specified.";
        header("Location: registration.php");
        exit();
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "User status updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update user status: " . $stmt->error;
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
