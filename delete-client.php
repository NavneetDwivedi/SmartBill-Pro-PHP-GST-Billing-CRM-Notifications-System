<?php
require('config/db.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid client ID.");
}

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM clients WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: clients.php?deleted=success");
exit;
?>
.