<?php
require_once '../config/database-connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employee_id'])) {
    $employee_id = intval($_POST['employee_id']);

    // Fetch employee image and QR code
    $stmt_fetch = $conn->prepare("SELECT employee_image, employee_code FROM tbl_employee WHERE employee_id = ?");
    $stmt_fetch->bind_param("i", $employee_id);
    $stmt_fetch->execute();
    $employee = $stmt_fetch->get_result()->fetch_assoc();
    $stmt_fetch->close();

    if ($employee) {
        $photoPath = !empty($employee['employee_image']) ? "../images/employee-photos/" . $employee['employee_image'] : null;
        $qrPath = !empty($employee['employee_code']) ? "../images/qr-codes/" . $employee['employee_code'] . ".png" : null;

        // Delete employee (ON DELETE CASCADE removes attendance + requests)
        $stmt_delete = $conn->prepare("DELETE FROM tbl_employee WHERE employee_id = ?");
        $stmt_delete->bind_param("i", $employee_id);

        if ($stmt_delete->execute()) {
            // Delete employee files
            if ($photoPath && file_exists($photoPath)) unlink($photoPath);
            if ($qrPath && file_exists($qrPath)) unlink($qrPath);

            echo "success";
        } else {
            echo "error: " . $stmt_delete->error;
        }

        $stmt_delete->close();
    } else {
        echo "error: employee not found";
    }

    $conn->close();
} else {
    echo "error: invalid request";
}
