<?php
session_start();
include 'db_connect.php';

// Suriin kung may id na ipinasa sa URL
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: announcement.php");
    exit();
}

$id = intval($_GET['id']);

// Kunin muna ang announcement upang masigurong ito ay umiiral
$query = "SELECT * FROM announcements WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "Announcement not found.";
    header("Location: announcement.php");
    exit();
}

$announcement = mysqli_fetch_assoc($result);

// Kung may image, tanggalin ang file mula sa uploads folder
if (!empty($announcement['image'])) {
    $imagePath = "action/uploads/announcements/" . $announcement['image'];
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

// I-delete ang announcement mula sa database
$deleteQuery = "DELETE FROM announcements WHERE id = $id";
if (mysqli_query($conn, $deleteQuery)) {
    $_SESSION['success'] = "Announcement deleted successfully.";
} else {
    $_SESSION['error'] = "Failed to delete announcement.";
}

header("Location: announcement.php");
exit();
?>
