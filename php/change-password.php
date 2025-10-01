<?php
session_start();
require_once __DIR__ . '/../config/database-connection.php';

// Always return JSON
header('Content-Type: application/json');

if (session_id() === '') {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "You must be logged in to change your password."]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId          = $_SESSION['user_id'];
    $currentPassword = trim($_POST['currentPassword'] ?? '');
    $newPassword     = trim($_POST['newPassword'] ?? '');
    $confirmPassword = trim($_POST['confirmPassword'] ?? '');

    // Validate input
    if (strlen($newPassword) < 8) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "New password must be at least 8 characters long."]);
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "New password and confirm password do not match."]);
        exit();
    }

    // Fetch current password hash
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify current password
        if (!password_verify($currentPassword, $user['password'])) {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "Your current password is incorrect."]);
            exit();
        }

        // Update with new hashed password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $updateStmt->bind_param("si", $hashedPassword, $userId);

        if ($updateStmt->execute()) {
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Password changed successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Failed to update password. Please try again."]);
        }

        $updateStmt->close();
    } else {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "User not found."]);
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(405); // Method not allowed
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>