<?php
require 'config/db.php';

// 1. Sanitize client data
$client_name    = $_POST['client_name'];
$client_gst     = $_POST['client_gst'];
$client_contact = $_POST['client_contact'];
$client_email   = $_POST['client_email'];
$client_address = $_POST['client_address'];

$invoice_id     = $_POST['invoice_id'];

// Update client data
$client_stmt = $conn->prepare("UPDATE clients SET name = ?, gstin = ?, contact = ?, email = ?, address = ? WHERE id = (SELECT client_id FROM invoices WHERE id = ?)");
$client_stmt->bind_param("sssssi", $client_name, $client_gst, $client_contact, $client_email, $client_address, $invoice_id);
$client_stmt->execute();

// 2. Invoice fields
$invoice_no          = $_POST['invoice_no'];
$place_of_supply     = $_POST['place_of_supply'];
$invoice_date        = $_POST['invoice_date'];
$due_date            = $_POST['due_date'];
$payment_status      = $_POST['payment_status'];
$payment_method      = $_POST['payment_method'];
$reverse_charge      = $_POST['reverse_charge'];
$discount            = $_POST['discount'];
$total_invoice_value = $_POST['total_invoice_value'];

// Update invoice data
$invoice_stmt = $conn->prepare("UPDATE invoices SET invoice_no = ?, place_of_supply = ?, invoice_date = ?, due_date = ?, payment_status = ?, payment_method = ?, reverse_charge = ?, discount = ?, total_invoice_value = ? WHERE id = ?");
$invoice_stmt->bind_param("ssssssssdi", $invoice_no, $place_of_supply, $invoice_date, $due_date, $payment_status, $payment_method, $reverse_charge, $discount, $total_invoice_value, $invoice_id);
$invoice_stmt->execute();

// 3. Delete existing invoice items
$conn->query("DELETE FROM invoice_items WHERE invoice_id = $invoice_id");

// 4. Insert updated invoice items
foreach ($_POST['service_description'] as $i => $desc) {
    $sac   = $_POST['product_sac'][$i];
    $qty   = $_POST['product_qty'][$i];
    $rate  = $_POST['product_rate'][$i];
    $gst   = $_POST['product_gst'][$i];
    $cgst  = $_POST['cgst_amount'][$i];
    $sgst  = $_POST['sgst_amount'][$i];
    $igst  = $_POST['igst_amount'][$i];
    $total = $_POST['product_total'][$i];

    // Insert item
    $item_stmt = $conn->prepare("INSERT INTO invoice_items (invoice_id, description, sac_code, qty, rate, gst_percent, cgst, sgst, igst, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $item_stmt->bind_param("issidddddd", $invoice_id, $desc, $sac, $qty, $rate, $gst, $cgst, $sgst, $igst, $total);
    $item_stmt->execute();

    // Save to product table if not exists
    $checkProduct = $conn->prepare("SELECT id FROM products WHERE name = ? OR sac_code = ?");
    $checkProduct->bind_param("ss", $desc, $sac);
    $checkProduct->execute();
    $productResult = $checkProduct->get_result();

    if ($productResult->num_rows === 0) {
        $insertProduct = $conn->prepare("INSERT INTO products (name, sac_code, gst_percent, rate) VALUES (?, ?, ?, ?)");
        $insertProduct->bind_param("ssdd", $desc, $sac, $gst, $rate);
        $insertProduct->execute();
    }
}

// 5. Redirect back
header("Location: invoice-details.php?invoice_no=" . urlencode(ltrim($invoice_no, '#')));
exit;
