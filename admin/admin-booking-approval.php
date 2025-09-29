<?php
require __DIR__ . '/../config/database-connection.php';

// Fetch all pending/declined requests
$requests = [];
$sql = "SELECT * FROM tbl_event_pending ORDER BY event_date DESC, event_time DESC";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
}
$totalRequests = count($requests);
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Booking Approvals</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<section class="sidebar">
  <?php include '../includes/admin-sidebar.php'; ?>
</section>

<section class="content" id="content">
  <nav>
    <i class="material-icons icon-menu">menu</i>
    <form action="javascript:void(0);">
      <div class="form-input">
        <input type="search" id="bookingSearch" placeholder="Search bookings...">
        <button type="submit" class="search-btn">
          <i class="material-icons search-icon">search</i>
        </button>
      </div>
    </form>
    <?php include '../includes/admin-navbar.php'; ?>
  </nav>

  <div class="main">
    <div class="head-title">
      <div class="left">
        <h1>Booking Approval</h1>
      </div>
    </div>

    <div class="table-container">
      <div class="table-card">
        <div class="head">
          <h2>Customer Booking Requests</h2>
        </div>

        <table id="bookingTable">
          <thead>
            <tr>
              <th>#</th>
              <th>CUSTOMER</th>
              <th>CONTACT</th>
              <th>EVENT NAME</th>
              <th>DESCRIPTION</th>
              <th>DATE & TIME</th>
              <th>STATUS</th>
              <th>ACTION</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($requests)): $counter=1; ?>
              <?php foreach ($requests as $row): ?>
                <tr>
                  <td><?= $counter++; ?></td>
                  <td>
                    <strong><?= htmlspecialchars($row['customer_name']); ?></strong><br>
                    <?= htmlspecialchars($row['customer_email']); ?>
                  </td>
                  <td><?= htmlspecialchars($row['customer_contact']); ?></td>
                  <td><?= htmlspecialchars($row['event_name']); ?></td>
                  <td><?= htmlspecialchars($row['event_description']); ?></td>
                  <td><?= date("F j, Y h:i A", strtotime($row['event_date'].' '.$row['event_time'])); ?></td>
                  <td class="req-status">
                    <span class="<?= strtolower($row['event_status']); ?>">
                      <?= htmlspecialchars($row['event_status']); ?>
                    </span>
                  </td>
                  <td class="text-center">
                    <?php if ($row['event_status'] === 'Pending'): ?>
                      <div class="request-actions">
                        <!-- Approve -->
                        <div class="action-btn approve" 
                             data-id="<?= $row['pending_id']; ?>" 
                             title="Approve">
                          <i class="material-icons">check</i>
                        </div>
                        <!-- Decline -->
                        <div class="action-btn decline" 
                             data-id="<?= $row['pending_id']; ?>" 
                             title="Decline">
                          <i class="material-icons">close</i>
                        </div>
                        <!-- Preview -->
                        <div class="action-btn preview"
                             data-id="<?= $row['pending_id']; ?>"
                             data-name="<?= htmlspecialchars($row['customer_name']); ?>"
                             data-email="<?= htmlspecialchars($row['customer_email']); ?>"
                             data-contact="<?= htmlspecialchars($row['customer_contact']); ?>"
                             data-event="<?= htmlspecialchars($row['event_name']); ?>"
                             data-date="<?= htmlspecialchars($row['event_date']); ?>"
                             data-time="<?= htmlspecialchars($row['event_time']); ?>"
                             data-description="<?= htmlspecialchars($row['event_description']); ?>"
                             data-status="<?= htmlspecialchars($row['event_status']); ?>"
                             title="Preview">
                          <i class="material-icons">visibility</i>
                        </div>
                      </div>
                    <?php else: ?>
                      <span>-</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="8">No booking requests found</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <div class="table-pagination-container">
        <div class="total-rows" id="bookingTotalRows">
          Showing 1-<?= $totalRequests; ?> of <?= $totalRequests; ?> requests
        </div>
        <div class="table-pagination" id="bookingPagination"></div>
      </div>
    </div>
  </div>
</section>

<!-- Preview Modal -->
<?php include '../includes/preview-booking-approval.php'; ?>

<script src="../js/filter.js"></script>
<script src="../js/bookings-approval.js"></script>
<script src="../js/dashboard.js"></script>

</body>
</html>
