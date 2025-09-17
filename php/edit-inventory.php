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

    $user_id = $_SESSION['user_id'] ?? 0;
    $user_role = $_SESSION['user_role'] ?? 'Unknown';

    if ($inventory_id <= 0 || $item_name === '' || $item_quantity < 0 || $item_category === '') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
        exit;
    }

    // Fetch old data
    $stmtFetch = $conn->prepare("SELECT item_name, item_quantity, item_category FROM tbl_inventory WHERE inventory_id=?");
    $stmtFetch->bind_param("i", $inventory_id);
    $stmtFetch->execute();
    $oldData = $stmtFetch->get_result()->fetch_assoc();
    $stmtFetch->close();

    // Prepare new data
    $newData = [
        'item_name' => $item_name,
        'item_quantity' => $item_quantity,
        'item_category' => $item_category
    ];

    // Determine what actually changed
    $changes = [];
    foreach ($newData as $key => $value) {
        if ($oldData[$key] != $value) {
            $changes[$key] = [
                'old' => $oldData[$key],
                'new' => $value
            ];
        }
    }

    // Update the inventory
    $stmtUpdate = $conn->prepare("UPDATE tbl_inventory SET item_name=?, item_quantity=?, item_category=? WHERE inventory_id=?");
    $stmtUpdate->bind_param("sisi", $item_name, $item_quantity, $item_category, $inventory_id);

    if ($stmtUpdate->execute()) {
        if (!empty($changes)) {
            // Always include item name for identification
            $itemDisplayName = $newData['item_name'];
            $detailsText = "Item: $itemDisplayName; ";

            foreach ($changes as $field => $val) {
                if ($field === 'item_name') {
                    // Show old → new if name changed
                    $detailsText .= "Name: {$val['old']} → {$val['new']}; ";
                } elseif ($field === 'item_quantity') {
                    $detailsText .= "Quantity: {$val['old']} → {$val['new']}; ";
                } elseif ($field === 'item_category') {
                    $detailsText .= "Category: {$val['old']} → {$val['new']}; ";
                }
            }

            // Log activity with only changed fields + item name
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
