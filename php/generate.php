<?php
require_once __DIR__ . '/../config/database-connection.php'; // fixed path



foreach ($users as $user) {
    $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (email, password, user_role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $user['email'], $hashedPassword, $user['role']);

    if ($stmt->execute()) {
        echo "Inserted: {$user['email']} as {$user['role']} ✅<br>";
    } else {
        echo "Error inserting {$user['email']}: " . $stmt->error . "<br>";
    }

    $stmt->close();
}

$conn->close();
?>
