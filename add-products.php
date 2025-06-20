<?php include('header.php'); ?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="col-md-8 offset-md-2">
        <div class="card">
          <div class="card-body">
            <h5>Add New Product</h5>
            <form action="product-save.php" method="POST">
              <div class="mb-3">
                <label>Product Name</label>
                <input type="text" name="name" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>SAC Code</label>
                <input type="text" name="sac_code" class="form-control">
              </div>
              <div class="mb-3">
    <label>GST (%)</label>
    <select name="gst_percent" class="form-control" required>
        <option value="">Select GST Rate</option>
        <option value="0">0%</option>
        <option value="5">5%</option>
        <option value="12">12%</option>
        <option value="18">18%</option>
        <option value="28">28%</option>
    </select>
</div>

              <div class="mb-3">
                <label>Price</label>
                <input type="number" name="price" class="form-control" step="0.01">
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include('footer.php'); ?>




