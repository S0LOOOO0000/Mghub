<?php
require_once '../config/database-connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get POST data safely
    $id = mysqli_real_escape_string($conn, $_POST['employee_id']);
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email_address']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $work_station = mysqli_real_escape_string($conn, $_POST['work_station']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $shift = mysqli_real_escape_string($conn, $_POST['shift']);

    // Get current image name from DB
    $query = "SELECT employee_image FROM tbl_employee WHERE employee_id='$id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $current_image = $row['employee_image'] ?? '';

    // Handle new image upload
    if(isset($_FILES['employee_image']) && $_FILES['employee_image']['error'] === UPLOAD_ERR_OK) {
        $img_tmp = $_FILES['employee_image']['tmp_name'];
        $img_name = time().'_'.basename($_FILES['employee_image']['name']);
        $img_folder = '../images/employee-photos/'.$img_name;

        if(move_uploaded_file($img_tmp, $img_folder)) {
            $employee_image = $img_name;

            // Optionally delete old image file
            if($current_image && file_exists('../images/employee-photos/'.$current_image)) {
                unlink('../images/employee-photos/'.$current_image);
            }
        } else {
            $employee_image = $current_image; // fallback
        }
    } else {
        $employee_image = $current_image; // no new image uploaded
    }

    // Prepare SQL statement
    $sql = "UPDATE tbl_employee SET
        first_name='$first_name',
        last_name='$last_name',
        email_address='$email',
        contact_number='$contact',
        work_station='$work_station',
        role='$role',
        shift='$shift',
        employee_image='$employee_image'
        WHERE employee_id='$id'";

    // Execute query
    if(mysqli_query($conn, $sql)) {
        header("Location: ../admin/admin-employees.php?success=Employee updated successfully!");
        exit();
    } else {
        header("Location: ../admin/admin-employees.php?error=".urlencode(mysqli_error($conn)));
        exit();
    }
}
?>
