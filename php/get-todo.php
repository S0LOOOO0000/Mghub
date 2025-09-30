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

// Get station from request
$data = json_decode(file_get_contents('php://input'), true);
$station = isset($data['station']) ? $data['station'] : null;

if (!$station) {
    echo json_encode(['success' => false, 'message' => 'Station is required']);
    exit();
}

try {
    // Fetch todos for the logged-in user and specific station
    $stmt = $conn->prepare("
        SELECT todo_id, todo_text, progress, is_completed, station, created_at, updated_at
        FROM tbl_todo
        WHERE user_id = ? AND station = ?
        ORDER BY is_completed ASC, created_at DESC
    ");
    
    $stmt->bind_param("is", $user_id, $station);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $todos = [];
    while ($row = $result->fetch_assoc()) {
        $todos[] = $row;
    }
    
    echo json_encode(['success' => true, 'todos' => $todos]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error fetching todos: ' . $e->getMessage()]);
}

$conn->close();
?>

