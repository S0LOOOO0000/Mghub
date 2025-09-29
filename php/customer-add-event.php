<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $contact = $_POST['contact'];
    $event   = $_POST['event'];
    $date    = $_POST['date'];
    $time    = $_POST['time'];
    $desc    = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO tbl_event_pending
        (customer_name, customer_email, customer_contact, event_name, event_date, event_time, event_description, event_status)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("sssssss", $name, $email, $contact, $event, $date, $time, $desc);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    $stmt->close();
    $conn->close();
}
?>
