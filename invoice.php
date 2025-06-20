<?php
include('header.php');

// Correct SQL with proper column names
$sql = "SELECT invoices.id, invoices.invoice_no, invoices.invoice_date AS date, invoices.total_invoice_value AS amount, invoices.payment_status AS status,
               clients.name AS client_name, clients.email
        FROM invoices
        JOIN clients ON invoices.client_id = clients.id
        ORDER BY invoices.id DESC";

$result = $conn->query($sql);
?>



<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Invoice</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Invoice</a></li>
                                        <li class="breadcrumb-item active">Invoice</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    

  </div>
</div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive table-card" style="min-height: 100vh;">
                <table class="table table-hover table-nowrap align-middle mb-0">
                  <thead>
                    <tr class="text-muted text-uppercase">
                      <th>#</th>
                      <th>Invoice ID</th>
                      <th>Client</th>
                      <th>Email</th>
                      <th>Date</th>
                      <th>Billed</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($result && $result->num_rows > 0): 
                      $count = 1;
                      while($row = $result->fetch_assoc()): ?>
                      <tr>
                        <td><?= $count++; ?></td>
                        <td><p class="fw-medium mb-0"><?= htmlspecialchars($row['invoice_no']) ?></p></td>
                        <td>
                          <span class="text-body fw-medium"><?= htmlspecialchars($row['client_name']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= date("d M, Y", strtotime($row['date'])) ?></td>
                        <td>₹<?= number_format($row['amount'], 2) ?></td>
                        <td>
                          <?php
                          $status = strtolower($row['status']);
                          if ($status == 'paid') {
                              $badge = 'bg-success-subtle text-success';
                          } elseif ($status == 'unpaid') {
                              $badge = 'bg-primary text-white';
                          } elseif ($status == 'cancelled') {
                              $badge = 'bg-danger-subtle text-danger';
                          } else {
                              $badge = 'bg-secondary text-dark';
                          }
                          ?>
                          <span class="badge <?= $badge ?> p-2 text-uppercase"><?= htmlspecialchars($row['status']) ?></span>
                        </td>
                        <td>
                          <div class="dropdown">
                            <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                              <i class="las la-ellipsis-h align-middle fs-18"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                              <li><a class="dropdown-item" href="invoice-details.php?invoice_no=<?= urlencode(ltrim($row['invoice_no'], '#')) ?>"><i class="las la-eye fs-18 me-2 text-muted"></i> View</a></li>
                              <li><a class="dropdown-item" href="invoice-edit.php?id=<?= $row['id'] ?>"><i class="las la-pen fs-18 me-2 text-muted"></i> Edit</a></li>
                              <li>
  <a class="dropdown-item" href="generate-invoice.php?invoice_no=<?= urlencode(ltrim($row['invoice_no'], '#')) ?>" target="_blank">
    <i class="las la-file-download fs-18 me-2 text-muted"></i> Download
  </a>
</li>

                              <li><a class="dropdown-item text-danger" 
   href="invoice-delete.php?id=<?= $row['id'] ?>" 
   onclick="return confirm('Are you sure?')">
   <i class="las la-trash-alt fs-18 me-2"></i> Delete
</a>
</li>
                            </ul>
                          </div>
                        </td>
                      </tr>
                    <?php endwhile; else: ?>
                      <tr><td colspan="8" class="text-center">No invoices found.</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div> <!-- /.table-responsive -->
            </div> <!-- /.card-body -->
          </div> <!-- /.card -->
        </div> <!-- /.col-xl-12 -->
      </div> <!-- /.row -->
    </div> <!-- /.container-fluid -->
  </div> <!-- /.page-content -->
</div> <!-- /.main-content -->

<?php if (isset($_GET['deleted']) && $_GET['deleted'] === 'success'): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
  Invoice deleted successfully.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>


<?php include('footer.php'); ?>
