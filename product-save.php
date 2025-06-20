<?php
require 'config/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("INSERT INTO products (name, sac_code, gst_percent, rate) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssdd", $_POST['name'], $_POST['sac_code'], $_POST['gst_percent'], $_POST['price']);
    $stmt->execute();
    $stmt->close();
    header("Location: products.php?added=1");
    exit;
}
?>
