<?php
header('Content-Type: application/json; charset=utf-8');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../config/phpmailer/src/PHPMailer.php';
require __DIR__ . '/../config/phpmailer/src/SMTP.php';
require __DIR__ . '/../config/phpmailer/src/Exception.php';
require __DIR__ . '/../config/database-connection.php';

// Convert 24-hour time to 12-hour format
function formatTime12($time24) {
    if (!$time24) return '';
    $t = explode(':', $time24);
    $hour = (int)$t[0];
    $minute = $t[1] ?? '00';
    $ampm = $hour >= 12 ? 'PM' : 'AM';
    $hour12 = $hour % 12 ?: 12;
    return sprintf("%d:%02d %s", $hour12, $minute, $ampm);
}

function sendStatusEmail($toEmail, $toName, $eventName, $eventDate, $eventTime, $eventDescription, $status) {
    try {
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'mgcafe.adm2025@gmail.com';
        $mail->Password = 'ypcf mqee nath emtn'; // ideally use env variable
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('mgcafe2025@gmail.com', 'MG Cafe');
        $mail->addAddress($toEmail, $toName);
        $mail->isHTML(true);

        $subject = ($status === "Booked")
            ? "Booking Confirmation: $eventName"
            : "Event Status Update: $eventName";

        $mail->Subject = $subject;
        $formattedDate = date("F j, Y", strtotime($eventDate));

$mail->Body = "
<div style='font-family:Poppins, sans-serif; font-size:14px; color:#333; line-height:1.6; max-width:600px;'>
    <h2 style='margin:0 0 15px 0;'>Hi " . htmlspecialchars($toName) . ",</h2>

    <h3 style='margin:0 0 15px 0;'>
        Your reservation for <strong>" . htmlspecialchars($eventName) . "</strong> has been 
        <strong>updated</strong>. Its current status is 
        <strong>" . htmlspecialchars($status) . "</strong>.
    </h3>

    <p style='margin:0 0 5px 0;'>
        <strong>Date:</strong> " . htmlspecialchars($formattedDate) . "
    </p>
    <p style='margin:0 0 5px 0;'>
        <strong>Time:</strong> " . htmlspecialchars(formatTime12($eventTime)) . "
    </p>
    <p style='margin:0 0 15px 0;'>
        <strong>Location:</strong> Julian St, Brgy San Roque, Cardona, Rizal, Philippines, 1940
    </p>

    <p style='margin:0 0 20px 0;'>
        <strong>Description:</strong><br>" . nl2br(htmlspecialchars($eventDescription)) . "
    </p>

    <p style='margin:0 0 10px 0;'>
        If you have any questions, message us at 
        <a href='mailto:mgcafe2025@gmail.com'>mgcafe2025@gmail.com</a>.
    </p>

    <p style='margin:0;'>Thank you for choosing MG Cafe!</p>
</div>
";




        $mail->send();
    } catch (Exception $e) {
        error_log("Email send failed: " . $e->getMessage());
    }
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception("Invalid request method.");

    $event_id = $_POST['event_id'] ?? null;
    $customer_name = trim($_POST['customer_name'] ?? '');
    $customer_email = trim($_POST['customer_email'] ?? '');
    $customer_contact = trim($_POST['customer_contact'] ?? '');
    $event_name = trim($_POST['event_name'] ?? '');
    $event_date = trim($_POST['event_date'] ?? '');
    $event_time = trim($_POST['event_time'] ?? '');
    $event_description = trim($_POST['event_description'] ?? '');
    $event_status = trim($_POST['event_status'] ?? '');
    $sendEmail = isset($_POST['send_email']) && $_POST['send_email'] == 1;

    if (!$event_id || !$customer_name || !$customer_email || !$event_name || !$event_date || !$event_time || !$event_status) {
        throw new Exception("Required fields are missing.");
    }
    if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address.");
    }

    // Get old status
    $stmt = $conn->prepare("SELECT event_status FROM tbl_event_booking WHERE event_id=?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) throw new Exception("Event not found.");
    $row = $result->fetch_assoc();
    $oldStatus = $row['event_status'];
    $stmt->close();

    // Update event
    $sql = "UPDATE tbl_event_booking 
            SET customer_name=?, customer_email=?, customer_contact=?, event_name=?, event_date=?, event_time=?, event_description=?, event_status=?
            WHERE event_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssi",
        $customer_name,
        $customer_email,
        $customer_contact,
        $event_name,
        $event_date,
        $event_time,
        $event_description,
        $event_status,
        $event_id
    );

    if (!$stmt->execute()) throw new Exception("Database error: " . $stmt->error);
    $stmt->close();

    // Send email if requested and status changed
    if ($sendEmail && $oldStatus !== $event_status && in_array($event_status, ['Booked','Cancelled','Completed'])) {
        sendStatusEmail($customer_email, $customer_name, $event_name, $event_date, $event_time, $event_description, $event_status);
    }

    echo json_encode(['status'=>'success','message'=>'Event updated successfully.']);

} catch (Exception $e) {
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
} finally {
    if (isset($stmt) && $stmt) $stmt->close();
    if (isset($conn) && $conn) $conn->close();
}
