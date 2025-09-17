<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database-connection.php';

$month = $_GET['month'] ?? null;

$sql = "SELECT event_id, customer_name, customer_email, customer_contact, 
               event_name, event_date, event_time, event_description, 
               event_status, created_at
        FROM tbl_event_booking";

$params = [];
if ($month) {
    $sql .= " WHERE DATE_FORMAT(event_date, '%Y-%m') = ?";
    $params[] = $month;
}

$sql .= " ORDER BY event_date, event_time";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "SQL prepare failed: " . $conn->error]);
    exit;
}

if ($params) {
    $stmt->bind_param("s", $params[0]);
}

if (!$stmt->execute()) {
    echo json_encode(["error" => "SQL execute failed: " . $stmt->error]);
    exit;
}

$result = $stmt->get_result();
$events = [];

while ($row = $result->fetch_assoc()) {
    switch ($row['event_status']) {
        case 'Booked': $row['status_info'] = 'Event is reserved and upcoming.'; break;
        case 'Cancelled': $row['status_info'] = 'Event was cancelled by customer/staff.'; break;
        case 'Completed': $row['status_info'] = 'Event has already taken place successfully.'; break;
        default: $row['status_info'] = 'Unknown status'; break;
    }
    $events[] = $row;
}


echo json_encode($events, JSON_PRETTY_PRINT);
