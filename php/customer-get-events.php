<?php
require __DIR__ . '/../config/database-connection.php';
$events=[];

$sql = "SELECT * FROM tbl_event_booking WHERE event_status IN ('Booked','Completed')";
$result = $conn->query($sql);
if($result){
    while($row = $result->fetch_assoc()){
        $events[]=$row;
    }
}
$conn->close();

header('Content-Type: application/json');
echo json_encode($events);
?>
