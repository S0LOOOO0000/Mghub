<?php
include 'database-connection.php';
header('Content-Type: application/json');

$qr_code = $_POST['qr_code'] ?? null;

if (!$qr_code) {
    echo json_encode(['status'=>'error','message'=>'QR code missing']);
    exit;
}

// Find employee by QR code
$stmt = $conn->prepare("SELECT employee_id, first_name, last_name, email_address FROM tbl_employee WHERE employee_code=?");
$stmt->bind_param("s",$qr_code);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['status'=>'error','message'=>'Employee not found']);
    exit;
}
$employee = $result->fetch_assoc();

// Insert a new request (default: Change Shift)
$stmt2 = $conn->prepare("INSERT INTO tbl_request (employee_id, request_type, status, email_sent, created_at, updated_at) VALUES (?,?,?,?,NOW(),NOW())");
$default_type = 'Change Shift';
$email_sent = 0;
$status = 'Pending';
$stmt2->bind_param("issi",$employee['employee_id'],$default_type,$status,$email_sent);
$stmt2->execute();

if($stmt2->affected_rows > 0) {
    echo json_encode(['status'=>'success','message'=>'Request submitted successfully']);
} else {
    echo json_encode(['status'=>'error','message'=>'Failed to submit request']);
}
