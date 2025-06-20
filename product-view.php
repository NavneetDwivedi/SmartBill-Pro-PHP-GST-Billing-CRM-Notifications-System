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
      <div class="col-md-6 offset-md-3">
        <div class="card">
          <div class="card-body">
            <h5>Product Details</h5>
            <p><strong>Name:</strong> <?= htmlspecialchars($product['name']) ?></p>
            <p><strong>SAC Code:</strong> <?= $product['sac_code'] ?></p>
            <p><strong>GST%:</strong> <?= $product['gst_percent'] ?>%</p>
            <p><strong>Price:</strong> ₹<?= number_format($product['rate'], 2) ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include('footer.php'); ?>