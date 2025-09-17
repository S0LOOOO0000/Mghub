<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once '../config/database-connection.php';
require_once '../php/log-activity.php';

// ------------------ Fetch Inventory Logs ------------------ //
function fetchLogs($conn, $module) {
    $stmt = $conn->prepare("
        SELECT al.log_id, al.user_id, u.user_role, al.action, al.details, al.log_timestamp
        FROM activity_log al
        JOIN users u ON al.user_id = u.user_id
        WHERE al.module = ?
        ORDER BY al.log_timestamp DESC
    ");
    $stmt->bind_param("s", $module);
    $stmt->execute();
    $logs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    foreach ($logs as &$log) {
        $log['details'] = json_decode($log['details'], true);
    }
    return $logs;
}

// ------------------ Fetch Event Bookings as Logs ------------------ //
function fetchEventLogs($conn) {
    $stmt = $conn->prepare("
        SELECT event_id AS log_id, 
               NULL AS user_id, 
               'staff' AS user_role, 
               'Booking' AS action, 
               JSON_OBJECT(
                   'Customer Name', customer_name,
                   'Email', customer_email,
                   'Contact', customer_contact,
                   'Event Name', event_name,
                   'Description', event_description,
                   'Status', event_status
               ) AS details, 
               created_at AS log_timestamp
        FROM tbl_event_booking
        ORDER BY created_at DESC
    ");
    $stmt->execute();
    $logs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    foreach ($logs as &$log) {
        $log['details'] = json_decode($log['details'], true);
    }
    return $logs;
}

$inventoryLogs = fetchLogs($conn, 'Inventory');
$eventLogs = fetchEventLogs($conn);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Activity Logs</title>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/table.css">
</head>
<body data-role="<?= $_SESSION['user_role'] ?>">

<section class="sidebar">
    <?php include '../includes/admin-sidebar.php'; ?>
</section>

<section class="content">
<nav>
    <i class="material-icons icon-menu">menu</i>
    
</nav>

<div class="main">
    <div class="head-title">
        <div class="left">
            <h1>Activity Logs</h1>
            <ul class="breadcrumb">
                <li><a>System Logs</a></li>
                <li><i class='material-icons right-icon'>chevron_right</i></li>
                <li><a class="active">Home</a></li>
            </ul>
        </div>
    </div>

    <!-- INVENTORY ACTIVITY LOGS -->
    <div class="table-container">
        <div class="table-card">
            <div class="head">
                <h2>Inventory Activity Logs</h2>
                <form action="#">
                    <div class="form-input">
                        <input type="search" class="log-search" placeholder="Search logs...">
                        <button type="submit" class="search-btn"><i class="material-icons search-icon">search</i></button>
                    </div>
                </form>
                <?php include '../includes/activity-log-dropdown.php' ?>
            </div>
            <table id="inventoryLogsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Role</th>
                        <th>Action</th>
                        <th>Details</th>
                        <th>Date</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($inventoryLogs)) : $counter=1; ?>
                        <?php foreach($inventoryLogs as $log) : 
                            $detailsText = '';
                            if(is_array($log['details'])) {
                                if(isset($log['details']['old']) && isset($log['details']['new'])) {
                                    $detailsText = 'Old: ';
                                    foreach($log['details']['old'] as $k=>$v) $detailsText .= "$k=$v; ";
                                    $detailsText .= ' | New: ';
                                    foreach($log['details']['new'] as $k=>$v) $detailsText .= "$k=$v; ";
                                } else {
                                    foreach($log['details'] as $k=>$v) {
                                        $detailsText .= is_array($v) ? "$k:[".implode(',',$v)."]; " : "$k=$v; ";
                                    }
                                }
                            } else {
                                $detailsText = $log['details'];
                            }
                        ?>
                        <tr>
                            <td><?= $counter++; ?></td>
                            <td><?= htmlspecialchars($log['user_role']); ?></td>
                            <td><?= htmlspecialchars($log['action']); ?></td>
                            <td class="reason-text" title="<?= htmlspecialchars($detailsText); ?>"><?= htmlspecialchars($detailsText); ?></td>
                            <td><?= date("F j, Y", strtotime($log['log_timestamp'])); ?></td>
                            <td><?= date("g:i A", strtotime($log['log_timestamp'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6">No logs found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="table-pagination-container">
            <div class="total-rows" id="totalInventoryLogs"></div>
            <div class="table-pagination" id="paginationInventoryLogs"></div>
        </div>
    </div>


</div>
</section>

<script src="../js/dropdown.js"></script>
<script src="../js/dashboard.js"></script>
<script src="../js/inventory.js"></script>
<script src="../js/activity-logs.js"></script>

</body>
</html>
