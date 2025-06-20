<?php
require 'config/db.php';
header('Content-Type: application/json');

$term = $_GET['term'] ?? '';

$stmt = $conn->prepare("SELECT * FROM clients WHERE name LIKE ?");
$likeTerm = '%' . $term . '%';
$stmt->bind_param("s", $likeTerm);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'label' => $row['name'] . ' | ' . $row['gstin'] . ' | ' . $row['email'],
        'value' => $row['name'],
        'gstin' => $row['gstin'],
        'contact' => $row['contact'],
        'email' => $row['email'],
        'address' => $row['address'],
        'state' => $row['state']
    ];
}

echo json_encode($data);
