<?php
include __DIR__ . '/../config/database-connection.php';

// Respond as JSON instead of redirect
header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit;
}

$employee_id = $_POST['employee_id'] ?? 0;
$target_employee_id = $_POST['target_employee_id'] ?? 0;
$target_date = $_POST['target_shift_date'] ?? '';
$reason = $_POST['reason'] ?? '';

if(!$employee_id || !$target_employee_id || !$target_date || !$reason){
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}
if($employee_id == $target_employee_id){
    echo json_encode(["status" => "error", "message" => "Cannot swap with self"]);
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO tbl_request 
    (employee_id, request_type, target_employee_id, reason, request_date, target_date, status, email_sent, created_at, updated_at)
    VALUES (?, 'Change Shift', ?, ?, NOW(), ?, 'Pending', 0, NOW(), NOW())
");
$stmt->bind_param("iiss", $employee_id, $target_employee_id, $reason, $target_date);

try {
   if (!$stmt->execute()) {
      throw new Exception("Failed to insert");
   }
   echo json_encode(["status" => "success", "message" => "Change shift request submitted"]);
} catch (Exception $e) {
   echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}