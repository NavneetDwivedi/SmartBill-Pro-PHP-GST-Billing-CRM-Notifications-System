<?php
require 'config/db.php';
include 'header.php';

// Fetch all notifications with invoice number
$sql = "SELECT n.id, n.message, n.created_at, n.is_read, i.invoice_no
        FROM notifications n
        JOIN invoices i ON n.invoice_id = i.id
        ORDER BY n.created_at DESC";


$result = $conn->query($sql);
?>

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-xxl-9 mx-auto">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">All Notifications</h4>
            </div>
            <div class="card-body">
              <?php if ($result && $result->num_rows > 0): ?>
                <ul class="list-group">
                  <?php while ($row = $result->fetch_assoc()): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-start <?= $row['is_read'] ? '' : 'list-group-item-warning' ?>">
                      <div class="ms-2 me-auto">
                        <div>
                          <strong>#<?= htmlspecialchars($row['invoice_no']) ?>:</strong>
                          <?= htmlspecialchars($row['message']) ?>
                          <?php if (!$row['is_read']): ?>
                            <span class="badge bg-danger ms-2">New</span>
                          <?php endif; ?>
                        </div>
                        <small class="text-muted"><?= date("d M Y, h:i A", strtotime($row['created_at'])) ?></small>
                      </div>
                      <div class="d-flex align-items-center gap-2">
                        <?php if (!$row['is_read']): ?>
                          <a href="mark-read.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-success">Mark as Read</a>
                        <?php endif; ?>
                        <form action="delete-notification.php" method="post" onsubmit="return confirm('Delete this notification?')">
                          <input type="hidden" name="id" value="<?= $row['id'] ?>">
                          <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                      </div>
                    </li>
                  <?php endwhile; ?>
                </ul>
              <?php else: ?>
                <p class="text-muted">No notifications found.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
