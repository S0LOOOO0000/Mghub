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
    // Verify todo exists (no user ownership check - todos are station-wide)
    $verify = $conn->prepare("SELECT todo_id FROM tbl_todo WHERE todo_id = ?");
    $verify->bind_param("i", $todo_id);
    $verify->execute();
    $result = $verify->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Todo not found']);
        exit();
    }
    
    // Build update query based on provided fields
    $updates = [];
    $params = [];
    $types = "";
    
    if (isset($data['todo_text'])) {
        $updates[] = "todo_text = ?";
        $params[] = trim($data['todo_text']);
        $types .= "s";
    }
    
    if (isset($data['progress'])) {
        $updates[] = "progress = ?";
        $params[] = intval($data['progress']);
        $types .= "i";
    }
    
    if (isset($data['is_completed'])) {
        $is_completed = intval($data['is_completed']);
        $updates[] = "is_completed = ?";
        $params[] = $is_completed;
        $types .= "i";
        
        // If marking as completed, set progress to 100
        if ($is_completed === 1 && !isset($data['progress'])) {
            $updates[] = "progress = ?";
            $params[] = 100;
            $types .= "i";
        }
    }
    
    if (empty($updates)) {
        echo json_encode(['success' => false, 'message' => 'No fields to update']);
        exit();
    }
    
    $sql = "UPDATE tbl_todo SET " . implode(", ", $updates) . " WHERE todo_id = ?";
    $params[] = $todo_id;
    $types .= "i";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Todo updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update todo']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

$conn->close();
?>

