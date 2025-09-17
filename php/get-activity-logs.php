<?php
session_start();
include '../config/database-connection.php';

header('Content-Type: application/json');

$module = $_GET['module'] ?? '';
$limit = intval($_GET['limit'] ?? 50); // optional limit
$offset = intval($_GET['offset'] ?? 0); // optional offset

if ($module !== '') {
    $stmt = $conn->prepare("
        SELECT al.log_id, al.user_id, u.user_role, al.action, al.details, al.log_timestamp
        FROM activity_log al
        JOIN users u ON al.user_id = u.user_id
        WHERE al.module = ?
        ORDER BY al.log_timestamp DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("sii", $module, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Decode JSON details for frontend
    foreach ($result as &$row) {
        $row['details'] = json_decode($row['details'], true);
    }

    echo json_encode(['status' => 'success', 'logs' => $result]);
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'logs' => [], 'message' => 'Module not specified.']);
}

$conn->close();
?>
