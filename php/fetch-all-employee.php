<?php
include __DIR__ . '/../config/database-connection.php';

header('Content-Type: application/json');

try {
    $sql = "SELECT employee_id, employee_code, first_name, last_name, email_address, 
                   contact_number, employee_image, work_station, role, shift, status, created_at, qr_code 
            FROM tbl_employee 
            ORDER BY work_station ASC, shift ASC, created_at ASC";

    $result = $conn->query($sql);

    if (!$result) {
        echo json_encode([
            "status" => "error",
            "message" => "Query failed: " . $conn->error
        ]);
        exit;
    }

    $employees = [];
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }

    echo json_encode([
        "status" => "success",
        "employees" => $employees
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}