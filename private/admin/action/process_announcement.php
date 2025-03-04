<?php
session_start();
include 'db_connect.php';  // Adjust the path to db_connect if needed
                                // (../ goes up one directory)

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kunin ang title at content
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    // Mag-prepare para sa upload (kung may image)
    $imageName = "";
    if (!empty($_FILES['image']['name'])) {
        // Folder kung saan isa-save ang images
        $targetDir = "uploads/announcements/";
        
        // Pang-generate ng unique filename (para iwas overwrite)
        $temp = explode(".", $_FILES['image']['name']);
        $newFileName = uniqid() . '_' . md5(time()) . '.' . end($temp);

        $targetFilePath = $targetDir . $newFileName;

        // Pwede ka maglagay ng validation (file size, file type, etc.)
        // For now, simple upload lang:
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            $imageName = $newFileName; // Ito ang isasave sa DB
        } else {
            $_SESSION['error'] = "Error uploading image.";
            header("Location: ../announcement.php");
            exit;
        }
    }

    // I-save sa DB
    $sql = "INSERT INTO announcements (title, content, image) 
            VALUES ('$title', '$content', '$imageName')";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Announcement added successfully!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }

    // Balik sa announcement.php
    header("Location: ../announcement.php");
    exit;
} else {
    // Kung hindi POST, i-redirect nalang
    header("Location: ../announcement.php");
    exit;
}
