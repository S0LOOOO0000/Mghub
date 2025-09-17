<?php
session_start();
require_once __DIR__ . '/../config/database-connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Find user by email
    $stmt = $conn->prepare("SELECT user_id, email, password, user_role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id']    = $user['user_id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role']  = $user['user_role']; // ✅ consistent

            if ($user['user_role'] === 'admin') {
                header("Location: ../admin/admin-dashboard.php");
                exit();
            } elseif ($user['user_role'] === 'staff') {
                header("Location: ../client/client-dashboard.php");
                exit();
            } else {
                echo "<script>alert('Invalid user role.'); window.location.href='../index.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Invalid email or password.'); window.location.href='../index.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Invalid email or password.'); window.location.href='../index.php';</script>";
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../index.php");
    exit();
}
