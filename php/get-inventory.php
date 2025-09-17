<?php
// admin-inventory.php

// Include database connection
include __DIR__ . '/../config/database-connection.php';

/** ---------------- FETCH INVENTORY LIST ---------------- **/
$sql = "SELECT inventory_id, item_name, item_quantity, item_category, created_at 
        FROM tbl_inventory 
        ORDER BY created_at ASC";

$result = $conn->query($sql);

$inventory = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        // Auto-determine status based on quantity
        if ($row['item_quantity'] <= 0) {
            $row['item_status'] = "Out of Stock";
        } elseif ($row['item_quantity'] <= 10) {
            $row['item_status'] = "Low Stock";
        } elseif ($row['item_quantity'] > 100) {
            $row['item_status'] = "Overstock";
        } else {
            $row['item_status'] = "In Stock";
        }

        $inventory[] = $row;
    }
}

/** ---------------- FETCH TOTAL INVENTORY COUNT ---------------- **/
$sql = "SELECT COUNT(*) AS total_inventory FROM tbl_inventory";
$result = $conn->query($sql);

$totalInventory = 0; 
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalInventory = $row['total_inventory'];
}

// Close connection
$conn->close();
?>
