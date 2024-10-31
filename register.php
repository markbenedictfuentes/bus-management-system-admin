<?php

$servername = "localhost";
$dbusername = "admin_macmac";
$dbpassword = "macmac2323";
$dbname = "admin_admin";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = "shincipherishere1@gmail.com";  
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
