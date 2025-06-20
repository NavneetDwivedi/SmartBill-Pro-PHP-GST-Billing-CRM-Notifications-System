<?php
require 'dompdf/autoload.inc.php';
require 'config/db.php';

use Dompdf\Dompdf;

// Get invoice number from URL
$invoice_no = '#' . ltrim($_GET['invoice_no'] ?? '', '#');

if (!$invoice_no) {
    die("Invoice number is required.");
}

// Fetch invoice with client details
$stmt = $conn->prepare("
    SELECT i.*, 
           c.name AS client_name, 
           c.address AS client_address, 
           c.contact AS client_phone, 
           c.email AS client_email, 
           c.gstin AS client_gst 
    FROM invoices i 
    JOIN clients c ON i.client_id = c.id 
    WHERE i.invoice_no = ?
");
$stmt->bind_param("s", $invoice_no);
$stmt->execute();
$invoice_result = $stmt->get_result();

if ($invoice_result->num_rows == 0) {
    die("Invoice not found.");
}
$invoice = $invoice_result->fetch_assoc();
$stmt->close();

// Fetch invoice items
$stmt = $conn->prepare("SELECT * FROM invoice_items WHERE invoice_id = ?");
$stmt->bind_param("i", $invoice['id']);
$stmt->execute();
$items_result = $stmt->get_result();
$items = $items_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Image path fix
$image_path = $_SERVER['DOCUMENT_ROOT'] . '/gst/assets/images/dst_02.jpg';
$image_uri = 'file://' . realpath($image_path);


// Generate HTML
$html = '
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
    .invoice-box { padding: 20px; border: 1px solid #ccc; background: #f9f9f9; }
    .header { display: flex; justify-content: space-between; }
    .header h1 { font-size: 18px; margin: 0; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ddd; padding: 8px; }
    th { background: #eee; }
    .right { text-align: right; }
    .bold { font-weight: bold; }
</style>

<div class="invoice-box">
    <table width="100%" style="margin-bottom: 10px; border: none;">
  <tr>
    <td style="vertical-align: top; border: none;">
      <h1 style="margin: 0;">GST TAX INVOICE</h1>
      <p style="margin: 5px 0;"><strong>Invoice No:</strong> ' . htmlspecialchars($invoice['invoice_no']) . '<br>
      <strong>Date:</strong> ' . htmlspecialchars($invoice['invoice_date']) . '</p>
    </td>
    <td style="text-align: right; vertical-align: top; border: none;">
      <img src="https://www.dreamssofttechnology.com/img/logo.jpg" height="40" alt="Company Logo"><br>
      <strong>Invoika Technologies</strong><br>
      GSTIN: 22BBBBB1111B1Z5<br>
      info@invoika.com
    </td>
  </tr>
</table>


    <hr>

    <p><strong>Invoice To:</strong><br>
    ' . htmlspecialchars($invoice['client_name']) . '<br>
    ' . nl2br(htmlspecialchars($invoice['client_address'])) . '<br>
    Phone: ' . htmlspecialchars($invoice['client_phone']) . '<br>
    Email: ' . htmlspecialchars($invoice['client_email']) . '<br>
    GSTIN: ' . htmlspecialchars($invoice['client_gst']) . '</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>CGST</th>
                <th>SGST</th>
                <th>IGST</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>';

$total = 0;
foreach ($items as $index => $item) {
    $html .= '<tr>
        <td>' . ($index + 1) . '</td>
        <td>' . htmlspecialchars($item['description']) . '</td>
        <td class="right">' . $item['qty'] . '</td>
        <td class="right">₹' . number_format($item['rate'], 2) . '</td>
        <td class="right">₹' . number_format($item['cgst'], 2) . '</td>
        <td class="right">₹' . number_format($item['sgst'], 2) . '</td>
        <td class="right">₹' . number_format($item['igst'], 2) . '</td>
        <td class="right">₹' . number_format($item['total'], 2) . '</td>
    </tr>';
    $total += $item['total'];
}

$html .= '
        </tbody>
    </table>

    <table style="margin-top: 10px;">
        <tr>
            <td style="border: none;" class="right bold">Subtotal:</td>
            <td style="border: none;" class="right">₹' . number_format($total, 2) . '</td>
        </tr>
        <tr>
            <td style="border: none;" class="right bold">Discount:</td>
            <td style="border: none;" class="right">₹' . number_format($invoice['discount'], 2) . '</td>
        </tr>
        <tr>
            <td style="border: none;" class="right bold">Total Invoice Value:</td>
            <td style="border: none;" class="right bold">₹' . number_format($invoice['total_invoice_value'], 2) . '</td>
        </tr>
    </table>

    <p style="margin-top: 30px;"><strong>Declaration:</strong> We declare that this invoice shows the actual price of the services described and that all particulars are true and correct.</p>
    <p style="margin-top: 10px;">This is a computer-generated invoice and does not require a signature.</p>
</div>';

// Generate PDF
$dompdf = new Dompdf();
$dompdf->set_option('isRemoteEnabled', true);
$dompdf->set_option('defaultFont', 'DejaVu Sans');
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Invoice_{$invoice_no}.pdf", array("Attachment" => false));
exit;
?>
