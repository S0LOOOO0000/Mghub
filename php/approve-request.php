<?php
include 'database-connection.php';

$request_id = $_POST['request_id'] ?? null;
$action = $_POST['action'] ?? null;

if(!$request_id || !in_array($action,['approve','decline'])){
    die('Invalid request');
}

$status = $action === 'approve' ? 'Approved' : 'Declined';

// Update request
$stmt = $conn->prepare("UPDATE tbl_request SET status=?, updated_at=NOW() WHERE request_id=?");
$stmt->bind_param("si",$status,$request_id);
$stmt->execute();

header("Location: ../admin/admin-request.php");
