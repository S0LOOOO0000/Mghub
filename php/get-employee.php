<?php
// Include database connection
include __DIR__ . '/../config/database-connection.php';

// Fetch all employees
$sql = "SELECT employee_id, employee_code, first_name, last_name, email_address, 
        contact_number, employee_image, work_station, role, shift, status, created_at, qr_code 
        FROM tbl_employee 
        ORDER BY work_station ASC, shift ASC, created_at ASC";

$result = $conn->query($sql);
$employees = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employeeId = $row['employee_id'];

        // 1️⃣ Update status: New → Active if any attendance exists
        $stmt = $conn->prepare("
            SELECT COUNT(*) AS time_in_count 
            FROM tbl_attendance 
            WHERE employee_id = ? AND time_in IS NOT NULL
        ");
        $stmt->bind_param("i", $employeeId);
        $stmt->execute();
        $timeInCount = $stmt->get_result()->fetch_assoc()['time_in_count'];
        $stmt->close();

        if ($row['status'] === 'New' && $timeInCount > 0) {
            $row['status'] = 'Active';
            $stmtUpdate = $conn->prepare("UPDATE tbl_employee SET status = 'Active' WHERE employee_id = ?");
            $stmtUpdate->bind_param("i", $employeeId);
            $stmtUpdate->execute();
            $stmtUpdate->close();
        }

        // 2️⃣ Update status: Active → Inactive if absent for 3 consecutive days
        $stmt = $conn->prepare("
            SELECT COUNT(*) AS absent_count 
            FROM tbl_attendance 
            WHERE employee_id = ? 
              AND attendance_status = 'Absent' 
              AND attendance_date >= DATE_SUB(CURDATE(), INTERVAL 3 DAY)
        ");
        $stmt->bind_param("i", $employeeId);
        $stmt->execute();
        $absentCount = $stmt->get_result()->fetch_assoc()['absent_count'];
        $stmt->close();

        if ($absentCount >= 3) {
            $row['status'] = 'Inactive';
            $stmtUpdate = $conn->prepare("UPDATE tbl_employee SET status = 'Inactive' WHERE employee_id = ?");
            $stmtUpdate->bind_param("i", $employeeId);
            $stmtUpdate->execute();
            $stmtUpdate->close();
        }

        $employees[] = $row;
    }
}

// Fetch total employees for dashboard
$totalEmployees = $conn->query("SELECT COUNT(*) AS total_employees FROM tbl_employee")->fetch_assoc()['total_employees'];

$conn->close();
