<?php
require('config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id                 = $_POST['id'];
    $company_name       = $_POST['company_name'];
    $name               = $_POST['client_name'];
    $pan                = $_POST['client_pan'];
    $gstin              = $_POST['client_gstin'];
    $incorp_date        = $_POST['incorporation_date'];
    $contact            = $_POST['client_contact'];
    $email              = $_POST['client_email'];
    $website            = $_POST['client_website'];
    $city               = $_POST['client_city'];
    $state              = $_POST['client_state'];
    $address            = $_POST['client_address'];

    $stmt = $conn->prepare("UPDATE clients SET company_name=?, name=?, pan=?, gstin=?, incorporation_date=?, contact=?, email=?, website=?, city=?, state=?, address=? WHERE id=?");
    $stmt->bind_param("sssssssssssi", $company_name, $name, $pan, $gstin, $incorp_date, $contact, $email, $website, $city, $state, $address, $id);
    $stmt->execute();

    header("Location: clients.php?updated=success");
    exit;
}
?>
