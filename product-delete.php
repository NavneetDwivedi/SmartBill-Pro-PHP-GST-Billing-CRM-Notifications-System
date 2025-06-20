<?php
require 'config/db.php';
$id = $_GET['id'];
$conn->query("DELETE FROM products WHERE id = $id");
header("Location: products.php?deleted=1");
exit;
?>
