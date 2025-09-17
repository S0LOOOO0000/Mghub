<?php
include __DIR__ . '/../config/database-connection.php';

// Fetch all events
$sql = "SELECT * FROM tbl_event_booking ORDER BY event_date, event_time";
$result = $conn->query($sql);

$events = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

// Fetch total reservations
$sqlTotalReservations = "SELECT COUNT(*) AS total_reservations FROM tbl_event_booking";
$resultTotal = $conn->query($sqlTotalReservations);

$totalReservations = 0;
if ($resultTotal && $resultTotal->num_rows > 0) {
    $rowTotal = $resultTotal->fetch_assoc();
    $totalReservations = $rowTotal['total_reservations'];
}

// DO NOT CLOSE $conn HERE
?>
