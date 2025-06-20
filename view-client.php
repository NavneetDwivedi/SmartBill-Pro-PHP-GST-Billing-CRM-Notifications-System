<?php
require('config/db.php');
include('header.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid client ID.");
}

$client_id = (int)$_GET['id'];

// Fetch client
$client_stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");
$client_stmt->bind_param("i", $client_id);
$client_stmt->execute();
$client_result = $client_stmt->get_result();
$client = $client_result->fetch_assoc();

if (!$client) {
    die("Client not found.");
}

// Fetch invoices
$invoice_stmt = $conn->prepare("SELECT * FROM invoices WHERE client_id = ? ORDER BY id DESC");
$invoice_stmt->bind_param("i", $client_id);
$invoice_stmt->execute();
$invoice_result = $invoice_stmt->get_result();
?>

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <!-- Page Title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Client Details</h4>
          </div>
        </div>
      </div>

      <!-- Client Info -->
      <div class="row">
        <div class="col-xl-6">
          <div class="card shadow-sm">
  <div class="card-body">
    <div class="d-flex align-items-center">
      <div class="flex-shrink-0">
        <div class="avatar-lg rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fs-3 fw-bold">
          <?= strtoupper(substr($client['name'], 0, 1)) ?>
        </div>
      </div>
      <div class="flex-grow-1 ms-4">
        <h4 class="mb-1"><?= htmlspecialchars($client['name']) ?></h4>
       <p class="text-muted mb-0"><i class="las la-briefcase me-1"></i> <?= htmlspecialchars($client['company_name']) ?></p>

        <p class="text-muted mb-0"><i class="las la-envelope me-1"></i> <?= htmlspecialchars($client['email']) ?></p>
        <p class="text-muted mb-0"><i class="las la-phone me-1"></i> <?= htmlspecialchars($client['contact']) ?></p>
      </div>
    </div>

    <hr>

    <div class="row">
      <div class="col-md-6">
        <p class="mb-1"><strong>PAN:</strong> <?= htmlspecialchars($client['pan']) ?></p>
        <p class="mb-1"><strong>GSTIN:</strong> <?= htmlspecialchars($client['gstin']) ?></p>
        <p class="mb-1"><strong>Incorporation:</strong> <?= htmlspecialchars($client['incorporation_date']) ?></p>
      </div>
      <div class="col-md-6">
        <p class="mb-1"><strong>City:</strong> <?= htmlspecialchars($client['city']) ?></p>
        <p class="mb-1"><strong>State:</strong> <?= htmlspecialchars($client['state']) ?></p>
        <p class="mb-1"><strong>Website:</strong> <?= htmlspecialchars($client['website']) ?></p>
      </div>
    </div>

    <div class="mt-3">
      <p class="mb-0"><strong>Address:</strong></p>
      <p class="text-muted"><?= nl2br(htmlspecialchars($client['address'])) ?></p>
    </div>
  </div>
</div>

        </div>
      </div>

      <!-- Invoice List -->
      <div class="row mt-4">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-body" >
              <h5 class="mb-3">Invoices for <?= htmlspecialchars($client['name']) ?></h5>
              <div class="table-responsive table-card"  style="min-height: 300px;">
                <table class="table table-hover table-nowrap align-middle mb-0">
                  <thead>
                    <tr class="text-muted text-uppercase">
                      <th>#</th>
                      <th>Invoice ID</th>
                      <th>Issue Date</th>
                      <th>Due Date</th>
                      <th>Status</th>
                      <th>Total</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($invoice_result->num_rows > 0) {
                      $i = 1;
                      while ($inv = $invoice_result->fetch_assoc()) {
                        $status = strtolower($inv['payment_status']);
                        $badge = match ($status) {
                          'paid' => 'bg-success-subtle text-success',
                          'unpaid' => 'bg-primary text-white',
                          'cancelled' => 'bg-danger-subtle text-danger',
                          default => 'bg-secondary text-dark'
                        };
                        echo "<tr>
                          <td>{$i}</td>
                          <td><p class='fw-medium mb-0'>" . htmlspecialchars($inv['invoice_no']) . "</p></td>
                          <td>" . date("d M, Y", strtotime($inv['invoice_date'])) . "</td>
                          <td>" . date("d M, Y", strtotime($inv['due_date'])) . "</td>
                          <td><span class='badge {$badge} p-2 text-uppercase'>" . htmlspecialchars($inv['payment_status']) . "</span></td>
                          <td>₹" . number_format($inv['total_invoice_value'], 2) . "</td>
                          <td>
                            <div class='dropdown'>
                              <button class='btn btn-soft-secondary btn-sm dropdown-toggle' type='button' data-bs-toggle='dropdown'>
                                <i class='las la-ellipsis-h align-middle fs-18'></i>
                              </button>
                              <ul class='dropdown-menu dropdown-menu-end'>
                                <li><a class='dropdown-item' href='invoice-details.php?invoice_no=" . urlencode(ltrim($inv['invoice_no'], '#')) . "'><i class='las la-eye fs-18 me-2 text-muted'></i> View</a></li>
                                <li><a class='dropdown-item' href='invoice-edit.php?id=" . $inv['id'] . "'><i class='las la-pen fs-18 me-2 text-muted'></i> Edit</a></li>
                                <li><a class='dropdown-item' href='generate-invoice.php?invoice_no=" . urlencode(ltrim($inv['invoice_no'], '#')) . "' target='_blank'><i class='las la-file-download fs-18 me-2 text-muted'></i> Download</a></li>
                                <li><a class='dropdown-item text-danger' href='invoice-delete.php?id=" . $inv['id'] . "' onclick=\"return confirm('Are you sure?')\"><i class='las la-trash-alt fs-18 me-2'></i> Delete</a></li>
                              </ul>
                            </div>
                          </td>
                        </tr>";
                        $i++;
                      }
                    } else {
                      echo "<tr><td colspan='7' class='text-center'>No invoices found.</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div> <!-- /.table-responsive -->
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<?php include('footer.php'); ?>
