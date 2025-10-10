<?php
require __DIR__ . '/../config/database-connection.php';

$sql = "SELECT * FROM tbl_event_booking WHERE event_status IN ('Booked','Completed')";
$result = $conn->query($sql);

$events = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $events[] = [
            'id'     => $row['event_id'],
            'event_name' => $row['event_name'],
            'event_date' => $row['event_date'],
            'event_time' => $row['event_time'],
            'event_description' => $row['event_description'],
            'event_status' => $row['event_status']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($events);
$conn->close();
