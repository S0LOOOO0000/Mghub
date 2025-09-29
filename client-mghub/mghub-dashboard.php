<?php
include __DIR__ . '/../php/get-employee.php';
include __DIR__ . '/../php/get-inventory.php';
include __DIR__ . '/../php/get-event.php';

session_start();
include __DIR__ . '/../php/get-inventory.php';

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
<body class="client-mghub">
    <section class="sidebar">
    	<?php include '../includes/mghub-sidebar.php'; ?>
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
						<h3>2</h3>
						<p>Shift Requests</p>
					</span>
				</li>
			</ul>
		</div>
        
		<div class="table-data">
			<div class="order">
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
					<a href="mghub-inventory.php" class="view-all-btn">
						<span>View All Inventory</span>
						<i class='material-icons'>arrow_forward</i>
					</a>
				</div>
			</div>
			
			<div class="todo">
				<div class="head">
					<h3>To-Do List</h3>
					<i class='bx bx-plus icon'></i>
				</div>
				<div class="todo-filters">
					<button onclick="filterTodos('all')" class="active">All</button>
					<button onclick="filterTodos('completed')" class="completed">Completed</button>
					<button onclick="filterTodos('pending')" class="pending">Pending</button>
				</div>
				<ul class="todo-list">
					<li class="completed" data-progress="100">
						<p>Check daily food inventory</p>
						<div class="todo-actions">
							<span class="progress-text">100%</span>
							<i class='bx bx-dots-vertical-rounded menu-icon'>
								<dl class="content-menu">
									<dt class="menu-item"><a href="#">Edit</a></dt>
									<dt class="menu-item"><a href="#">Delete</a></dt>
									<dt class="menu-item"><a href="#">Mark as Pending</a></dt>
								</dl>
							</i>
						</div>
					</li>
					<li class="completed" data-progress="100">
						<p>Prep kitchen for lunch service</p>
						<div class="todo-actions">
							<span class="progress-text">100%</span>
							<i class='bx bx-dots-vertical-rounded menu-icon'>
								<dl class="content-menu">
									<dt class="menu-item"><a href="#">Edit</a></dt>
									<dt class="menu-item"><a href="#">Delete</a></dt>
									<dt class="menu-item"><a href="#">Mark as Pending</a></dt>
								</dl>
							</i>
						</div>
					</li>
					<li class="not-completed" data-progress="70">
						<p>Update daily specials menu</p>
						<div class="todo-actions">
							<span class="progress-text">70%</span>
							<i class='bx bx-dots-vertical-rounded menu-icon'>
								<dl class="content-menu">
									<dt class="menu-item"><a href="#">Edit</a></dt>
									<dt class="menu-item"><a href="#">Delete</a></dt>
									<dt class="menu-item"><a href="#">Mark as Completed</a></dt>
								</dl>
							</i>
						</div>
					</li>
					<li class="not-completed" data-progress="40">
						<p>Coordinate with delivery drivers</p>
						<div class="todo-actions">
							<span class="progress-text">40%</span>
							<i class='bx bx-dots-vertical-rounded menu-icon'>
								<dl class="content-menu">
									<dt class="menu-item"><a href="#">Edit</a></dt>
									<dt class="menu-item"><a href="#">Delete</a></dt>
									<dt class="menu-item"><a href="#">Mark as Completed</a></dt>
								</dl>
							</i>
						</div>
					</li>
					<li class="not-completed" data-progress="20">
						<p>Review customer feedback reports</p>
						<div class="todo-actions">
							<span class="progress-text">20%</span>
							<i class='bx bx-dots-vertical-rounded menu-icon'>
								<dl class="content-menu">
									<dt class="menu-item"><a href="#">Edit</a></dt>
									<dt class="menu-item"><a href="#">Delete</a></dt>
									<dt class="menu-item"><a href="#">Mark as Completed</a></dt>
								</dl>
							</i>
						</div>
					</li>
				</ul>
				
				<!-- Add Button at Bottom Right -->
				<div class="todo-add-button">
					<button class="add-todo-btn" title="Add New Todo">
						<i class='material-icons'>add</i>
					</button>
				</div>
					
    
        </div>
    </section>


	<script src="../js/dropdown.js"> </script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="../js/mghub-dashboard.js"></script>
    <script src="../js/attendance.js"></script>
    <script src="../js/employee.js"></script>
    <script src="../js/inventory.js"></script>
</body>
</html>