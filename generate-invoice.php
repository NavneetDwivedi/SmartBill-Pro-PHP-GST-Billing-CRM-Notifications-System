<?php
require 'dompdf/autoload.inc.php';
require 'config/db.php';

use Dompdf\Dompdf;

$invoice_no = '#' . ltrim($_GET['invoice_no'] ?? '', '#');
if (!$invoice_no) die("Invoice number is required.");

$stmt = $conn->prepare("SELECT i.*, c.name AS client_name, c.address AS client_address, c.contact AS client_phone, c.email AS client_email, c.gstin AS client_gst FROM invoices i JOIN clients c ON i.client_id = c.id WHERE i.invoice_no = ?");
$stmt->bind_param("s", $invoice_no);
$stmt->execute();
$invoice_result = $stmt->get_result();
if ($invoice_result->num_rows == 0) die("Invoice not found.");
$invoice = $invoice_result->fetch_assoc();
$stmt->close();

$stmt = $conn->prepare("SELECT * FROM invoice_items WHERE invoice_id = ?");
$stmt->bind_param("i", $invoice['id']);
$stmt->execute();
$items_result = $stmt->get_result();
$items = $items_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$show_cgst = $show_sgst = $show_igst = false;
foreach ($items as $item) {
    if ($item['cgst'] > 0) $show_cgst = true;
    if ($item['sgst'] > 0) $show_sgst = true;
    if ($item['igst'] > 0) $show_igst = true;
}



$html = '<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 0; }
    .invoice-box { min-height: 100vh; padding: 30px; background: #fff; border: 1px solid #eee; border-radius: 8px; box-sizing: border-box; }
    .company-details { text-align: right; }
    .company-details img { height: 40px; margin-bottom: 5px; }
    .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .table th, .table td { border: 1px solid #ccc; padding: 8px; }
    .table th { background: #f2f2f2; }
    .summary { width: 100%; margin-top: 20px; }
    .summary td { padding: 5px; text-align: right; }
    .section { margin-top: 30px; }
</style>

<div class="invoice-box">
    <table width="100%">
        <tr>
            <td>
                <h1>GST TAX INVOICE</h1>
                <p><strong>Invoice No:</strong> ' . $invoice['invoice_no'] . '<br>
                <strong>Date:</strong> ' . date('d M Y', strtotime($invoice['invoice_date'])) . '<br>
              
                <strong>Payment Status:</strong> ' . ucfirst($invoice['payment_status']) . '</p>
            </td>
            <td class="company-details">
               <!-- <img src="https://www.dreamssofttechnology.com/img/logo.jpg" alt="Logo"><br> -->
                <strong>Company Pvt Ltd</strong><br>
                <strong>Address: </strong>Jagatpura, Jaipur, Rajasthan <br>
                GSTIN: 22BBBBB1111B1Z5<br>
                info@xyz.com
            </td>
        </tr>
    </table>

    <hr>

    <h5>Client Details</h5>
    <p><strong>' . $invoice['client_name'] . '</strong><br>
    ' . nl2br($invoice['client_address']) . '<br>
    Phone: ' . $invoice['client_phone'] . '<br>
    Email: ' . $invoice['client_email'] . '<br>
    GSTIN: ' . $invoice['client_gst'] . '</p>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>GST%</th>';
if ($show_cgst) $html .= '<th>CGST</th>';
if ($show_sgst) $html .= '<th>SGST</th>';
if ($show_igst) $html .= '<th>IGST</th>';
$html .= '<th>Total</th>
            </tr>
        </thead>
        <tbody>';

$total = 0;
foreach ($items as $index => $item) {
    $html .= '<tr>
        <td>' . ($index + 1) . '</td>
        <td>' . htmlspecialchars($item['description']) . '</td>
        <td>' . $item['qty'] . '</td>
        <td>₹' . number_format($item['rate'], 2) . '</td>
        <td>' . $item['gst_percent'] . '%</td>';
    if ($show_cgst) $html .= '<td>₹' . number_format($item['cgst'], 2) . '</td>';
    if ($show_sgst) $html .= '<td>₹' . number_format($item['sgst'], 2) . '</td>';
    if ($show_igst) $html .= '<td>₹' . number_format($item['igst'], 2) . '</td>';
    $html .= '<td>₹' . number_format($item['total'], 2) . '</td>
    </tr>';
    $total += $item['total'];
}



$html .= '</tbody></table>

    <table class="summary">
        <tr><td><strong>Subtotal:</strong></td><td>₹' . number_format($total, 2) . '</td></tr>
        <tr><td><strong>Discount:</strong></td><td>₹' . number_format($invoice['discount'], 2) . '</td></tr>
        <tr><td><strong>Total Invoice Value:</strong></td><td><strong>₹' . number_format($invoice['total_invoice_value'], 2) . '</strong></td></tr>
    </table>

    <div class="section">
        <p><strong>Declaration:</strong><br>
        We declare that this invoice shows the actual price of the services described and that all particulars are true and correct.</p>
        <p>This is a computer-generated invoice and does not require a signature.</p>
    </div>
</div>';

$dompdf = new Dompdf();
$dompdf->set_option('isRemoteEnabled', true);
$dompdf->set_option('defaultFont', 'DejaVu Sans');
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$formattedDate = date('d_M_Y', strtotime($invoice['invoice_date']));
$clientNameSlug = preg_replace('/[^a-zA-Z0-9]/', '_', $invoice['client_name']);
$pdfFileName = "Invoice_{$formattedDate}_{$clientNameSlug}.pdf";

$dompdf->stream($pdfFileName, array("Attachment" => false));

exit;
