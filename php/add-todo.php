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

if (!isset($data['todo_text']) || empty(trim($data['todo_text']))) {
    echo json_encode(['success' => false, 'message' => 'Todo text is required']);
    exit();
}

$todo_text = trim($data['todo_text']);
$progress = isset($data['progress']) ? intval($data['progress']) : 0;
$station = isset($data['station']) ? $data['station'] : null;

if (!$station) {
    echo json_encode(['success' => false, 'message' => 'Station is required']);
    exit();
}

try {
    $stmt = $conn->prepare("
        INSERT INTO tbl_todo (user_id, station, todo_text, progress, is_completed)
        VALUES (?, ?, ?, ?, 0)
    ");
    
    $stmt->bind_param("issi", $user_id, $station, $todo_text, $progress);
    
    if ($stmt->execute()) {
        $todo_id = $conn->insert_id;
        echo json_encode([
            'success' => true,
            'message' => 'Todo added successfully',
            'todo' => [
                'todo_id' => $todo_id,
                'station' => $station,
                'todo_text' => $todo_text,
                'progress' => $progress,
                'is_completed' => 0
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add todo']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

$conn->close();
?>

