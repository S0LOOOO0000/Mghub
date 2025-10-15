<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


include __DIR__ . '/../php/get-employee.php';
include __DIR__ . '/../php/get-inventory.php';
include __DIR__ . '/../php/get-event.php';
include __DIR__ . '/../php/get-request.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <?php include '../includes/favicon.php'; ?>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href='https://unpkg.com/boxicons@2.1.4/dist/boxicons.js' rel='stylesheet'>
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
    <link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="../css/components/sidebar.css">
</head>
<body>
    <section class="sidebar">
    	<?php include '../includes/admin-sidebar.php'; ?>
    </section>

    <section class="content" >
    	    <nav>
                <!-- Menu Icon -->
                <i class="material-icons icon-menu">menu</i>
                <!-- Searchbar -->
                <form action="#">
                    <div class="form-input">
                        <input type="search" placeholder="Search...">
                        <button type="submit" class="search-btn"><i class='material-icons search-icon' >search</i></button>
                    </div>
                </form>
                <!-- Notification Bell and Profile -->
                <?php include '../includes/admin-navbar.php'; ?>
            </nav>
    
    	<div class="main">
			<div class="head-title-with-cards">
				<div class="head-title">
					<div class="left">
						<h1>Dashboard</h1>
						<ul class="breadcrumb">
							<li> <a>Dashboard</a> </li>
							<li> <i class='material-icons right-icon'>chevron_right</i></li>
							<li> <a class="active">Home</a> </li>
						</ul>
					</div>
				</div>
				
				<ul class="box-info">
					<li>
						<i class="material-icons icon-card one">groups</i>
						<span class="text">
							<h3><?= $totalEmployees; ?></h3>
							<p>Employees</p>
						</span>
					</li>
					<li>
						<i class="material-icons icon-card two">inventory_2</i>
						<span class="text">
							<h3><?= $totalInventory; ?></h3>
							<p>Inventory</p>
						</span>
					</li>
					<li>
						<i class="material-icons icon-card three">event</i>
						<span class="text">
							<h3><?= $totalReservations ?></h3>
							<p>Reservations</p>
						</span>
					</li>
					<li>
						<i class="material-icons icon-card four">swap_horiz</i>
						<span class="text">
							<h3><?= $totalRequests; ?></h3>
							<p>Shift Requests</p>
						</span>
					</li>
				</ul>
			</div>
			
        
			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Requests</h3>
						<i class="material-icons">tune</i>
						<select class="filterStatus">
							<option value="all">All</option>
							<option value="pending">Pending</option>
							<option value="approved">Approved</option>
							<option value="declined">Declined</option>
						</select>
						
						<select class="filterType">
							<option value="all">All Types</option>
							<option value="on_leave">On Leave</option>
							<option value="change_shift">Change Shift</option>
						</select>
					</div>
					<table>
						<thead>
							<tr>
								<th>#</th>
								<th>Employee</th>
								<th>Station & Role</th>
								<th>Shift</th>
								<th>Request Type</th>
								<th>Details</th>
								<th>Reason</th>
								<th>Target Date</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($requests)) : ?>
								<?php 
								$counter = 1;
								// Show all requests for dashboard with pagination
								$dashboardRequests = $requests;
								?>
								<?php foreach ($dashboardRequests as $row): ?>
									<tr>
										<td><?= $counter++; ?></td>
										<td>
											<span><strong><?= htmlspecialchars($row['requester_first_name'] . " " . $row['requester_last_name']); ?></strong></span>
											<p><?= htmlspecialchars($row['requester_email']); ?></p>
										</td>
										<td class="req-station">
											<span><?= htmlspecialchars($row['requester_station']); ?></span>
											<p><?= htmlspecialchars($row['requester_role']); ?></p>
										</td>
										<td class="emp-shift shift <?= strtolower($row['requester_shift']); ?>">
											<?= htmlspecialchars($row['requester_shift']); ?>
										</td>
										<td class="req-type">
											<span><?= htmlspecialchars($row['request_type']); ?></span>
										</td>
										<td>
											<?php if ($row['request_type'] === 'Change Shift' && !empty($row['target_employee_id'])): ?>
												Swap with: 
												<strong><?= htmlspecialchars($row['target_first_name'] . " " . $row['target_last_name']); ?></strong>
											<?php elseif ($row['request_type'] === 'On Leave'): ?>
												Leave Type: <strong><?= htmlspecialchars($row['leave_type']); ?></strong>
											<?php else: ?>
												-
											<?php endif; ?>
										</td>
										<td><div class="reason-text"><?= htmlspecialchars($row['reason']); ?></div></td>
										<td><?= date("M j, Y", strtotime($row['target_date'])); ?></td>
										<td class="req-status">
											<span class="status <?= strtolower($row['status']); ?>">
												<?= htmlspecialchars($row['status']); ?>
											</span>
										</td>
										<td class="text-center">
											<?php if($row['status'] == 'Pending') : ?>
												<div class="action-buttons">
													<button type="button" class="btn-approve" data-id="<?= $row['request_id']; ?>" title="Approve">✓</button>
													<button type="button" class="btn-reject" data-id="<?= $row['request_id']; ?>" title="Reject">✗</button>
													<button type="button" class="btn-view" data-id="<?= $row['request_id']; ?>" title="View">👁</button>
												</div>
											<?php else: ?>
												<div class="action-buttons">
													<button class="btn-view" data-id="<?= $row['request_id']; ?>" title="View">👁</button>
												</div>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr><td colspan="10">No requests found</td></tr>
							<?php endif; ?>
						</tbody>
					</table>
					
					<!-- Pagination Controls -->
					<div class="table-pagination-container">
						<div class="total-rows" id="paginationInfo">
							Loading...
						</div>
						<div class="table-pagination" id="requestPagination"></div>
					</div>
				</div>
				
				
				<div class="inventory">
						<div class="head">
							<h3>Recent Inventory</h3>
							<div class="inventory-filters">
								<button onclick="filterInventory('all')" class="active">All</button>
								<button onclick="filterInventory('low')">Low Stock</button>
								<button onclick="filterInventory('out')">Out of Stock</button>
							</div>
						</div>
						
						<div class="inventory-list">
							<?php if (!empty($inventory)) : ?>
								<?php 
								// Show all inventory items for dashboard
								$dashboardInventory = $inventory;
								?>
								<?php foreach ($dashboardInventory as $item): ?>
									<div class="inventory-item" data-status="<?= strtolower(str_replace(' ', '_', $item['item_status'])); ?>">
										<div class="item-info">
											<h4><?= htmlspecialchars($item['item_name']); ?></h4>
											<p class="item-category"><?= htmlspecialchars($item['item_category']); ?></p>
										</div>
										<div class="item-details">
											<span class="quantity"><?= $item['item_quantity']; ?> pcs</span>
											<span class="status-badge status-<?= strtolower(str_replace(' ', '_', $item['item_status'])); ?>">
												<?= $item['item_status']; ?>
											</span>
										</div>
									</div>
								<?php endforeach; ?>
							<?php else: ?>
								<div class="no-items">
									<p>No inventory items found</p>
								</div>
							<?php endif; ?>
						</div>
						
						<!-- View All Button -->
						<div class="inventory-footer">
							<a href="admin-inventory.php" class="view-all-btn">
								<span>View All Inventory</span>
								<i class='material-icons'>arrow_forward</i>
							</a>
						</div>
					
    
        </div>
    </section>

	<?php include '../includes/preview-request-modal.php'; ?>						
	<?php include '../includes/request-approval-modal.php'; ?>

	<script src="../js/dashboard.js?v=2"></script>
	<script src="../js/dropdown.js"> </script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="../js/dashboard.js"></script>
    <script src="../js/employee.js"></script>
	<script src="../js/request-approval.js"></script>

</body>
</html>