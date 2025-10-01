<?php
session_start();
include '../config/database-connection.php';
include 'log-activity.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inventory_id = intval($_POST['inventory_id'] ?? 0);
    $item_name = trim($_POST['item_name'] ?? '');
    $item_quantity = intval($_POST['item_quantity'] ?? 0);
    $item_category = trim($_POST['item_category'] ?? '');
    $branch = $_SESSION['branch'] ?? 'Unknown';

    $user_id = $_SESSION['user_id'] ?? 0;
    $user_role = $_SESSION['user_role'] ?? 'Unknown';

    if ($inventory_id <= 0 || $item_name === '' || $item_quantity < 0 || $item_category === '' || $branch === 'Unknown') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
        exit;
    }

    // Fetch old data
    $stmtFetch = $conn->prepare("SELECT item_name, item_quantity, item_category 
        FROM tbl_inventory WHERE inventory_id=? AND branch=?");
    $stmtFetch->bind_param("is", $inventory_id, $branch);
    $stmtFetch->execute();
    $oldData = $stmtFetch->get_result()->fetch_assoc();
    $stmtFetch->close();

    if (!$oldData) {
        echo json_encode(['status' => 'error', 'message' => 'Item not found for this branch.']);
        exit;
    }

    $newData = [
        'item_name' => $item_name,
        'item_quantity' => $item_quantity,
        'item_category' => $item_category
    ];

    $changes = [];
    foreach ($newData as $key => $value) {
        if ($oldData[$key] != $value) {
            $changes[$key] = ['old' => $oldData[$key], 'new' => $value];
        }
    }

    $stmtUpdate = $conn->prepare("UPDATE tbl_inventory 
        SET item_name=?, item_quantity=?, item_category=? 
        WHERE inventory_id=? AND branch=?");
    $stmtUpdate->bind_param("sisis", $item_name, $item_quantity, $item_category, $inventory_id, $branch);

    if ($stmtUpdate->execute()) {
        if (!empty($changes)) {
            $detailsText = "Item: $item_name; ";
            foreach ($changes as $field => $val) {
                $detailsText .= ucfirst($field) . ": {$val['old']} → {$val['new']}; ";
            }
            $detailsText .= "Branch: $branch;";

            logActivity($conn, $user_id, $user_role, 'Inventory', $inventory_id, 'Edit', $detailsText);
        }
        echo json_encode(['status' => 'success', 'message' => 'Item updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update item.']);
    }

    $stmtUpdate->close();
    $conn->close();
}
?>
