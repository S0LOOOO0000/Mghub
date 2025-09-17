<?php
session_start();
include '../config/database-connection.php';
include 'log-activity.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inventory_id = intval($_POST['inventory_id'] ?? 0);
    $user_id = $_SESSION['user_id'] ?? 0;
    $user_role = $_SESSION['user_role'] ?? 'Unknown';

    if ($inventory_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid inventory ID.']);
        exit;
    }

    // Fetch old data
    $stmtFetch = $conn->prepare("SELECT item_name, item_quantity, item_category FROM tbl_inventory WHERE inventory_id=?");
    $stmtFetch->bind_param("i", $inventory_id);
    $stmtFetch->execute();
    $oldData = $stmtFetch->get_result()->fetch_assoc();
    $stmtFetch->close();

    $stmtDelete = $conn->prepare("DELETE FROM tbl_inventory WHERE inventory_id=?");
    $stmtDelete->bind_param("i", $inventory_id);

    if ($stmtDelete->execute()) {
        // Prepare plain text details for log
        $detailsText = "Item: {$oldData['item_name']}; Quantity: {$oldData['item_quantity']}; Category: {$oldData['item_category']};";

        logActivity($conn, $user_id, $user_role, 'Inventory', $inventory_id, 'Delete', $detailsText);
        echo json_encode(['status' => 'success', 'message' => 'Item deleted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete item.']);
    }

    $stmtDelete->close();
    $conn->close();
}
?>
