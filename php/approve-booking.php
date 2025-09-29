<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../config/phpmailer/src/PHPMailer.php';
require __DIR__ . '/../config/phpmailer/src/SMTP.php';
require __DIR__ . '/../config/phpmailer/src/Exception.php';
require __DIR__ . '/../config/database-connection.php';

header('Content-Type: application/json; charset=utf-8');

function sendStatusEmail($toEmail, $toName, $eventName, $eventDate, $eventTime, $eventDescription, $status) {
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'mgcafe.adm2025@gmail.com';
        $mail->Password = 'ypcf mqee nath emtn'; // ⚠️ use env var in production
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('mgcafe2025@gmail.com', 'MG Cafe');
        $mail->addAddress($toEmail, $toName);
        $mail->isHTML(true);

        $subject = ($status === "Approved")
            ? "Booking Approved: $eventName"
            : "Booking Declined: $eventName";

        $formattedDate = date("F j, Y", strtotime($eventDate));
        $formattedTime = date("h:i A", strtotime($eventTime));

        $mail->Subject = $subject;
        $mail->Body = "
        <div style='font-family:Poppins, sans-serif; font-size:14px;'>
            <h2>Hi " . htmlspecialchars($toName) . ",</h2>
            <p>Your reservation for <strong>" . htmlspecialchars($eventName) . "</strong> has been 
            <strong>" . htmlspecialchars($status) . "</strong>.</p>
            <p><strong>Date:</strong> $formattedDate</p>
            <p><strong>Time:</strong> $formattedTime</p>
            <p><strong>Description:</strong><br>" . nl2br(htmlspecialchars($eventDescription)) . "</p>
            <p>Thank you for choosing MG Cafe!</p>
        </div>";
        $mail->send();
    } catch (Exception $e) {
        error_log("Email failed: " . $e->getMessage());
    }
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request");
    }

    $pendingId = intval($_POST['booking_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if (!$pendingId || !in_array($action, ['approve', 'decline'])) {
        throw new Exception("Invalid data");
    }

    // Fetch from pending
    $stmt = $conn->prepare("SELECT * FROM tbl_event_pending WHERE pending_id=? LIMIT 1");
    $stmt->bind_param("i", $pendingId);
    $stmt->execute();
    $result = $stmt->get_result();
    $request = $result->fetch_assoc();
    $stmt->close();

    if (!$request) {
        throw new Exception("Pending booking not found");
    }

    if ($action === 'approve') {
        if ($request['event_status'] !== 'Pending') {
            throw new Exception("This booking is already processed.");
        }

        // Insert to confirmed
        $stmt = $conn->prepare("
            INSERT INTO tbl_event_booking 
                (customer_name, customer_email, customer_contact, event_name, event_date, event_time, event_description, event_status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'Approved', NOW())
        ");
        $stmt->bind_param(
            "sssssss",
            $request['customer_name'],
            $request['customer_email'],
            $request['customer_contact'],
            $request['event_name'],
            $request['event_date'],
            $request['event_time'],
            $request['event_description']
        );
        $stmt->execute();
        $stmt->close();

        // Remove from pending
        $stmt = $conn->prepare("DELETE FROM tbl_event_pending WHERE pending_id=?");
        $stmt->bind_param("i", $pendingId);
        $stmt->execute();
        $stmt->close();

        // Send email
        sendStatusEmail(
            $request['customer_email'],
            $request['customer_name'],
            $request['event_name'],
            $request['event_date'],
            $request['event_time'],
            $request['event_description'],
            "Approved"
        );

        echo json_encode(['status' => 'success', 'message' => "Booking Approved & moved to confirmed list."]);

    } else {
        if ($request['event_status'] !== 'Pending') {
            throw new Exception("This booking is already processed.");
        }

        $stmt = $conn->prepare("UPDATE tbl_event_pending SET event_status='Declined' WHERE pending_id=?");
        $stmt->bind_param("i", $pendingId);
        $stmt->execute();
        $stmt->close();

        sendStatusEmail(
            $request['customer_email'],
            $request['customer_name'],
            $request['event_name'],
            $request['event_date'],
            $request['event_time'],
            $request['event_description'],
            "Declined"
        );

        echo json_encode(['status' => 'success', 'message' => "Booking Declined."]);
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    if (isset($conn)) $conn->close();
}
