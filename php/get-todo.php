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

// Get POST data from JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// Station MUST be sent from JavaScript to support multiple simultaneous sessions
if (!isset($data['station']) || empty($data['station'])) {
    echo json_encode(['success' => false, 'message' => 'Station parameter is required', 'debug' => 'No station in POST data']);
    exit();
}

$station = $data['station'];

try {
    // Fetch todos for the specific station (shared by all users at that station)
    $stmt = $conn->prepare("
        SELECT todo_id, todo_text, progress, is_completed, station, created_at, updated_at
        FROM tbl_todo
        WHERE station = ?
        ORDER BY is_completed ASC, created_at DESC
    ");
    
    $stmt->bind_param("s", $station);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $todos = [];
    while ($row = $result->fetch_assoc()) {
        $todos[] = $row;
    }
    
    echo json_encode([
        'success' => true, 
        'todos' => $todos,
        'debug' => [
            'station' => $station,
            'count' => count($todos)
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error fetching todos: ' . $e->getMessage()]);
}

$conn->close();
?>

