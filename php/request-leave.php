<?php
include __DIR__ . '/../config/database-connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    exit;
}

$employee_code = $_POST['employee_id'] ?? null;  // Actually employee_code (e.g. EMP100)
$target_date   = $_POST['target_date'] ?? null;
$leave_type    = $_POST['leave_type'] ?? null;
$reason        = $_POST['reason'] ?? null;

if (!$employee_code || !$target_date || !$leave_type || !$reason) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}

// ✅ Step 1: Convert employee_code → employee_id
$getEmp = $conn->prepare("SELECT employee_id FROM tbl_employee WHERE employee_code=? LIMIT 1");
$getEmp->bind_param("s", $employee_code);
$getEmp->execute();
$res = $getEmp->get_result();
if ($res->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Employee does not exist"]);
    exit;
}
$row = $res->fetch_assoc();
$employee_id = $row['employee_id'];
$getEmp->close();

// ✅ Step 2: Insert into tbl_request
$stmt = $conn->prepare("
    INSERT INTO tbl_request
    (employee_id, request_type, leave_type, reason, request_date, target_date, status, email_sent, created_at, updated_at)
    VALUES (?, 'On Leave', ?, ?, NOW(), ?, 'Pending', 0, NOW(), NOW())
");
$stmt->bind_param("isss", $employee_id, $leave_type, $reason, $target_date);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Leave request submitted"]);
} else {
    echo json_encode(["status" => "error", "message" => "DB error: " . $stmt->error]);
}
