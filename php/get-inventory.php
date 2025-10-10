<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../config/database-connection.php';

// --- ADMIN: branch from query string ---
$branch = $_GET['branch'] ?? ($_SESSION['branch'] ?? 'MG Cafe'); // default MG Cafe

$inventory = [];
$totalInventory = 0;

if ($branch !== 'Unknown') {
    $sql = "SELECT inventory_id, item_name, item_quantity, item_category, created_at 
            FROM tbl_inventory 
            WHERE branch = ?
            ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $branch);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $qty = (int)$row['item_quantity'];

            if ($qty <= 0) {
                $row['item_status'] = "Out of Stock";
            } elseif ($qty <= 10) {
                $row['item_status'] = "Low Stock";
            } else {
                $row['item_status'] = "In Stock";
            }

            $row['is_overstock'] = ($qty > 100);

            $inventory[] = $row;
        }
        $stmt->close();
    }

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

// Optionally store in session if needed
$_SESSION['branch'] = $branch;

$conn->close();
?>
