<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['branch'] = "MG Cafe";
include __DIR__ . '/../php/get-employee.php';
include __DIR__ . '/../php/get-inventory.php';
include __DIR__ . '/../php/get-event.php';

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
	<link rel="stylesheet" href="../css/components/table.css">
</head>
<body class="client-dashboard">
    <section class="sidebar">
    	<?php include '../includes/client-sidebar.php'; ?>
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
						<h3>3</h3>
						<p>Shift Requests</p>
					</span>
				</li>
			</ul>
		</div>
        
		<div class="table-data">
			<div class="order">
				<div class="head">
					<h3>Bookings</h3>
					<i class="material-icons">tune</i>
					<select class="filterStatus">
						<option value="all">All</option>
						<option value="Booked">Booked</option>
						<option value="Completed">Completed</option>
						<option value="Cancelled">Cancelled</option>
					</select>
					
					<select class="filterType">
						<option value="all">All Types</option>
						<option value="birthday">Birthday Party</option>
						<option value="wedding">Wedding</option>
						<option value="corporate">Corporate Event</option>
					</select>
				</div>
				<table>
					<thead>
						<tr>
							<th>#</th>
							<th>Customer</th>
							<th>Event Type</th>
							<th>Date & Time</th>
							<th>Package</th>
							<th>Guests</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($events)) : ?>
							<?php 
							$counter = 1;
							// Show all bookings for dashboard with pagination
							$dashboardEvents = $events;
							?>
							<?php foreach ($dashboardEvents as $event): ?>
								<tr>
									<td><?= $counter++; ?></td>
									<td>
										<span><strong><?= htmlspecialchars($event['customer_name']); ?></strong></span>
										<p><?= htmlspecialchars($event['customer_contact']); ?></p>
									</td>
									<td class="event-type">
										<span><?= htmlspecialchars($event['event_name']); ?></span>
									</td>
									<td>
										<span><?= date("M j, Y", strtotime($event['event_date'])); ?></span>
										<p><?= date("g:i A", strtotime($event['event_time'])); ?></p>
									</td>
									<td>Standard Package</td>
									<td>50-100 guests</td>
									<td class="booking-status">
										<span class="status <?= strtolower($event['event_status']); ?>">
											<?= htmlspecialchars($event['event_status']); ?>
										</span>
									</td>
									<td class="text-center">
										<div class="action-buttons">
											<button class="btn-view" onclick="window.location.href='client-bookings.php'" title="View Details">👁</button>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr><td colspan="8">No bookings found</td></tr>
						<?php endif; ?>
					</tbody>
				</table>
				
				<!-- Pagination Controls -->
				<div class="table-pagination-container">
					<div class="total-rows" id="paginationInfo">
						Loading...
					</div>
					<div class="table-pagination" id="bookingPagination"></div>
				</div>
			</div>
			
			<div class="todo">
				<div class="head">
					<h3>To-Do List</h3>
				</div>
				<div class="todo-filters">
					<button onclick="filterTodos('all')" class="active">All</button>
					<button onclick="filterTodos('completed')" class="completed">Completed</button>
					<button onclick="filterTodos('pending')" class="pending">Pending</button>
				</div>
				<ul class="todo-list">
					<!-- Todos will be loaded dynamically via JavaScript -->
					<li style="text-align: center; padding: 20px; color: #999;">Loading todos...</li>
				</ul>
				
				<!-- Add Button at Bottom Right -->
				<div class="todo-add-button">
					<button class="add-todo-btn" title="Add New Todo">
						<i class='material-icons'>add</i>
					</button>
				</div>
					
    
        </div>
    </section>

	<!-- Todo Modal -->
	<?php include '../includes/todo-modal.php'; ?>

	<!-- Set Dashboard Station for Todo System -->
	<script>
		const DASHBOARD_STATION = 'Cafe';
		const DASHBOARD_USER_ID = <?php echo $_SESSION['user_id']; ?>;
	</script>

	<script src="../js/dropdown.js"> </script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="../js/client-dashboard.js"></script>
    <script src="../js/attendance.js"></script>
    <script src="../js/employee.js"></script>
    <script src="../js/todo.js"></script>
</body>
</html>