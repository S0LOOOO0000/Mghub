<?php
if (session_status() === PHP_SESSION_NONE) session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../config/phpmailer/src/PHPMailer.php';
require __DIR__ . '/../config/phpmailer/src/SMTP.php';
require __DIR__ . '/../config/phpmailer/src/Exception.php';
require __DIR__ . '/../config/database-connection.php';

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Send email notification about request status
 */
function sendRequestStatusEmail($toEmail, $toName, $requestType, $targetDate, $reason, $status) {
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'mgcafe.adm2025@gmail.com';
        $mail->Password = 'ypcf mqee nath emtn'; // ⚠️ use env variable in production
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom('mgcafe2025@gmail.com', 'MG Hub Admin');
        $mail->addAddress($toEmail, $toName);
        $mail->isHTML(true);

        $subject = ($status === "Approved") ? "$requestType Request Approved" : "$requestType Request Declined";
        $formattedDate = date("F j, Y", strtotime($targetDate));

        $mail->Subject = $subject;
        $mail->Body = "
            <div style='font-family:Poppins, sans-serif; font-size:14px;'>
                <h2>Hi " . htmlspecialchars($toName) . ",</h2>
                <p>Your <strong>$requestType</strong> request for <strong>$formattedDate</strong> has been 
                <strong>" . htmlspecialchars($status) . "</strong>.</p>
                <p><strong>Reason:</strong><br>" . nl2br(htmlspecialchars($reason)) . "</p>
                <p>Thank you for your cooperation!</p>
            </div>";
        $mail->send();
    } catch (Exception $e) {
        error_log("Email failed: " . $e->getMessage());
    }
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception("Invalid request method");

    $requestId = intval($_POST['request_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if (!$requestId || !in_array($action, ['approve', 'decline'])) {
        throw new Exception("Invalid request data");
    }

    // Fetch request with requester info
    $stmt = $conn->prepare("
        SELECT r.*, e.first_name, e.last_name, e.email_address, e.shift AS employee_shift
        FROM tbl_request r
        JOIN tbl_employee e ON r.employee_id = e.employee_id
        WHERE r.request_id = ? LIMIT 1
    ");
    $stmt->bind_param("i", $requestId);
    $stmt->execute();
    $request = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$request) throw new Exception("Request not found");

    if ($request['status'] !== 'Pending') {
        echo json_encode(['status' => 'error', 'message' => 'This request has already been processed']);
        exit;
    }

    $newStatus = ($action === 'approve') ? 'Approved' : 'Declined';

    // Start transaction
    $conn->begin_transaction();

    // Update request status
    $stmt = $conn->prepare("UPDATE tbl_request SET status=?, updated_at=NOW() WHERE request_id=?");
    $stmt->bind_param("si", $newStatus, $requestId);
    $stmt->execute();
    $stmt->close();

    // Handle Change Shift swap if approved
    if ($newStatus === 'Approved' && $request['request_type'] === 'Change Shift' && !empty($request['target_employee_id'])) {

        // Get target employee info
        $stmt = $conn->prepare("SELECT shift, first_name, last_name, email_address FROM tbl_employee WHERE employee_id=? LIMIT 1");
        $stmt->bind_param("i", $request['target_employee_id']);
        $stmt->execute();
        $targetEmployee = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($targetEmployee) {
            $targetShift = $targetEmployee['shift'];

            // Swap requester shift
            $stmt = $conn->prepare("UPDATE tbl_employee SET shift=? WHERE employee_id=?");
            $stmt->bind_param("si", $targetShift, $request['employee_id']);
            $stmt->execute();
            $stmt->close();

            // Swap target employee shift
            $stmt = $conn->prepare("UPDATE tbl_employee SET shift=? WHERE employee_id=?");
            $stmt->bind_param("si", $request['employee_shift'], $request['target_employee_id']);
            $stmt->execute();
            $stmt->close();

            // Send email to target employee about the swap
            sendRequestStatusEmail(
                $targetEmployee['email_address'],
                $targetEmployee['first_name'] . ' ' . $targetEmployee['last_name'],
                "Shift Swap",
                $request['target_date'],
                "You have been assigned a shift swap with " . $request['first_name'] . " " . $request['last_name'] . ".",
                "Approved"
            );
        }
    }

    // Commit transaction
    $conn->commit();

    // Send email to requester
    sendRequestStatusEmail(
        $request['email_address'],
        $request['first_name'] . ' ' . $request['last_name'],
        $request['request_type'],
        $request['target_date'],
        $request['reason'],
        $newStatus
    );

    echo json_encode([
        'status' => 'success',
        'message' => "Request $newStatus",
        'newStatus' => $newStatus
    ]);

} catch (Exception $e) {
    if (isset($conn) && $conn instanceof mysqli) $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    if (isset($conn) && $conn instanceof mysqli) $conn->close();
}
