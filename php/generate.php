<?php
require_once __DIR__ . '/../config/database-connection.php'; // fixed path


// Insert new users
foreach ($newUsers as $user) {
    $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (email, password, user_role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $user['email'], $hashedPassword, $user['role']);

    if ($stmt->execute()) {
        echo "✅ Inserted: {$user['email']} (role: {$user['role']})<br>";
        echo "   ➝ Plain password: {$user['password']}<br>";
    } else {
        echo "❌ Error inserting {$user['email']}: " . $stmt->error . "<br>";
    }

    $stmt->close();
}

// Update old users
foreach ($updateUsers as $user) {
    $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET email = ?, password = ?, user_role = ? WHERE email = ?");
    $stmt->bind_param("ssss", $user['new_email'], $hashedPassword, $user['role'], $user['old_email']);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo "🔄 Updated: {$user['old_email']} → {$user['new_email']} ({$user['role']})<br>";
        echo "   ➝ New plain password: {$user['password']}<br>";
    } else {
        echo "⚠️ No changes for {$user['old_email']} (maybe not found).<br>";
    }

    $stmt->close();
}

$conn->close();
?>
