<?php
// =========================
// request-leave.php
// =========================
include __DIR__ . '/../config/database-connection.php';

function back_with($key, $msg) {
    $base = $_SERVER['HTTP_REFERER'] ?? '../pages/attendance.php';
    $sep  = (parse_url($base, PHP_URL_QUERY) ? '&' : '?');
    header("Location: {$base}{$sep}{$key}=" . urlencode($msg));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    back_with('error', 'Invalid request method');
}

$employee_id = $_POST['employee_id'] ?? null;
$target_date = $_POST['target_date'] ?? null;
$leave_type  = $_POST['leave_type'] ?? null;
$reason      = $_POST['reason'] ?? null;

// Basic validation
if (!$employee_id || !$target_date || !$leave_type || !$reason) {
    back_with('error', 'Missing required fields');
}

// Check employee exists
$chk = $conn->prepare("SELECT 1 FROM tbl_employee WHERE employee_id=?");
$chk->bind_param("i", $employee_id);
$chk->execute();
$chk->store_result();
if ($chk->num_rows === 0) {
    back_with('error', 'Employee does not exist');
}
$chk->close();

// Insert
$stmt = $conn->prepare("
    INSERT INTO tbl_request
    (employee_id, request_type, leave_type, reason, request_date, target_date, status, created_at, updated_at)
    VALUES (?, 'On Leave', ?, ?, NOW(), ?, 'Pending', NOW(), NOW())
");
$stmt->bind_param("isss", $employee_id, $leave_type, $reason, $target_date);

if ($stmt->execute()) {
    back_with('success', 'Leave request submitted');
} else {
    back_with('error', 'DB error: ' . $stmt->error);
}
