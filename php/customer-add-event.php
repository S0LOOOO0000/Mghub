<?php
require __DIR__ . '/../config/database-connection.php';
header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request");
    }

    $name    = trim($_POST['customer_name'] ?? '');
    $email   = trim($_POST['customer_email'] ?? '');
    $contact = trim($_POST['customer_contact'] ?? '');
    $event   = trim($_POST['event_name'] ?? '');
    $date    = trim($_POST['event_date'] ?? '');
    $time    = trim($_POST['event_time'] ?? '');
    $desc    = trim($_POST['event_description'] ?? '');

    if (!$name || !$email || !$event || !$date || !$time) {
        throw new Exception("Missing required fields.");
    }

    $stmt = $conn->prepare("
        INSERT INTO tbl_event_pending 
        (customer_name, customer_email, customer_contact, event_name, event_date, event_time, event_description, event_status)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')
    ");
    $stmt->bind_param("sssssss", $name, $email, $contact, $event, $date, $time, $desc);

    if ($stmt->execute()) {
        echo json_encode(['status'=>'success','message'=>'Booking submitted! Pending approval.']);
    } else {
        throw new Exception("Database insert failed.");
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
} finally {
    if (isset($conn)) $conn->close();
}
