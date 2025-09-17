<?php
include __DIR__ . '/../config/database-connection.php';

function back($key,$msg){
    $base = $_SERVER['HTTP_REFERER'] ?? '../pages/attendance.php';
    $sep  = parse_url($base, PHP_URL_QUERY) ? '&' : '?';
    header("Location: {$base}{$sep}{$key}=".urlencode($msg));
    exit;
}

if($_SERVER['REQUEST_METHOD']!=="POST") back('error','Invalid request');

$employee_id = $_POST['employee_id'] ?? 0;
$target_employee_id = $_POST['target_employee_id'] ?? 0;
$target_date = $_POST['target_shift_date'] ?? '';
$reason = $_POST['reason'] ?? '';

if(!$employee_id || !$target_employee_id || !$target_date || !$reason)
    back('error','Missing required fields');
if($employee_id == $target_employee_id) back('error','Cannot swap with self');

$stmt = $conn->prepare("
    INSERT INTO tbl_request (employee_id, request_type, target_employee_id, reason, request_date, target_date, status, email_sent, created_at, updated_at)
    VALUES (?, 'Change Shift', ?, ?, NOW(), ?, 'Pending', 0, NOW(), NOW())
");
$stmt->bind_param("iiss",$employee_id,$target_employee_id,$reason,$target_date);

if($stmt->execute()) back('success','Change shift request submitted');
else back('error','DB Error: '.$stmt->error);
