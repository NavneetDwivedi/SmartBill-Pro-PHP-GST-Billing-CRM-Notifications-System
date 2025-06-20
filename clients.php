<?php
include('header.php');

// Fetch client data
$query = "SELECT * FROM clients ORDER BY id DESC";
$result = $conn->query($query);
?>

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <!-- Page Title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Clients</h4>
            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Client</a></li>
                <li class="breadcrumb-item active">Client List</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <!-- Add Client Button -->
      <div class="row pb-4 gy-3">
        <div class="col-sm-4">
          <a href="add-clients.php" class="btn btn-primary"><i class="las la-plus me-1"></i> Add New</a>
        </div>
      </div>

      <!-- Clients Table -->
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive table-card">
                <table class="table table-hover table-nowrap align-middle mb-0">
                  <thead>
                    <tr class="text-muted text-uppercase">
                      <th>Name</th>
                      <th>GSTIN</th>
                      <th>Contact</th>
                      <th>Email</th>
                      <th>Address</th>
                      <th style="width: 12%;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($result->num_rows > 0): ?>
                      <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                          <td><?= htmlspecialchars($row['name']) ?></td>
                          <td><?= htmlspecialchars($row['gstin']) ?></td>
                          <td><?= htmlspecialchars($row['contact']) ?></td>
                          <td><?= htmlspecialchars($row['email']) ?></td>
                          <td><?= nl2br(htmlspecialchars($row['address'])) ?></td>
                          <td>
                            <ul class="list-inline hstack gap-2 mb-0">
                              <li class="list-inline-item" title="View">
                                <a href="view-client.php?id=<?= $row['id'] ?>" class="btn btn-soft-info btn-sm">
                                  <i class="las la-eye fs-17 align-middle"></i>
                                </a>
                              </li>
                              <li class="list-inline-item" title="Edit">
                                <a href="edit-client.php?id=<?= $row['id'] ?>" class="btn btn-soft-warning btn-sm">
                                  <i class="las la-pen fs-17 align-middle"></i>
                                </a>
                              </li>
                              <li class="list-inline-item" title="Delete">
                                <a href="delete-client.php?id=<?= $row['id'] ?>" class="btn btn-soft-danger btn-sm" onclick="return confirm('Are you sure you want to delete this client?')">
                                  <i class="las la-trash-alt fs-17 align-middle"></i>
                                </a>
                              </li>
                            </ul>
                          </td>
                        </tr>
                      <?php endwhile; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="6" class="text-center">No clients found.</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div><!-- container-fluid -->
  </div><!-- End Page-content -->
</div>

<?php include('footer.php'); ?>
