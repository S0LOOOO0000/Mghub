<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../config/database-connection.php';

$branch = $_SESSION['branch'] ?? 'Unknown';
$inventory = [];
$totalInventory = 0;

if ($branch !== 'Unknown') {
    // ✅ Fetch items for this branch
    $sql = "SELECT inventory_id, item_name, item_quantity, item_category, created_at 
            FROM tbl_inventory 
            WHERE branch = ?
            ORDER BY created_at DESC"; // latest first
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $branch);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $qty = (int)$row['item_quantity'];

            // ✅ Safe stock status (matches enum values in DB)
            if ($qty <= 0) {
                $row['item_status'] = "Out of Stock";
            } elseif ($qty <= 10) {
                $row['item_status'] = "Low Stock";
            } else {
                $row['item_status'] = "In Stock";
            }

            // ✅ Optional: extra flag for UI highlighting
            $row['is_overstock'] = ($qty > 100);

            $inventory[] = $row;
        }
        $stmt->close();
    }

    // ✅ Fetch total inventory count
    $sql = "SELECT COUNT(*) AS total_inventory FROM tbl_inventory WHERE branch = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $branch);
        $stmt->execute();
        $stmt->bind_result($totalInventory);
        $stmt->fetch();
        $stmt->close();
    }
}

$conn->close();
?>
