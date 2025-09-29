<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../config/phpmailer/src/PHPMailer.php';
require __DIR__ . '/../config/phpmailer/src/SMTP.php';
require __DIR__ . '/../config/phpmailer/src/Exception.php';
require __DIR__ . '/../config/database-connection.php';

header('Content-Type: application/json; charset=utf-8');

function sendStatusEmail($toEmail,$toName,$eventName,$eventDate,$eventTime,$eventDescription,$status){
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'mgcafe.adm2025@gmail.com';
        $mail->Password = 'ypcf mqee nath emtn'; // Use env variable in production
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('mgcafe2025@gmail.com','MG Cafe');
        $mail->addAddress($toEmail,$toName);
        $mail->isHTML(true);

        $subject = ($status==="Booked") ? "Booking Confirmed: $eventName" : "Booking Status Updated: $eventName";
        $formattedDate = date("F j, Y", strtotime($eventDate));
        $formattedTime = date("h:i A", strtotime($eventTime));

        $mail->Subject = $subject;
        $mail->Body = "
        <div style='font-family:Poppins, sans-serif; font-size:14px;'>
            <h2>Hi " . htmlspecialchars($toName) . ",</h2>
            <p>Your reservation for <strong>" . htmlspecialchars($eventName) . "</strong> is <strong>" . htmlspecialchars($status) . "</strong>.</p>
            <p><strong>Date:</strong> $formattedDate</p>
            <p><strong>Time:</strong> $formattedTime</p>
            <p><strong>Description:</strong><br>" . nl2br(htmlspecialchars($eventDescription)) . "</p>
            <p>Thank you for choosing MG Cafe!</p>
        </div>";
        $mail->send();
    } catch(Exception $e){
        error_log("Email failed: ".$e->getMessage());
    }
}

try{
    if($_SERVER['REQUEST_METHOD']!=='POST') throw new Exception("Invalid request");

    $bookingId = intval($_POST['booking_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    if(!$bookingId || !in_array($action,['approve','decline'])) throw new Exception("Invalid data");

    $stmt = $conn->prepare("SELECT * FROM tbl_event_booking WHERE booking_id=?");
    $stmt->bind_param("i",$bookingId);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
    $stmt->close();
    if(!$booking) throw new Exception("Booking not found");

    $newStatus = ($action==='approve') ? 'Booked' : 'Declined';
    $stmt = $conn->prepare("UPDATE tbl_event_booking SET event_status=? WHERE booking_id=?");
    $stmt->bind_param("si",$newStatus,$bookingId);
    $stmt->execute();
    $stmt->close();

    if($action==='approve'){
        sendStatusEmail(
            $booking['customer_email'],
            $booking['customer_name'],
            $booking['event_name'],
            $booking['event_date'],
            $booking['event_time'],
            $booking['event_description'],
            $newStatus
        );
    }

    echo json_encode(['status'=>'success','message'=>"Booking $newStatus."]);

}catch(Exception $e){
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}finally{
    if(isset($conn)) $conn->close();
}
?>
