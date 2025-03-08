<?php
include 'db_connect.php';

// Get the selected classification from the request
$classification = isset($_GET['classification']) ? $_GET['classification'] : '';

// Prepare the SQL query
if ($classification) {
    $sql = "SELECT classification, COUNT(*) as count FROM complaints WHERE classification = '$classification' GROUP BY classification";
} else {
    $sql = "SELECT classification, COUNT(*) as count FROM complaints GROUP BY classification";
}

$result = $conn->query($sql);

// Prepare data for the pie chart
$classificationData = [];
while ($row = $result->fetch_assoc()) {
    $classificationData[] = $row;
}

// Create data arrays for the pie chart
$labels = [];
$data = [];
foreach ($classificationData as $classification) {
    $labels[] = $classification['classification'];
    $data[] = $classification['count'];
}

// Return the data as JSON
echo json_encode([
    'labels' => $labels,
    'counts' => $data
]);
?>
