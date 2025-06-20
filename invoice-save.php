<?php
require('config/db.php');

// 1. Sanitize client data
$client_name    = $_POST['client_name'];
$client_gst     = $_POST['client_gst'];
$client_contact = $_POST['client_contact'];
$client_email   = $_POST['client_email'];
$client_address = $_POST['client_address'];

// 2. Check if client exists
$checkClient = $conn->prepare("SELECT id FROM clients WHERE gstin = ? OR email = ?");
$checkClient->bind_param("ss", $client_gst, $client_email);
$checkClient->execute();
$result = $checkClient->get_result();

if ($result->num_rows > 0) {
    $client_id = $result->fetch_assoc()['id'];
} else {
    // Insert new client
    $insertClient = $conn->prepare("INSERT INTO clients (name, gstin, contact, email, address) VALUES (?, ?, ?, ?, ?)");
    $insertClient->bind_param("sssss", $client_name, $client_gst, $client_contact, $client_email, $client_address);
    $insertClient->execute();
    $client_id = $insertClient->insert_id;
}

// 3. Get invoice data
$invoice_no         = $_POST['invoice_no'];
$place_of_supply    = $_POST['place_of_supply'];
$invoice_date       = $_POST['invoice_date'];
$due_date           = $_POST['due_date'];
$payment_status     = $_POST['payment_status'];
$payment_method     = $_POST['payment_method'];
$reverse_charge     = $_POST['reverse_charge'];
$discount           = $_POST['discount'];
$total_invoice_value = $_POST['total_invoice_value'];

// 4. Insert invoice

$insertInvoice = $conn->prepare("INSERT INTO invoices (invoice_no, client_id, place_of_supply, invoice_date, due_date, payment_status, payment_method, reverse_charge, discount, total_invoice_value) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if (!$insertInvoice) {
    die("Prepare failed for invoice insert: " . $conn->error);
}
$insertInvoice->bind_param("sissssssdd", $invoice_no, $client_id, $place_of_supply, $invoice_date, $due_date, $payment_status, $payment_method, $reverse_charge, $discount, $total_invoice_value);

if (!$insertInvoice->execute()) {
    die("Invoice insert error: " . $insertInvoice->error);
}

$invoice_id = $insertInvoice->insert_id;

foreach ($_POST['service_description'] as $i => $desc) {
    $sac   = $_POST['product_sac'][$i];
    $qty   = $_POST['product_qty'][$i];
    $rate  = $_POST['product_rate'][$i];
    $gst   = $_POST['product_gst'][$i];
    $cgst  = $_POST['cgst_amount'][$i];
    $sgst  = $_POST['sgst_amount'][$i];
    $igst  = $_POST['igst_amount'][$i];
    $total = $_POST['product_total'][$i];

    // Insert invoice item
    $insertItem = $conn->prepare("INSERT INTO invoice_items (invoice_id, description, sac_code, qty, rate, gst_percent, cgst, sgst, igst, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insertItem->bind_param("issidddddd", $invoice_id, $desc, $sac, $qty, $rate, $gst, $cgst, $sgst, $igst, $total);
    if (!$insertItem->execute()) {
        die("Item insert error: " . $insertItem->error);
    }

    // Check if product already exists (by name or SAC)
    $checkProduct = $conn->prepare("SELECT id FROM products WHERE name = ? OR sac_code = ?");
    $checkProduct->bind_param("ss", $desc, $sac);
    $checkProduct->execute();
    $productResult = $checkProduct->get_result();

    if ($productResult->num_rows === 0) {
        // Insert new product
        $insertProduct = $conn->prepare("INSERT INTO products (name, sac_code, gst_percent, rate) VALUES (?, ?, ?, ?)");
        $insertProduct->bind_param("ssdd", $desc, $sac, $gst, $rate);
        if (!$insertProduct->execute()) {
            die("Product insert error: " . $insertProduct->error);
        }
    }
}


// 6. Redirect to PDF invoice preview
header("Location: invoice-details.php?invoice_no=" . urlencode($invoice_no));

exit;

exit;
?>
