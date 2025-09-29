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
</head>
<body>
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
					<i class="material-icons icon-card two">how_to_reg</i>
					<span class="text">
						<h3>40</h3>
						<p>Present Today</p>
					</span>
				</li>
				<li>
					<i class="material-icons icon-card three">inventory_2</i>
					<span class="text">
						<h3><?= $totalInventory; ?></h3>
						<p>Inventory</p>
					</span>
				</li>
				<li>
					<i class="material-icons icon-card four">event</i>
					<span class="text">
						<h3><?= $totalReservations ?></h3>
						<p>Reservations</p>
					</span>
				</li>
				<li>
					<i class="material-icons icon-card five">swap_horiz</i>
					<span class="text">
						<h3>5</h3>
						<p>Shift Requests</p>
					</span>
				</li>
			</ul>
        
			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Attendance</h3>
							<form action="#">
								<div class="form-input">
									<input type="search" placeholder="Search...">
									<button type="submit" class="search-btn"><i class='material-icons search-icon' >search</i></button>
								</div>
							</form>
						<i class="material-icons">tune</i>
						<select class="filterStatus">
							<option value="all">All</option>
							<option value="present">Present</option>
							<option value="late">Late</option>
							<option value="Absent">Absent</option>
						</select>
						
						<select class="filterStatus">
							<option value="all">All</option>
							<option value="present">Present</option>
							<option value="late">Late</option>
							<option value="Absent">Absent</option>
						</select>
					</div>
					<table>
						<thead>
							<tr>
								<th>Image</th>
								<th>Name</th>
								<th>Shift</th>
								<th>Time in</th>
								<th>Time Out</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<img src="https://placehold.co/50x50.png">
								</td>
								<td>
									<span>Micheal John</span>
									<p>micheal_john@mail.com</p></td>
								<td>Morning</td>
								<td>8:14 am</td>
								<td>6:20 pm</td>
								<td><span class="status late">Late</span></td>
							</tr>
							<tr>
								<td>
									<img src="https://placehold.co/50x50.png">
								</td>
								<td>
									<span>Ryan Doe</span>
									<p>riyan_doe@mail.com</p>
								</td>
								<td>Morning</td>
								<td>7:50 am</td>
								<td>6:07 pm</td>
								<td><span class="status present">Present</span></td>
							</tr>
							<tr>
								<td>
									<img src="https://placehold.co/50x50.png">
								</td>
								<td>
									<span>Tarry White</span>
									<p>tarry_white@mail.com</p>
								</td>
								<td>Morning</td>
								<td>0:00</td>
								<td>0:00</td>
								<td><span class="status absent">Absent</span></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="todo">
						<div class="head">
							<h3>Todos</h3>
							<i class='bx bx-plus icon'></i>
						</div>
						<div class="todo-filters">
						<button onclick="filterTodos('all')">All</button>
						<button onclick="filterTodos('completed')" class="completed">Completed</button>
						<button onclick="filterTodos('pending')" class="pending">Pending</button>
						</div>
						<ul class="todo-list">
						<li class="completed" data-progress="100">
							<span class="progress-text">%100</span>
							<p>Check Inventory</p>
							<i class='bx bx-dots-vertical-rounded menu-icon'>
								<dl class="content-menu">
									<dt class="menu-item"><a href="#">Edit</a></dt>
									<dt class="menu-item"><a href="#">Delete</a></dt>
									<dt class="menu-item"><a href="#">Mark as Pending</a></dt>
								</dl>
							</i>
						</li>
						<li class="completed" data-progress="100">
							<span class="progress-text">%100</span>
							<p>Manage Delivery Team</p>
							<i class='bx bx-dots-vertical-rounded menu-icon'>
								<dl class="content-menu">
									<dt class="menu-item"><a href="#">Edit</a></dt>
									<dt class="menu-item"><a href="#">Delete</a></dt>
									<dt class="menu-item"><a href="#">Mark as Pending</a></dt>
								</dl>
							</i>
						</li>
						<li class="not-completed" data-progress="45">
							<span class="progress-text">%45</span>
							<p>Contact Salma: Confirm Delivery</p>
							<i class='material-icons menu-icon'> More-vert
								<dl class="content-menu">
									<dt class="menu-item"><a href="#">Edit</a></dt>
									<dt class="menu-item"><a href="#">Delete</a></dt>
									<dt class="menu-item"><a href="#">Mark as Pending</a></dt>
								</dl>
							</i>
						</li>
						<li class="not-completed" data-progress="67">
							<span class="progress-text">%67</span>
							<p>Update Shop Catalogue</p>
							<i class='bx bx-dots-vertical-rounded menu-icon'>
								<dl class="content-menu">
									<dt class="menu-item"><a href="#">Edit</a></dt>
									<dt class="menu-item"><a href="#">Delete</a></dt>
									<dt class="menu-item"><a href="#">Mark as Pending</a></dt>
								</dl>
							</i>
						</li>
						<li class="not-completed" data-progress="10">
							<span class="progress-text">%10</span>
							<p>Count Profit Analytics</p>
							<i class='bx bx-dots-vertical-rounded menu-icon'>
								<dl class="content-menu">
									<dt class="menu-item"><a href="#">Edit</a></dt>
									<dt class="menu-item"><a href="#">Delete</a></dt>
									<dt class="menu-item"><a href="#">Mark as Pending</a></dt>
								</dl>
							</i>
						</li>
					</ul>
					
    
        </div>
    </section>


    <script src="../js/dashboard.js"></script>
</body>
</html>