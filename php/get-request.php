<?php
include __DIR__ . '/../config/database-connection.php';

$sql = "
    SELECT 
        r.request_id,
        r.employee_id,
        r.request_type,
        r.target_employee_id,
        r.leave_type,
        r.reason,
        r.request_date,
        r.target_date,
        r.status,
        r.email_sent,
        r.created_at,
        r.updated_at,
        
        e.first_name AS requester_first_name,
        e.last_name AS requester_last_name,
        e.email_address AS requester_email,
        e.work_station AS requester_station,
        e.role AS requester_role,
        e.shift AS requester_shift,
        e.employee_image AS requester_image,
        
        te.first_name AS target_first_name,
        te.last_name AS target_last_name,
        te.shift AS target_shift
    FROM tbl_request r
    INNER JOIN tbl_employee e 
        ON r.employee_id = e.employee_id
    LEFT JOIN tbl_employee te 
        ON r.target_employee_id = te.employee_id
    ORDER BY r.request_date DESC
";

$result = $conn->query($sql);

$requests = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
}

$totalRequests = count($requests);

$conn->close();
