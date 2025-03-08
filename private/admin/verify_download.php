<?php
session_start();
include 'db_connect.php'; // Siguraduhin na konektado ang database

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $docId = $_POST['id'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($docId) || empty($password)) {
        $response['message'] = 'Document id and Password are needed.';
        echo json_encode($response);
        exit;
    }
    
    // Kunin ang hashed password mula sa database para sa document na ito
    $stmt = $conn->prepare("SELECT document_password FROM case_documents WHERE id = ?");
    $stmt->bind_param("i", $docId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['document_password'];
        if (password_verify($password, $hashedPassword)) {
            $response['success'] = true;
            $response['message'] = 'Success. File Download Successfully.';
        } else {
            $response['message'] = 'Wrong Password.';
        }
    } else {
        $response['message'] = 'Documents Not Found.';
    }
    $stmt->close();
}

echo json_encode($response);
?>
