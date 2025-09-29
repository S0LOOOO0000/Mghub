<?php
include 'db_connect.php';

$sql = "SELECT * FROM tbl_event_booking WHERE event_status IN ('Booked','Completed')";
$result = $conn->query($sql);

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = [
        'id'    => $row['booking_id'],
        'title' => $row['event_name'],
        'start' => $row['event_date'] . 'T' . $row['event_time'],
        'desc'  => $row['event_description'],
        'status'=> $row['event_status']
    ];
}

header('Content-Type: application/json');
echo json_encode($events);

$conn->close();
?>
