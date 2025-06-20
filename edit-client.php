<?php
include('header.php');
require('config/db.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid client ID.");
}
$client_id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();
?>

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-md-10">
          <div class="card">
            <div class="card-body">
              <h4 class="mb-4">Edit Client</h4>
              <form action="update-client.php" method="POST">
                <input type="hidden" name="id" value="<?= $client['id'] ?>">
                <div class="row">
                  <div class="col-md-6">
                    <label>Company Name</label>
                    <input type="text" name="company_name" class="form-control" value="<?= htmlspecialchars($client['company_name']) ?>">
                  </div>
                  <div class="col-md-6">
                    <label>Client Name</label>
                    <input type="text" name="client_name" class="form-control" value="<?= htmlspecialchars($client['name']) ?>" required>
                  </div>
                  <div class="col-md-6 mt-2">
                    <label>PAN</label>
                    <input type="text" name="client_pan" class="form-control" value="<?= htmlspecialchars($client['pan']) ?>">
                  </div>
                  <div class="col-md-6 mt-2">
                    <label>GSTIN</label>
                    <input type="text" name="client_gstin" class="form-control" value="<?= htmlspecialchars($client['gstin']) ?>">
                  </div>
                  <div class="col-md-6 mt-2">
                    <label>Date of Incorporation</label>
                    <input type="date" name="incorporation_date" class="form-control" value="<?= $client['incorporation_date'] ?>">
                  </div>
                  <div class="col-md-6 mt-2">
                    <label>Contact No.</label>
                    <input type="text" name="client_contact" class="form-control" value="<?= htmlspecialchars($client['contact']) ?>">
                  </div>
                  <div class="col-md-6 mt-2">
                    <label>Email ID</label>
                    <input type="email" name="client_email" class="form-control" value="<?= htmlspecialchars($client['email']) ?>">
                  </div>
                  <div class="col-md-6 mt-2">
                    <label>Website</label>
                    <input type="text" name="client_website" class="form-control" value="<?= htmlspecialchars($client['website']) ?>">
                  </div>
                  <div class="col-md-6 mt-2">
                    <label>City</label>
                    <input type="text" name="client_city" class="form-control" value="<?= htmlspecialchars($client['city']) ?>">
                  </div>
                  <div class="col-md-6 mt-2">
                    <label>State</label>
                    <input type="text" name="client_state" class="form-control" value="<?= htmlspecialchars($client['state']) ?>">
                  </div>
                  <div class="col-md-12 mt-2">
                    <label>Address</label>
                    <textarea name="client_address" class="form-control" rows="2" required><?= htmlspecialchars($client['address']) ?></textarea>
                  </div>
                </div>
                <div class="mt-4 text-end">
                  <button type="submit" class="btn btn-primary">Update Client</button>
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
