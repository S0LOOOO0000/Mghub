<?php
require_once '../config/database-connection.php';
require_once '../config/phpqrcode/qrlib.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email_address = mysqli_real_escape_string($conn, $_POST['email_address']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $work_station = mysqli_real_escape_string($conn, $_POST['work_station']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $shift = mysqli_real_escape_string($conn, $_POST['shift']);

    /*** -------------------- VALIDATIONS -------------------- ***/

    // First name: letters and spaces allowed
    if (!preg_match("/^[a-zA-Z\s]+$/", $first_name)) {
        header("Location: ../admin/admin-employees.php?error=First name must contain letters and spaces only");
        exit();
    }

    // Last name: letters and spaces allowed
    if (!preg_match("/^[a-zA-Z\s]+$/", $last_name)) {
        header("Location: ../admin/admin-employees.php?error=Last name must contain letters and spaces only");
        exit();
    }

    // Contact number: numbers only
    if (!preg_match("/^[0-9]+$/", $contact_number)) {
        header("Location: ../admin/admin-employees.php?error=Contact number must be numeric only");
        exit();
    }

    // Check duplicates
    $check = "SELECT * FROM tbl_employee WHERE email_address='$email_address' OR contact_number='$contact_number'";
    $check_result = mysqli_query($conn, $check);

    if (mysqli_num_rows($check_result) > 0) {
        header("Location: ../admin/admin-employees.php?error=Email or Contact already exists");
        exit();
    }

    // Generate employee_code
    $result = mysqli_query($conn, "SELECT employee_code FROM tbl_employee ORDER BY employee_id DESC LIMIT 1");
    $employee_code = ($row = mysqli_fetch_assoc($result)) 
        ? 'EMP' . str_pad(intval(substr($row['employee_code'], 3)) + 1, 3, '0', STR_PAD_LEFT)
        : 'EMP001';

    // Generate QR code
    $qr_folder = '../images/qr-codes/';
    if (!is_dir($qr_folder)) mkdir($qr_folder, 0777, true);
    $qr_file = $qr_folder . $employee_code . '.png';
    QRcode::png($employee_code, $qr_file, QR_ECLEVEL_L, 5);

    // Upload employee image
    $employee_image = null;
    if (isset($_FILES['employee_image']) && $_FILES['employee_image']['error'] === UPLOAD_ERR_OK) {
        $img_tmp = $_FILES['employee_image']['tmp_name'];
        $img_name = time() . '_' . $_FILES['employee_image']['name'];
        $img_folder = '../images/employee-photos/' . $img_name;
        if (move_uploaded_file($img_tmp, $img_folder)) $employee_image = $img_name;
    }

    // Insert into database
    $created_at = date('Y-m-d H:i:s');
    $updated_at = date('Y-m-d H:i:s');

    $sql = "INSERT INTO tbl_employee 
        (employee_code, qr_code, first_name, last_name, email_address, contact_number, employee_image, work_station, role, shift, status, created_at, updated_at)
        VALUES 
        ('$employee_code', '$employee_code', '$first_name', '$last_name', '$email_address', '$contact_number', '$employee_image', '$work_station', '$role', '$shift', 'New', '$created_at', '$updated_at')";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../admin/admin-employees.php?success=Employee added successfully!");
        exit();
    } else {
        header("Location: ../admin/admin-employees.php?error=Error adding employee: " . urlencode(mysqli_error($conn)));
        exit();
    }
}
?>
