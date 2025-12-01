<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../config/database-connection.php';

$branch = $_GET['branch'] ?? ($_SESSION['branch'] ?? null); // null = all branches

$inventory = [];
$totalInventory = 0;        // Branch-specific inventory
$totalInventoryAll = 0;     // Overall total inventory

// --- Branch-specific inventory
if ($branch) {
    $sql = "SELECT * FROM tbl_inventory WHERE branch = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $branch);
} else {
    $sql = "SELECT * FROM tbl_inventory ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
}

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
    $totalInventory++;
}

$stmt->close();

// --- Overall inventory for all branches
$sqlAll = "SELECT COUNT(*) as total_inventory_all FROM tbl_inventory";
$resultAll = $conn->query($sqlAll);
if ($resultAll) {
    $rowAll = $resultAll->fetch_assoc();
    $totalInventoryAll = (int)$rowAll['total_inventory_all'];
}

$conn->close();

// Store selected branch in session
if ($branch) {
    $_SESSION['branch'] = $branch;
}
?>
