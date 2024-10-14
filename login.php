<?php
session_start();


$servername = "localhost";
$dbusername = "adminnex@localhost";  
$dbpassword = "macmac2323";  
$dbname = "admin_admin";  


$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


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


        header("Location: dashboard.php");
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
