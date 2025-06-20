<?php
 require 'config/db.php';
//require 'PHPMailer/PHPMailer.php';
// require 'PHPMailer/SMTP.php';
// require 'PHPMailer/Exception.php';

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

$today = date('Y-m-d');
$targetDate = date('Y-m-d', strtotime('+7 days'));

// Calculate target date: 7 days from today
$reminder_date = date('Y-m-d', strtotime('+7 days'));

// Get upcoming invoices
$stmt = $conn->prepare("SELECT invoices.id, invoices.invoice_no, invoices.due_date, clients.name AS client_name 
                        FROM invoices 
                        JOIN clients ON invoices.client_id = clients.id 
                        WHERE DATE(invoices.due_date) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)");

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $body = "<h3>Invoice Reminders (Due in 7 days)</h3><ul>";

    while ($row = $result->fetch_assoc()) {
        $invoice_no = $row['invoice_no'];
        $client_name = $row['client_name'];
        $due_date = $row['due_date'];
        $invoice_id = $row['id'];

        $body .= "<li><strong>$invoice_no</strong> for <strong>$client_name</strong> is due on <strong>$due_date</strong></li>";

        // Save notification in database (prevent duplicate)
        $check = $conn->prepare("SELECT id FROM notifications WHERE invoice_id = ?");
        $check->bind_param("i", $invoice_id);
        $check->execute();
        $check_result = $check->get_result();

        if ($check_result->num_rows == 0) {
            $msg = "$invoice_no for $client_name is due on $due_date";
            $insert = $conn->prepare("INSERT INTO notifications (invoice_id, message, created_at) VALUES (?, ?, NOW())");
            $insert->bind_param("is", $invoice_id, $msg);
            $insert->execute();
        }
    }

   
}
?>
