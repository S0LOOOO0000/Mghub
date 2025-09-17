<?php
include __DIR__ . '/../config/database-connection.php';
header('Content-Type: application/json');

$requester_id = isset($_GET['employee_id']) ? intval($_GET['employee_id']) : 0;
if ($requester_id <= 0) { echo json_encode([]); exit; }

// Get requester's work station
$stmt = $conn->prepare("SELECT work_station FROM tbl_employee WHERE employee_id = ?");
$stmt->bind_param("i", $requester_id);
$stmt->execute();
$res = $stmt->get_result();
if (!$res || $res->num_rows === 0) { echo json_encode([]); exit; }
$station = $res->fetch_assoc()['work_station'];

// Fetch other employees in same station, Active only
$stmt = $conn->prepare("
    SELECT employee_id, first_name, last_name, shift
    FROM tbl_employee
    WHERE employee_id != ? AND work_station = ? AND status = 'Active'
    ORDER BY FIELD(shift,'Morning','Mid','Night'), first_name, last_name
");
$stmt->bind_param("is", $requester_id, $station);
$stmt->execute();
$result = $stmt->get_result();

$employees = [];
while($row = $result->fetch_assoc()) { $employees[] = $row; }
echo json_encode($employees);
$conn->close();
