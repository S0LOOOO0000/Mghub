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
            $_SESSION['user_role']  = $user['user_role']; 

            // Admin login
            if ($user['user_role'] === 'admin') {
                header("Location: ../admin/admin-dashboard.php");
                exit();
            }

            // Staff login - redirect by email (establishment)
            elseif ($user['user_role'] === 'staff') {
                switch ($user['email']) {
                    case 'mgcafe123@gmail.com':
                        header("Location: ../client/client-dashboard.php");
                        break;

                    case 'mghub123@gmail.com':
                        header("Location: ../client-mghub/mghub-dashboard.php");
                        break;

                    case 'mgspa123@gmail.com':
                        header("Location: ../client-spa/spa-dashboard.php");
                        break;

                    default:
                        echo "<script>alert('Unknown staff account.'); window.location.href='../index.php';</script>";
                        exit();
                }
                exit();
            } 
            else {
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
