<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../config/phpmailer/src/PHPMailer.php';
require __DIR__ . '/../config/phpmailer/src/SMTP.php';
require __DIR__ . '/../config/phpmailer/src/Exception.php';
require __DIR__ . '/../config/database-connection.php';

header('Content-Type: application/json; charset=utf-8');

try{
    if($_SERVER['REQUEST_METHOD']!=='POST') throw new Exception("Invalid request");

    $name = trim($_POST['customer_name']);
    $email = trim($_POST['customer_email']);
    $contact = trim($_POST['customer_contact']);
    $event = trim($_POST['event_name']);
    $date = $_POST['event_date'];
    $time = $_POST['event_time'];
    $desc = trim($_POST['event_description']);

    if(!$name || !$email || !$contact || !$event || !$date || !$time) throw new Exception("All fields are required");

    $today = date("Y-m-d");
    if($date <= $today) throw new Exception("Cannot book past or same-day events");

    $stmt = $conn->prepare("INSERT INTO tbl_event_booking(customer_name,customer_email,customer_contact,event_name,event_date,event_time,event_description,event_status) VALUES(?,?,?,?,?,?,?,?)");
    $status = 'Pending Approval';
    $stmt->bind_param("ssssssss",$name,$email,$contact,$event,$date,$time,$desc,$status);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['status'=>'success','message'=>'Booking submitted! Pending approval.']);

}catch(Exception $e){
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}finally{
    if(isset($conn)) $conn->close();
}
?>
