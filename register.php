<?php

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "admin";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = "admin@admin.com";  
$name = "admin";
$password = "admin";  
$hashed_password = password_hash($password, PASSWORD_DEFAULT);


$sql = "INSERT INTO login (email, name, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $email, $name, $hashed_password);

if ($stmt->execute()) {
    echo "New user registered successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
