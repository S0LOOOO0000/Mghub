<?php
include __DIR__ . '/../php/get-employee.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!-- My CSS -->
	<link rel="stylesheet" href="../css/style.css">


</head>
<body>
    <section class="sidebar">
        <?php include '../includes/admin-sidebar.php'; ?>
    </section>

        <section class="content" id="content">
                    <nav>
                        <!-- Menu Icon -->
                        <i class="material-icons icon-menu">menu</i>
                        <!-- Searchbar -->
                        <form action="#">
                            <div class="form-input">
                                <input type="search" id="employeeSearch" placeholder="Search...">
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
                                        <h1>Employees</h1>
                                        <ul class="breadcrumb">
                                            <li> <a>Employees Management</a> </li>
                                            <li> <i class='material-icons right-icon'>chevron_right</i></li>
                                            <li> <a class="active">Home</a> </li>
                                        </ul>
                                    </div>
                                </div>
                            <!-- Table -->
                            <div class="table-container">
                                <div class="table-card">
                                    <div class="head">
                                        <h2>Employee Management</h2>
                                        <!-- Filter Dropdown (Status, Shift and Station) -->
                                        <?php include '../includes/employee-dropdown.php' ?>
                                        
                                        <div class="gap"></div>
                                        <!-- Export Button -->
                                        <div class="custom-dropdown export-dropdown">
                                            <button type="button" class="dropdown-toggle">
                                                Export All <i class="material-icons dropdown-icon">expand_more</i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li data-export="pdf">Export PDF</li>
                                                <li data-export="excel">Export Excel</li>
                                                <li data-export="qrcode">Export QR Code</li>
                                            </ul>
                                        </div>

                                        
                                        <button type="button" class="btn-add" id="openModal">
                                            <i class="material-icons icon-add">add</i> Add
                                        </button>
                                    </div>

                                    <table id="employeeTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ID</th>
                                                <th>IMAGE</th>
                                                <th>NAME</th>
                                                <th>CONTACT</th>
                                                <th>STATION & ROLE</th>
                                                <th>SHIFT</th>
                                                <th>STATUS</th>
                                                <th>EMPLOYED</th>
                                                <th>QR CODE</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($employees)) : ?>
                                                <?php $counter = 1; ?>
                                                <?php foreach ($employees as $row): ?>
                                                    <tr>
                                                        <td><?= $counter++; ?></td>

                                                        <td class="emp-code"><?= htmlspecialchars($row['employee_code']); ?></td>

                                                        <td class="emp-image">
                                                            <img src="<?= !empty($row['employee_image']) ? '../images/employee-photos/' . htmlspecialchars($row['employee_image']) : 'https://placehold.co/50x50.png'; ?>" 
                                                                width="50" height="50" class="preview-img">
                                                        </td>

                                                        <td class="emp-name">
                                                            <span><strong><?= htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></strong></span>
                                                            <p><?= htmlspecialchars($row['email_address']); ?></p>
                                                        </td>

                                                        <td class="emp-contact"><?= htmlspecialchars($row['contact_number']); ?></td>

                                                        <td class="emp-station">
                                                            <span><?= htmlspecialchars($row['work_station']); ?></span>
                                                            <p><?= htmlspecialchars($row['role']); ?></p>
                                                        </td>

                                                        <td class="emp-shift">
                                                            <span class="shift <?= strtolower($row['shift']); ?>">
                                                                <?= htmlspecialchars($row['shift']); ?>
                                                            </span>
                                                        </td>

                                                        <td class="emp-status">
                                                            <span class="<?= strtolower($row['status']); ?>">
                                                                <?= htmlspecialchars($row['status']); ?>
                                                            </span>
                                                        </td>

                                                        <td><?= date("F j, Y", strtotime($row['created_at'])); ?></td>

                                                        <td class="emp-qr">
                                                            <img src="<?= !empty($row['employee_code']) ? '../images/qr-codes/' . htmlspecialchars($row['employee_code']) . '.png' : 'https://placehold.co/40x40.png'; ?>" 
                                                                alt="QR Code" width="40" class="preview-img">
                                                        </td>

                                                        <td>
                                                            <div class="icon-circle" tabindex="0">
                                                                <div class="dropdown-toggle icon-toggle">
                                                                    <i class="material-icons icon-more-vert">more_horiz</i>
                                                                </div>
                                                                <div class="dropdown-menu">
                                                                    <ul>
                                                                        <li class="edit-btn"
                                                                            data-employee-id="<?= $row['employee_id']; ?>"
                                                                            data-employee-code="<?= htmlspecialchars($row['employee_code']); ?>"
                                                                            data-first-name="<?= htmlspecialchars($row['first_name']); ?>"
                                                                            data-last-name="<?= htmlspecialchars($row['last_name']); ?>"
                                                                            data-email="<?= htmlspecialchars($row['email_address']); ?>"
                                                                            data-contact="<?= htmlspecialchars($row['contact_number']); ?>"
                                                                            data-station="<?= htmlspecialchars($row['work_station']); ?>"
                                                                            data-role="<?= htmlspecialchars($row['role']); ?>"
                                                                            data-shift="<?= htmlspecialchars($row['shift']); ?>"
                                                                            data-image="<?= !empty($row['employee_image']) ? '../images/employee-photos/' . htmlspecialchars($row['employee_image']) : 'https://placehold.co/100x100.png'; ?>"
                                                                            data-qr="<?= !empty($row['employee_code']) ? '../images/qr-codes/' . htmlspecialchars($row['employee_code']) . '.png' : 'https://placehold.co/100x100.png'; ?>"
                                                                        >Edit</li>

                                                                        <li class="delete-btn" 
                                                                            data-employee-id="<?= $row['employee_id']; ?>" 
                                                                            data-employee-name="<?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>">
                                                                            Delete
                                                                        </li>

                                                                        <li class="download-pdf" 
                                                                            data-employee-code="<?= htmlspecialchars($row['employee_code']); ?>"
                                                                            data-first-name="<?= htmlspecialchars($row['first_name']); ?>"
                                                                            data-last-name="<?= htmlspecialchars($row['last_name']); ?>">
                                                                            Download PDF
                                                                        </li>

                                                                        <li class="download-qr-btn" 
                                                                            data-qr="<?= !empty($row['employee_code']) ? '../images/qr-codes/' . htmlspecialchars($row['employee_code']) . '.png' : '' ?>"
                                                                            data-first-name="<?= htmlspecialchars($row['first_name']); ?>"
                                                                            data-last-name="<?= htmlspecialchars($row['last_name']); ?>">
                                                                            Download QR Code
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><td colspan="11">No employees found</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>

                        </div>
                            <!-- Pagination -->
                        <div class="table-pagination-container">
                            <div class="total-rows" id="employeeTotalRows">
                                Showing 1-10 of <?= $totalEmployees; ?> employees
                            </div>
                            <div class="table-pagination" id="employeePagination"></div>
                         </div>                                

                            
                </div>

                    <!-- All Employee Modal (Add, Edit, Delete, Image & QRcode Preview) -->
                    <?php include '../includes/employee-modal.php'; ?>

            <!-- All Employee Modal (Add, Edit, Delete, Image & QRcode Preview) -->
            </div>
        </section>

	<script src="../js/filter.js"> </script>	
    <script src="../js/export.js"> </script>                        
    <script src="../js/dropdown.js"> </script>
    <script src="../js/dashboard.js"> </script>
	<script src="../js/employee.js"> </script>
     <script src="../js/attendance.js"></script>
</body>
</html>