<?php
session_start();
include '../config/database-connection.php';
include 'log-activity.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = trim($_POST['item_name'] ?? '');
    $item_quantity = intval($_POST['item_quantity'] ?? 0);
    $item_category = trim($_POST['item_category'] ?? '');
    $branch = $_SESSION['branch'] ?? 'Unknown';

    $user_id = $_SESSION['user_id'] ?? 0;
    $user_role = $_SESSION['user_role'] ?? 'Unknown';

    if ($item_name === '' || $item_quantity < 0 || $item_category === '' || $branch === 'Unknown') {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields correctly.']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO tbl_inventory 
        (item_name, item_quantity, item_category, branch, item_status, created_at) 
        VALUES (?, ?, ?, ?, 'In Stock', NOW())");
    $stmt->bind_param("siss", $item_name, $item_quantity, $item_category, $branch);

    if ($stmt->execute()) {
        $inventory_id = $stmt->insert_id;

        $detailsText = "Item: $item_name; Quantity: $item_quantity; Category: $item_category; Branch: $branch;";
        logActivity($conn, $user_id, $user_role, 'Inventory', $inventory_id, 'Add', $detailsText);

        echo json_encode(['status' => 'success', 'message' => 'Item added successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add item.']);
    }

    $stmt->close();
    $conn->close();
}
?>
