<?php
include('header.php');

if (!isset($_GET['id'])) {
    die("Invoice ID not provided.");
}

$invoice_id = (int)$_GET['id'];
require('config/db.php');

// Fetch invoice
$invoice_stmt = $conn->prepare("SELECT i.*, c.name, c.gstin, c.contact, c.email, c.address 
                                FROM invoices i 
                                JOIN clients c ON i.client_id = c.id 
                                WHERE i.id = ?");
$invoice_stmt->bind_param("i", $invoice_id);
$invoice_stmt->execute();
$invoice_result = $invoice_stmt->get_result();

if ($invoice_result->num_rows === 0) {
    die("Invoice not found.");
}
$invoice = $invoice_result->fetch_assoc();

// Fetch invoice items
$items_stmt = $conn->prepare("SELECT * FROM invoice_items WHERE invoice_id = ?");
$items_stmt->bind_param("i", $invoice_id);
$items_stmt->execute();
$items_result = $items_stmt->get_result();
$invoice_items = $items_result->fetch_all(MYSQLI_ASSOC);
?>

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-xxl-9">
          <div class="card">
            <form method="post" action="invoice-update.php" id="invoice_form">
              <input type="hidden" name="invoice_id" value="<?= $invoice_id ?>">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <label>Invoice No</label>
                    <input type="text" name="invoice_no" class="form-control" value="<?= $invoice['invoice_no'] ?>" readonly>
                  </div>
                  <div class="col-md-6">
                    <label>Place of Supply</label>
                    <input type="text" name="place_of_supply" class="form-control" value="<?= $invoice['place_of_supply'] ?>">
                  </div>
                  <div class="col-md-6 mt-2">
                    <label>Invoice Date</label>
                    <input type="date" name="invoice_date" class="form-control" value="<?= $invoice['invoice_date'] ?>">
                  </div>
                  <div class="col-md-6 mt-2">
                    <label>Due Date</label>
                    <input type="date" name="due_date" class="form-control" value="<?= $invoice['due_date'] ?>">
                  </div>
                  <div class="col-md-6 mt-2">
                    <label>Payment Status</label>
                    <select name="payment_status" class="form-control">
                      <option value="Paid" <?= $invoice['payment_status'] == 'Paid' ? 'selected' : '' ?>>Paid</option>
                      <option value="Unpaid" <?= $invoice['payment_status'] == 'Unpaid' ? 'selected' : '' ?>>Unpaid</option>
                    </select>
                  </div>
                  <div class="col-md-6 mt-2">
                    <label>Payment Method</label>
                    <select name="payment_method" class="form-control">
                      <option value="UPI" <?= $invoice['payment_method'] == 'UPI' ? 'selected' : '' ?>>UPI</option>
                      <option value="Bank Transfer" <?= $invoice['payment_method'] == 'Bank Transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                      <option value="Cash" <?= $invoice['payment_method'] == 'Cash' ? 'selected' : '' ?>>Cash</option>
                    </select>
                  </div>
                </div>

                <hr>
                <div class="row">
                  <div class="col-md-6">
                    <label>Client Name</label>
                    <input type="text" name="client_name" class="form-control" value="<?= $invoice['name'] ?>">
                  </div>
                  <div class="col-md-6">
                    <label>Client GSTIN</label>
                    <input type="text" name="client_gst" class="form-control" value="<?= $invoice['gstin'] ?>">
                  </div>
                  <div class="col-md-6 mt-2">
                    <label>Client Contact</label>
                    <input type="text" name="client_contact" class="form-control" value="<?= $invoice['contact'] ?>">
                  </div>
                  <div class="col-md-6 mt-2">
                    <label>Client Email</label>
                    <input type="email" name="client_email" class="form-control" value="<?= $invoice['email'] ?>">
                  </div>
                  <div class="col-md-12 mt-2">
                    <label>Client Address</label>
                    <textarea name="client_address" class="form-control" rows="2"><?= $invoice['address'] ?></textarea>
                  </div>
                </div>

                <hr>
                <table class="table table-bordered" id="service_table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Product Name</th>
                      <th>SAC Code</th>
                      <th>Qty</th>
                      <th>Rate</th>
                      <th>GST%</th>
                      <th>CGST</th>
                      <th>SGST</th>
                      <th>IGST</th>
                      <th>Total</th>
                      <th>Remove</th>
                    </tr>
                  </thead>
                  <tbody id="serviceBody">
                    <?php foreach ($invoice_items as $index => $item): ?>
                    <tr>
                      <td><?= $index + 1 ?></td>
                      <td><input name="service_description[]" class="form-control product-input" value="<?= htmlspecialchars($item['description']) ?>"></td>
                      <td><input name="product_sac[]" class="form-control" value="<?= $item['sac_code'] ?>"></td>
                      <td><input name="product_qty[]" type="number" class="form-control qty" value="<?= $item['qty'] ?>"></td>
                      <td><input name="product_rate[]" type="number" class="form-control rate" value="<?= $item['rate'] ?>"></td>
                      <td>
                        <select name="product_gst[]" class="form-control gst">
                          <?php foreach ([0,5,12,18,28] as $gst): ?>
                          <option value="<?= $gst ?>" <?= $item['gst_percent'] == $gst ? 'selected' : '' ?>><?= $gst ?>%</option>
                          <?php endforeach; ?>
                        </select>
                      </td>
                      <td><input type="text" name="cgst_amount[]" class="form-control cgst" value="<?= $item['cgst'] ?>"></td>
                      <td><input type="text" name="sgst_amount[]" class="form-control sgst" value="<?= $item['sgst'] ?>"></td>
                      <td><input type="text" name="igst_amount[]" class="form-control igst" value="<?= $item['igst'] ?>"></td>
                      <td><input type="text" name="product_total[]" class="form-control total" value="<?= $item['total'] ?>"></td>
                      <td><button type="button" class="btn btn-danger remove-row">X</button></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>

                <div class="row mt-3">
                  <div class="col-md-4">
                    <label>Reverse Charge</label>
                    <select name="reverse_charge" class="form-control">
                      <option value="No" <?= $invoice['reverse_charge'] == 'No' ? 'selected' : '' ?>>No</option>
                      <option value="Yes" <?= $invoice['reverse_charge'] == 'Yes' ? 'selected' : '' ?>>Yes</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label>Discount</label>
                    <input type="number" name="discount" class="form-control" value="<?= $invoice['discount'] ?>">
                  </div>
                  <div class="col-md-4">
                    <label>Total Invoice Value (₹)</label>
                    <input type="text" name="total_invoice_value" class="form-control" value="<?= $invoice['total_invoice_value'] ?>">
                  </div>
                </div>

                <hr>

                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Update Invoice</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include('footer.php'); ?>

<script>
function updateTotals() {
  const place = $('input[name="place_of_supply"]').val().trim().toLowerCase();
  const isRajasthan = place === 'rajasthan';

  let grandTotal = 0;

  $('#serviceBody tr').each(function () {
    const row = $(this);
    const qty = parseFloat(row.find('.qty').val()) || 0;
    const rate = parseFloat(row.find('.rate').val()) || 0;
    const gst = parseFloat(row.find('.gst').val()) || 0;
    const baseAmount = qty * rate;

    let cgst = 0, sgst = 0, igst = 0;

    if (isRajasthan) {
      // Apply IGST only
      igst = (baseAmount * gst) / 100;
    } else {
      // Apply CGST + SGST
      cgst = (baseAmount * gst) / 200;
      sgst = (baseAmount * gst) / 200;
    }

    const total = baseAmount + cgst + sgst + igst;

    row.find('.cgst').val(cgst.toFixed(2));
    row.find('.sgst').val(sgst.toFixed(2));
    row.find('.igst').val(igst.toFixed(2));
    row.find('.total').val(total.toFixed(2));

    grandTotal += total;
  });

  const discount = parseFloat($('input[name="discount"]').val()) || 0;
  const finalTotal = grandTotal - discount;
  $('input[name="total_invoice_value"]').val(finalTotal.toFixed(2));
}

// Trigger on change/input
$(document).on('input change', '.qty, .rate, .gst, input[name="discount"], input[name="place_of_supply"]', function () {
  updateTotals();
});

// Call once on page load
$(document).ready(function () {
  updateTotals();
});
</script>



<script>
  $(function() {
  $('input[name="client_name"]').autocomplete({
    source: 'client-suggest.php',
    minLength: 2,
    select: function(event, ui) {
      $('input[name="client_name"]').val(ui.item.value);
      $('input[name="client_gst"]').val(ui.item.gstin);
      $('input[name="client_contact"]').val(ui.item.contact);
      $('input[name="client_email"]').val(ui.item.email);
      $('textarea[name="client_address"]').val(ui.item.address);
      return false;
    }
  });
});

$(function() {
  $(document).on('focus', '.product-input', function () {
    if (!$(this).data("autocomplete")) {
        $(this).autocomplete({
            source: 'product-suggest.php',
            minLength: 1,
            select: function (event, ui) {
                const row = $(this).closest('tr');
                row.find('input[name="product_sac[]"]').val(ui.item.sac_code);
                row.find('input[name="product_rate[]"]').val(ui.item.rate);

                // Set GST%
                row.find('select[name="product_gst[]"]').val(ui.item.product_gst).trigger('change');

                // Trigger recalculation
                row.find('input[name="product_qty[]"]').trigger('input');
            }
        }).data("autocomplete", true);
    }
  });
});

</script>