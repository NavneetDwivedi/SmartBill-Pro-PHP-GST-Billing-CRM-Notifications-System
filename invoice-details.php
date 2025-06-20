<?php
include('header.php'); 

if (!isset($_GET['invoice_no'])) {
    die("Invoice number not provided.");
}

$invoice_no = '#' . ltrim($_GET['invoice_no'] ?? '', '#');

require('config/db.php');

// Fetch invoice and client
$invoice_stmt = $conn->prepare("SELECT i.*, c.name, c.email, c.contact, c.address, c.gstin FROM invoices i JOIN clients c ON i.client_id = c.id WHERE i.invoice_no = ?");
$invoice_stmt->bind_param("s", $invoice_no);
$invoice_stmt->execute();
$invoice_result = $invoice_stmt->get_result();

if ($invoice_result->num_rows === 0) {
    die("Invoice not found.");
}

$invoice = $invoice_result->fetch_assoc();

// Fetch invoice items
$items_stmt = $conn->prepare("SELECT * FROM invoice_items WHERE invoice_id = ?");
$items_stmt->bind_param("i", $invoice['id']);
$items_stmt->execute();
$items_result = $items_stmt->get_result();
$items = $items_result->fetch_all(MYSQLI_ASSOC);
?>
<div class="main-content">
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Invoices Details</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="#">Invoice</a></li>
                            <li class="breadcrumb-item active">Invoices Details</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-xxl-9">
                <div class="card" id="demo">
                    <div class="card-body">
                        <div class="row p-4">
                            <div class="col-lg-9">
                                <h3 class="fw-bold mb-4">Invoice: <?php echo htmlspecialchars($invoice['invoice_no']); ?></h3>
                                <div class="row g-4">
                                    <div class="col-lg-6 col-6">
                                        <p class="text-muted mb-1 text-uppercase fw-medium fs-14">Invoice No</p>
                                        <h5 class="fs-16 mb-0"><?php echo htmlspecialchars($invoice['invoice_no']); ?></h5>
                                    </div>
                                    <div class="col-lg-6 col-6">
                                        <p class="text-muted mb-1 text-uppercase fw-medium fs-14">Date</p>
                                        <h5 class="fs-16 mb-0"><?php echo date('d M, Y', strtotime($invoice['invoice_date'])); ?></h5>
                                    </div>
                                    <div class="col-lg-6 col-6">
                                        <p class="text-muted mb-1 text-uppercase fw-medium fs-14">Payment Status</p>
                                        <span class="badge bg-success-subtle text-success fs-11"><?php echo ucfirst($invoice['payment_status']); ?></span>
                                    </div>
                                    <div class="col-lg-6 col-6">
                                        <p class="text-muted mb-1 text-uppercase fw-medium fs-14">Total Amount</p>
                                        <h5 class="fs-16 mb-0">₹<?php echo number_format($invoice['total_invoice_value'], 2); ?></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mt-sm-0 mt-3">
                                    <div class="mb-4">
                                        <img src="assets/images/dst_02.jpg" height="50">
                                    </div>
                                    <h6 class="text-muted text-uppercase fw-semibold">Address</h6>
                                    <p class="text-muted mb-1">Jagatpura, Jaipur, Rajasthan</p>
                                    <h6><span class="text-muted fw-normal">Contact:</span> 096000 0000</h6>
                                </div>
                            </div>
                        </div>

                        <div class="row p-4 border-top border-top-dashed">
                            <div class="col-lg-6">
                                <h6 class="text-muted text-uppercase fw-semibold mb-3">Billing Address</h6>
                                <p class="fw-medium mb-2"><?php echo htmlspecialchars($invoice['name']); ?></p>
                                <p class="text-muted mb-1"><?php echo htmlspecialchars($invoice['address']); ?></p>
                                <p class="text-muted mb-1">Phone: <?php echo htmlspecialchars($invoice['contact']); ?></p>
                                 <h6><span class="text-muted fw-normal">Email:</span> <?php echo htmlspecialchars($invoice['email']); ?></h6>
                                <p class="text-muted mb-0">GSTIN: <?php echo htmlspecialchars($invoice['gstin']); ?></p>
                            </div>
                           <!-- <div class="col-lg-6 text-end">
                                <h6 class="text-muted text-uppercase fw-semibold mb-3">Due Date</h6>
                                <h5><?php // echo date('d M, Y', strtotime($invoice['due_date'])); ?></h5>
                            </div> -->
                        </div>
 <?php
$show_cgst = $show_sgst = $show_igst = false;
foreach ($items as $item) {
    if ($item['cgst'] > 0) $show_cgst = true;
    if ($item['sgst'] > 0) $show_sgst = true;
    if ($item['igst'] > 0) $show_igst = true;
}
?>

                        <div class="table-responsive mt-4">
                            <table class="table table-borderless text-center align-middle">
                                <thead class="table-active">
                                    <tr>
                                        <th>#</th>
                                        <th>Description</th>
                                        <th>Rate</th>
                                        <th>Qty</th>
                                        <th>GST%</th>
<?php if ($show_cgst): ?><th>CGST</th><?php endif; ?>
<?php if ($show_sgst): ?><th>SGST</th><?php endif; ?>
<?php if ($show_igst): ?><th>IGST</th><?php endif; ?>
<th class="text-end">Total</th>

                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php foreach ($items as $index => $item): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td class="text-start"><?php echo htmlspecialchars($item['description']); ?><br><small class="text-muted">SAC: <?php echo $item['sac_code']; ?></small></td>
                                        <td>₹<?php echo number_format($item['rate'], 2); ?></td>
                                        <td><?php echo $item['qty']; ?></td>
                                        <td><?php echo $item['gst_percent']; ?>%</td>
<?php if ($show_cgst): ?><td>₹<?php echo number_format($item['cgst'], 2); ?></td><?php endif; ?>
<?php if ($show_sgst): ?><td>₹<?php echo number_format($item['sgst'], 2); ?></td><?php endif; ?>
<?php if ($show_igst): ?><td>₹<?php echo number_format($item['igst'], 2); ?></td><?php endif; ?>
<td class="text-end">₹<?php echo number_format($item['total'], 2); ?></td>

                                        <td class="text-end">₹<?php echo number_format($item['total'], 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="row justify-content-end mt-4">
                            <div class="col-lg-4">
                                <table class="table">
                                    <tr><td>Discount:</td><td class="text-end">- ₹<?php echo number_format($invoice['discount'], 2); ?></td></tr>
                                    <tr><td><strong>Total:</strong></td><td class="text-end"><strong>₹<?php echo number_format($invoice['total_invoice_value'], 2); ?></strong></td></tr>
                                </table>
                            </div>
                        </div>

                        <div class="mt-3">
                            <h6 class="text-muted text-uppercase fw-semibold mb-3">Payment Method</h6>
                            <p class="text-muted mb-1"><?php echo htmlspecialchars($invoice['payment_method']); ?></p>
                        </div>

                        <div class="hstack gap-2 justify-content-end d-print-none mt-4">
  <!-- <a href="javascript:window.print()" class="btn btn-info">
        <i class="ri-printer-line align-bottom me-1"></i> Print
    </a> -->
    <a href="generate-invoice.php?invoice_no=<?php echo urlencode(ltrim($invoice_no, '#')); ?>" target="blank" class="btn btn-primary">
        <i class="ri-download-2-line align-bottom me-1"></i> Download PDF
    </a>
</div>


                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>

<?php include('footer.php'); ?>
