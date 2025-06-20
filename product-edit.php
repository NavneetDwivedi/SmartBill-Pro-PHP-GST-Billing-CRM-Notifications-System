<?php
require 'config/db.php';
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM products WHERE id = $id");
$product = $result->fetch_assoc();
include('header.php');
?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="col-md-8 offset-md-2">
        <div class="card">
          <div class="card-body">
            <h5>Edit Product</h5>
            <form action="product-update.php" method="POST">
              <input type="hidden" name="id" value="<?= $product['id'] ?>">
              <div class="mb-3">
                <label>Product Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
              </div>
              <div class="mb-3">
                <label>SAC Code</label>
                <input type="text" name="sac_code" class="form-control" value="<?= $product['sac_code'] ?>">
              </div>
             <div class="mb-3">
    <label>GST (%)</label>
    <select name="gst_percent" class="form-control" required>
        <option value="">Select GST Rate</option>
        <option value="0" <?= $product['gst_percent'] == 0 ? 'selected' : '' ?>>0%</option>
        <option value="5" <?= $product['gst_percent'] == 5 ? 'selected' : '' ?>>5%</option>
        <option value="12" <?= $product['gst_percent'] == 12 ? 'selected' : '' ?>>12%</option>
        <option value="18" <?= $product['gst_percent'] == 18 ? 'selected' : '' ?>>18%</option>
        <option value="28" <?= $product['gst_percent'] == 28 ? 'selected' : '' ?>>28%</option>
    </select>
</div>

              <div class="mb-3">
                <label>Price</label>
                <input type="number" name="price" class="form-control" step="0.01" value="<?= $product['rate'] ?>">
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-warning">Update</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include('footer.php'); ?>