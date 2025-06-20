<?php
require 'config/db.php';
$stmt = $conn->prepare("UPDATE products SET name=?, sac_code=?, gst_percent=?, rate=? WHERE id=?");
$stmt->bind_param("sssdi", $_POST['name'], $_POST['sac_code'], $_POST['gst_percent'], $_POST['price'], $_POST['id']);
$stmt->execute();
$stmt->close();
header("Location: products.php?updated=1");
exit;
?>