<?php
include __DIR__ . '/../config/database-connection.php';

if($_SERVER['REQUEST_METHOD']==="POST"){
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE tbl_request SET status=?, updated_at=NOW() WHERE request_id=?");
    $stmt->bind_param("si",$status,$request_id);
    echo $stmt->execute() ? "success" : "error";
    exit;
}
