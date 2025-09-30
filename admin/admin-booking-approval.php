<?php
require __DIR__ . '/../config/database-connection.php';

// Fetch all pending + declined requests
$requests = [];
$sql = "SELECT * FROM tbl_event_pending ORDER BY event_date DESC, event_time DESC";
$result = $conn->query($sql);
if($result){
    while($row = $result->fetch_assoc()){
        $requests[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Booking Approvals</title>
  <?php include '../includes/favicon.php'; ?>
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
        <form action="#">
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
            <h1>Customer Booking Requests</h1>
        </div>

        <div class="table-container">
            <div class="table-card">
                <table id="bookingTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>CUSTOMER</th>
                            <th>CONTACT</th>
                            <th>EVENT NAME</th>
                            <th>DATE & TIME</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($requests)): $counter=1; ?>
                        <?php foreach($requests as $row): ?>
                        <tr>
                            <td><?= $counter++; ?></td>
                            <td><strong><?= htmlspecialchars($row['customer_name']); ?></strong><br><?= htmlspecialchars($row['customer_email']); ?></td>
                            <td><?= htmlspecialchars($row['customer_contact']); ?></td>
                            <td><?= htmlspecialchars($row['event_name']); ?></td>
                            <td><?= date("F j, Y h:i A", strtotime($row['event_date'].' '.$row['event_time'])); ?></td>
                            <td class="status <?= strtolower($row['event_status']); ?>"><?= htmlspecialchars($row['event_status']); ?></td>
                            <td>
                                <?php if($row['event_status']=='Pending'): ?>
                                    <button class="action-btn approve" data-id="<?= $row['pending_id']; ?>">Approve</button>
                                    <button class="action-btn decline" data-id="<?= $row['pending_id']; ?>">Decline</button>
                                <?php else: ?>
                                    <span>-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr><td colspan="7">No booking requests found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script src="../js/admin-booking-approval.js"></script>
</body>
</html>
