<?php
// update_status.php
include 'db_connect.php'; // Siguraduhing naglalaman ito ng $conn

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["report_id"]) && isset($_POST["status"])) {
        $report_id = $_POST["report_id"];
        $status = $_POST["status"];
        // Baguhin ang allowed statuses para i-match sa bagong mga status:
        $allowed_statuses = array("Pending", "Review Accident", "Resolved", "Rejected");
        if (!in_array($status, $allowed_statuses)) {
            die("Invalid status value.");
        }
        if (updateStatus($conn, $report_id, $status)) {
            header("Location: legal.php?tab=accidents&update=success");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Missing required fields.";
    }
} else {
    echo "Invalid request method.";
}

function updateStatus($conn, $report_id, $status) {
    $stmt = $conn->prepare("UPDATE accidents SET status = ? WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("si", $status, $report_id);

    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}
?>
