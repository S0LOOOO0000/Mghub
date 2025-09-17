<?php
include __DIR__ . '/../php/get-employee.php';
include __DIR__ . '/../php/get-request.php'; // this should fetch $requests
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees Requests</title>
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
                    <input type="search" id="requestSearch" placeholder="Search requests...">
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
                    <h1>Attendance</h1>
                    <ul class="breadcrumb">
                        <li><a>Attendance Management</a></li>
                        <li><i class='material-icons right-icon'>chevron_right</i></li>
                        <li><a class="active">Home</a></li>
                    </ul>
                </div>
            </div>

            <!-- Table -->
            <div class="table-container">
                <div class="table-card">
                    <div class="head">
                        <h2>Schedule Requests</h2>
                        <?php include '../includes/request-dropdown.php'; ?>
                    </div>

                    <table id="requestTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>EMPLOYEE</th>
                                <th>STATION & ROLE</th>
                                <th>SHIFT</th>
                                <th>REQUEST TYPE</th>
                                <th>DETAIL</th>
                                <th>REASON</th>
                                <th>TARGET DATE</th>
                                <th>STATUS</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($requests)) : ?>
                                <?php $counter = 1; ?>
                                <?php foreach ($requests as $row): ?>
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
                                        <td><?= date("F j, Y", strtotime($row['target_date'])); ?></td>
                                        <td class="req-status">
                                            <span class="status <?= strtolower($row['status']); ?>">
                                                <?= htmlspecialchars($row['status']); ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php if($row['status'] == 'Pending') : ?>
                                                <div class="request-actions">
                                                    <div class="action-btn approve" data-id="<?= $row['request_id']; ?>" title="Approve">
                                                        <i class="material-icons">check</i>
                                                    </div>
                                                    <div class="action-btn decline" data-id="<?= $row['request_id']; ?>" title="Decline">
                                                        <i class="material-icons">close</i>
                                                    </div>
                                                    <div class="action-btn preview" 
                                                        data-id="<?= $row['request_id']; ?>" 
                                                        data-reason="<?= htmlspecialchars($row['reason']); ?>" 
                                                        data-requester="<?= htmlspecialchars($row['requester_first_name'] . " " . $row['requester_last_name']); ?>"
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
                                <tr><td colspan="10">No requests found</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="table-pagination-container">
                    <div class="total-rows" id="requestTotalRows"></div>
                    <div class="table-pagination" id="requestPagination"></div>
                </div>
            </div>
        </div>
    </section>

    <?php include '../includes/request-preview-modal.php'; ?>

    <!-- Scripts -->
    <script src="../js/dropdown.js"></script>
    <script src="../js/attendance.js"></script>
    <script src="../js/request.js"></script>
    <script src="../js/dashboard.js"></script>
    <script src="../js/employee.js"></script>
    <script src="../js/filter.js"></script>

    <script>
    // ===== Approve / Decline via AJAX =====
    document.querySelectorAll('.request-actions .approve').forEach(btn => {
        btn.addEventListener('click', () => {
            const requestId = btn.dataset.id;
            if(confirm('Approve this request?')) {
                fetch('../php/approve-request.php', {
                    method: 'POST',
                    headers: {'Content-Type':'application/x-www-form-urlencoded'},
                    body: 'request_id=' + requestId + '&action=approve'
                }).then(() => location.reload());
            }
        });
    });

    document.querySelectorAll('.request-actions .decline').forEach(btn => {
        btn.addEventListener('click', () => {
            const requestId = btn.dataset.id;
            if(confirm('Decline this request?')) {
                fetch('../php/approve-request.php', {
                    method: 'POST',
                    headers: {'Content-Type':'application/x-www-form-urlencoded'},
                    body: 'request_id=' + requestId + '&action=decline'
                }).then(() => location.reload());
            }
        });
    });
    </script>
</body>
</html>
