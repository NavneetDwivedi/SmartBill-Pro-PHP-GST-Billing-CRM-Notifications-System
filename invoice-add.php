<?php include('header.php'); 
// Get latest invoice ID
$result = $conn->query("SELECT id FROM invoices ORDER BY id DESC LIMIT 1");
$row = $result->fetch_assoc();
$nextId = isset($row['id']) ? $row['id'] + 1 : 1;

$invoice_no = '#DST' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

?>

<style>
    .mt-2 {
        margin-top: 10px;
    }

</style>

        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">New Invoice</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Invoice</a></li>
                                        <li class="breadcrumb-item active">New Invoice</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    

                    <div class="row justify-content-center">
                        <div class="col-xxl-9">
                            <div class="card">
                              <form method="post" action="invoice-save.php" id="invoice_form">
    <div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <label>Invoice No</label>
      <input type="text" name="invoice_no" class="form-control" value="<?php echo $invoice_no; ?>" readonly>
    </div>
    <div class="col-md-6">
      <label>Place of Supply (State)</label>
      <input type="text" name="place_of_supply" class="form-control" placeholder="Rajasthan" required>
    </div>
    <div class="col-md-6  mt-2">
    <label>Invoice Issue Date</label>
    <input type="date" name="invoice_date" class="form-control" required>
  </div>
  <div class="col-md-6 mt-2">
    <label>Invoice Due Date</label>
    <input type="date" name="due_date" class="form-control">
  </div>
  
    <div class="col-md-6 mt-2">
      <label>Payment Status</label>
      <select name="payment_status" class="form-control" required>
        <option value="">Select</option>
        <option value="Paid">Paid</option>
        <option value="Unpaid">Unpaid</option>
      </select>
    </div>
    <div class="col-md-6 mt-2">
      <label>Payment Method</label>
      <select name="payment_method" class="form-control">
        <option value="">Select</option>
        <option value="UPI">UPI</option>
        <option value="Bank Transfer">Bank Transfer</option>
        <option value="Cash">Cash</option>
      </select>
    </div>
    
  </div>

  <hr>

  <!-- Client Details -->
<div class="row">
  <div class="col-md-6">
    <label>Client Name</label>
    <input type="text" name="client_name" class="form-control" required>
  </div>
  <div class="col-md-6">
    <label>Client GSTIN</label>
    <input type="text" name="client_gst" class="form-control">
  </div>
  <div class="col-md-6 mt-2">
    <label>Client Contact No.</label>
    <input type="text" name="client_contact" class="form-control" placeholder="e.g. 9876543210">
  </div>
  <div class="col-md-6 mt-2">
    <label>Client Email ID</label>
    <input type="email" name="client_email" class="form-control" placeholder="e.g. client@example.com">
  </div>
  <div class="col-md-12 mt-2">
    <label>Client Address</label>
    <textarea name="client_address" class="form-control" rows="2" required></textarea>
  </div>
</div>



  <hr>

  <!-- Service Table -->
  <table class="table table-bordered" id="service_table">
    <thead>
      <tr>
        <th>#</th>
        <th>Product Name</th>
        <th>SAC Code</th>
        <th>Qty</th>
        <th>Price</th>
        <th>GST%</th>
        <th>CGST</th>
        <th>SGST</th>
        <th>IGST</th>
        <th>Total</th>
        <th>Remove</th>
      </tr>
    </thead>
    <tbody id="serviceBody">
      <tr>
        <td>1</td>
        <td><input name="service_description[]" class="form-control product-input" required></td>
        <td><input name="product_sac[]" class="form-control" required></td>
        <td><input type="number" name="product_qty[]" class="form-control qty" value="1" min="1" required></td>
        <td><input type="number" name="product_rate[]" class="form-control rate" step="0.01" required></td>
        <td>
          <select name="product_gst[]" class="form-control gst">
            <option value="0">0%</option>
            <option value="5">5%</option>
            <option value="12">12%</option>
            <option value="18" selected>18%</option>
            <option value="28">28%</option>
          </select>
        </td>
        <td><input type="text" class="form-control cgst" name="cgst_amount[]" readonly></td>
        <td><input type="text" class="form-control sgst" name="sgst_amount[]" readonly></td>
        <td><input type="text" class="form-control igst" name="igst_amount[]" readonly></td>
        <td><input type="text" class="form-control total" name="product_total[]" readonly></td>
        <td><button type="button" class="btn btn-danger remove-row">X</button></td>
      </tr>
    </tbody>
  </table>
  <button type="button" id="addRow" class="btn btn-secondary mb-3">Add Row</button>

  <div class="row">
    <div class="col-md-4">
      <label>Reverse Charge</label>
      <select name="reverse_charge" class="form-control">
        <option value="No">No</option>
        <option value="Yes">Yes</option>
      </select>
    </div>
    <div class="col-md-4">
      <label>Discount</label>
      <input type="number" name="discount" class="form-control" value="0">
    </div>
    <div class="col-md-4">
      <label>Total Invoice Value (₹)</label>
      <input type="text" name="total_invoice_value" class="form-control" readonly>
    </div>
  </div>

  <hr>

  <div class="text-end">
    <button type="submit" class="btn btn-primary">Save Invoice</button>
  </div>
  </div>
</form>



</div>
</div>
</div>
    <?php include('footer.php'); ?>

  <script>
document.addEventListener("DOMContentLoaded", function () {
  function updateTotals() {
    const placeOfSupply = document.querySelector('[name="place_of_supply"]').value.toLowerCase();
    const isIntraState = placeOfSupply.includes("rajasthan"); // Adjust as per your own state

    let grandTotal = 0;

    document.querySelectorAll('#serviceBody tr').forEach(row => {
      const qty = parseFloat(row.querySelector('.qty').value) || 0;
      const rate = parseFloat(row.querySelector('.rate').value) || 0;
      const gstRate = parseFloat(row.querySelector('.gst').value) || 0;
      const baseAmount = qty * rate;

      let cgst = 0, sgst = 0, igst = 0;

      if (isIntraState) {
        cgst = (baseAmount * gstRate) / 200;
        sgst = (baseAmount * gstRate) / 200;
      } else {
        igst = (baseAmount * gstRate) / 100;
      }

      const total = baseAmount + cgst + sgst + igst;

      row.querySelector('.cgst').value = cgst.toFixed(2);
      row.querySelector('.sgst').value = sgst.toFixed(2);
      row.querySelector('.igst').value = igst.toFixed(2);
      row.querySelector('.total').value = total.toFixed(2);

      grandTotal += total;
    });

    const discount = parseFloat(document.querySelector('[name="discount"]').value) || 0;
    const finalAmount = grandTotal - discount;

    document.querySelector('[name="total_invoice_value"]').value = finalAmount.toFixed(2);
  }

  // Events
  document.getElementById('addRow').addEventListener('click', () => {
   
    const tbody = document.getElementById('serviceBody');
const newRow = tbody.rows[0].cloneNode(true);

// Clear values
newRow.querySelectorAll('input').forEach(input => input.value = '');
newRow.querySelector('.qty').value = 1;

// Update serial number
const rowCount = tbody.querySelectorAll('tr').length + 1;
newRow.querySelector('td').textContent = rowCount;

tbody.appendChild(newRow);
updateTotals();
  });

  // Recalculate when any quantity, rate, GST, or discount changes
document.addEventListener('input', e => {
  if (e.target.closest('#service_table')) {
    updateTotals();
  }
});

document.addEventListener('change', e => {
  if (e.target.closest('#service_table') || e.target.name === 'discount') {
    updateTotals();
  }
});


  document.addEventListener('click', e => {
    if (e.target.classList.contains('remove-row')) {
      if (document.querySelectorAll('#serviceBody tr').length > 1) {
        e.target.closest('tr').remove();
        updateTotals();
      }
    }
  });

  document.querySelector('[name="place_of_supply"]').addEventListener('input', updateTotals);
});

$(function() {
  $('input[name="client_name"]').autocomplete({
    source: 'client-suggest.php',
    minLength: 2,
    select: function(event, ui) {
      $('input[name="client_name"]').val(ui.item.value);
      $('input[name="client_gst"]').val(ui.item.gstin);
      $('input[name="client_contact"]').val(ui.item.contact);
      $('input[name="client_email"]').val(ui.item.email);
      $('input[name="place_of_supply"]').val(ui.item.state);
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

                // Set GST% and trigger change to ensure any listener reacts
                row.find('select[name="product_gst[]"]').val(ui.item.product_gst).trigger('change');


                // Trigger recalculation
                row.find('input[name="product_qty[]"]').trigger('input');
            }
        }).data("autocomplete", true);
    }
});
});

</script>

