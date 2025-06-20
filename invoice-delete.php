<?php
require('config/db.php');

// Check if invoice ID is set and numeric
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid invoice ID.");
}

$invoice_id = (int) $_GET['id'];

// First, delete related invoice items
$item_stmt = $conn->prepare("DELETE FROM invoice_items WHERE invoice_id = ?");
$item_stmt->bind_param("i", $invoice_id);
$item_stmt->execute();
$item_stmt->close();

// Then, delete the invoice itself
$invoice_stmt = $conn->prepare("DELETE FROM invoices WHERE id = ?");
$invoice_stmt->bind_param("i", $invoice_id);
$invoice_stmt->execute();
$invoice_stmt->close();

// Redirect back to invoice listing with success message (optional)
header("Location: invoice.php?deleted=success");
exit;
