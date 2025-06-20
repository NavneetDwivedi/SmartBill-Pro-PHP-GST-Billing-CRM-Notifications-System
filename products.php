<?php include('header.php'); ?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12 d-flex justify-content-between align-items-center mb-3">
          <h4>Product List</h4>
          <a href="add-products.php" class="btn btn-primary">Add New Product</a>
        </div>
      </div>

      <div class="card">
        <div class="card-body table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>SAC Code</th>
                <th>GST%</th>
                <th>Price (₹)</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              require('config/db.php');
              $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
              if ($result->num_rows > 0):
                $i = 1;
                while ($row = $result->fetch_assoc()):
              ?>
                <tr>
                  <td><?= $i++ ?></td>
                  <td><?= htmlspecialchars($row['name']) ?></td>
                  <td><?= htmlspecialchars($row['sac_code']) ?></td>
                  <td><?= $row['gst_percent'] ?>%</td>
                  <td>₹<?= number_format($row['rate'], 2) ?></td>
                  <td>
                    <a href="product-view.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">View</a>
                    <a href="product-edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="product-delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                  </td>
                </tr>
              <?php endwhile; else: ?>
                <tr><td colspan="6" class="text-center">No products found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>
<?php include('footer.php'); ?>
