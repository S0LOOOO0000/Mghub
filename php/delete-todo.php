<?php
include __DIR__ . '/../config/database-connection.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['todo_id'])) {
    echo json_encode(['success' => false, 'message' => 'Todo ID is required']);
    exit();
}

$todo_id = intval($data['todo_id']);

try {
    // Verify todo belongs to user before deleting
    $verify = $conn->prepare("SELECT user_id FROM tbl_todo WHERE todo_id = ?");
    $verify->bind_param("i", $todo_id);
    $verify->execute();
    $result = $verify->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Todo not found']);
        exit();
    }
    
    $row = $result->fetch_assoc();
    if ($row['user_id'] !== $user_id) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit();
    }
    
    // Delete the todo
    $stmt = $conn->prepare("DELETE FROM tbl_todo WHERE todo_id = ?");
    $stmt->bind_param("i", $todo_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Todo deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete todo']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

$conn->close();
?>
