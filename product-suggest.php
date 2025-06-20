<?php
require 'config/db.php';

$term = $_GET['term'] ?? '';

$stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ?");
$likeTerm = '%' . $term . '%';
$stmt->bind_param("s", $likeTerm);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'label' => $row['name'],
        'value' => $row['name'],
        'sac_code' => $row['sac_code'],
         'product_gst' => (int) $row['gst_percent'],
        'rate' => $row['rate']
    ];
}

echo json_encode($data);
?>
