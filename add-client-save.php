<?php
require('config/db.php');

// Collect and sanitize form data
$company_name        = trim($_POST['company_name']);
$name                = trim($_POST['client_name']);
$gstin               = trim($_POST['client_gstin']);
$pan                 = trim($_POST['client_pan']);
$incorporation_date  = trim($_POST['incorporation_date']);
$contact             = trim($_POST['client_contact']);
$email               = trim($_POST['client_email']);
$address             = trim($_POST['client_address']);
$city                = trim($_POST['client_city']);
$state               = trim($_POST['client_state']);
$website             = trim($_POST['client_website']);

// Validate required fields
if (!empty($name) && !empty($address)) {
    $stmt = $conn->prepare("INSERT INTO clients (company_name, name, gstin, pan, incorporation_date, contact, email, address, city, state, website) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $company_name, $name, $gstin, $pan, $incorporation_date, $contact, $email, $address, $city, $state, $website);

    if ($stmt->execute()) {
        echo "<script>
            alert('Client added successfully!');
            setTimeout(function() {
                window.location.href = 'clients.php';
            }, 500);
        </script>";
    } else {
        echo "<script>alert('Error: " . addslashes($stmt->error) . "'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Client Name and Address are required.'); window.history.back();</script>";
}
?>
