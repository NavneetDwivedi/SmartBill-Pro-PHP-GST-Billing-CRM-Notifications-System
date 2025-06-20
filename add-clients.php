<?php
include('header.php');
?>

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Add New Client</h4>
          </div>
        </div>
      </div>

      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-body">
             <form action="add-client-save.php" method="POST">
  <div class="row">
    <div class="col-md-6">
      <label>Company Name</label>
      <input type="text" name="company_name" class="form-control" placeholder="e.g. Company Pvt. Ltd." required>
    </div>
    <div class="col-md-6">
      <label>Client Name</label>
      <input type="text" name="client_name" class="form-control" placeholder="e.g. Jons" required>
    </div>
    <div class="col-md-6 mt-2">
      <label>PAN</label>
      <input type="text" name="client_pan" class="form-control" placeholder="e.g. ABCDE1234F">
    </div>
    <div class="col-md-6 mt-2">
      <label>GSTIN</label>
      <input type="text" name="client_gstin" class="form-control" placeholder="e.g. 22ABCDE1234F1Z5">
    </div>
    <div class="col-md-6 mt-2">
      <label>Date of Incorporation</label>
      <input type="date" name="incorporation_date" class="form-control">
    </div>
    <div class="col-md-6 mt-2">
      <label>Mobile</label>
      <input type="text" name="client_contact" class="form-control" placeholder="e.g. 9876543210">
    </div>
    <div class="col-md-6 mt-2">
      <label>Email</label>
      <input type="email" name="client_email" class="form-control" placeholder="e.g. client@example.com">
    </div>
    <div class="col-md-6 mt-2">
      <label>City</label>
      <input type="text" name="client_city" class="form-control" placeholder="e.g. Jaipur">
    </div>
    <div class="col-md-6 mt-2">
      <label>State</label>
      <input type="text" name="client_state" class="form-control" placeholder="e.g. Rajasthan">
    </div>
    <div class="col-md-6 mt-2">
      <label>Website URL</label>
      <input type="url" name="client_website" class="form-control" placeholder="e.g. https://clientcompany.com">
    </div>
    <div class="col-md-12 mt-2">
      <label>Address</label>
      <textarea name="client_address" class="form-control" rows="2" placeholder="Full address including PIN" required></textarea>
    </div>
  </div>
  <div class="mt-4 text-end">
    <button type="submit" class="btn btn-primary">Save Client</button>
  </div>
</form>

            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<?php include('footer.php'); ?>
