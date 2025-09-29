<?php
include __DIR__ . '/../php/get-employee.php';
include __DIR__ . '/../php/get-inventory.php';
include __DIR__ . '/../php/get-event.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <?php include '../includes/favicon.php'; ?>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!-- My CSS -->
	<link rel="stylesheet" href="../css/style.css">
	
</head>
<body>
    <section class="sidebar">
        <?php include '../includes/client-sidebar.php'; ?>
    </section>

    <section class="content" id="content">
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
            <!-- Main Layout -->    
        <div class="main">
                    <!-- Breadcrumb -->
                    <div class="head-title">
                        <div class="left">
                            <h1>Attendance</h1>
                            <ul class="breadcrumb">
                                <li> <a>Attendance Management</a> </li>
                                <li> <i class='material-icons right-icon'>chevron_right</i></li>
                                <li> <a class="active">Home</a> </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Table -->
                        <div class="table-container">
                            <div class="table-card">

                                <!-- Tab 1: Current Attendance -->
                                <div id="attendanceTable" class="tab-content" style="display:block;">
                                    <div class="head">
                                        <h2>Attendance Management</h2>
                                        <?php include '../includes/attendance-dropdown.php'; ?>

                                        <button class="btn-time-in-icon" onclick="openQrScanner('time_in')">
                                            <i class="material-icons">login</i> Request Change
                                        </button>
                                        <button class="btn-time-out-icon" onclick="openQrScanner('time_out')">
                                            <i class="material-icons">logout</i> Request Leave
                                        </button>
                                    </div>

                                    <table class="attendance-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>IMAGE</th>
                                                <th>NAME</th>
                                                <th>STATION & ROLE</th>
                                                <th>SHIFT</th>
                                                <th>STATUS</th>
                                                <th>DATE</th>
                                            </tr>
                                        </thead>
                                        <tbody id="attendance-body">
                                            <?php include __DIR__ . '/../php/get-attendance-admin.php'; ?>
                                        </tbody>
                                    </table> 
                                </div>
                            </div>

                            <div class="table-pagination-container">
                                <div class="total-rows" id="totalRows">
                                    Showing 1-<?= $totalEmployees ?> of <?= $totalEmployees ?> records
                                </div>
                                <div class="table-pagination" id="pagination"></div>
                            </div>
                        </div>




            
            <?php include '../includes/attendance-modal.php'; ?>
            <?php include '../includes/request-modal.php'; ?>
        </div>
    </section>

                            

<script src="https://unpkg.com/html5-qrcode"></script>
<script src="../js/dashboard.js"></script>
<script src="../js/attendance.js"></script>
<script src="../js/employee.js"></script>
<script src="../js/request.js"></script>

<script src="../js/dropdown.js"></script>
<script src="../js/filter.js"></script>

<script src="../js/fetch-client-attendance.js"></script>
</body>
</html>