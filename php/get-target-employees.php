<?php
include __DIR__ . '/../config/database-connection.php';
header('Content-Type: application/json');

// ✅ Use alphanumeric employee_code
$employee_code = isset($_GET['employee_id']) ? $_GET['employee_id'] : '';
if (empty($employee_code)) {
    echo json_encode([
        "status" => "error",
        "message" => "Missing employee_id"
    ]);
    exit;
}

// Get employee info
$stmt = $conn->prepare("SELECT employee_code, first_name, last_name, shift, work_station, status
                        FROM tbl_employee 
                        WHERE employee_code = ?");
$stmt->bind_param("s", $employee_code);
$stmt->execute();
$res = $stmt->get_result();

if (!$res || $res->num_rows === 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Employee not found"
    ]);
    exit;
}

$employee = $res->fetch_assoc();

echo json_encode([
    "status" => "success",
    "employee" => $employee
]);

$conn->close();
