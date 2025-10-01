<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'staff') {
    header("Location: ../index.php");
    exit;
}

// ✅ Detect which page we are on and set branch
$pageName = basename($_SERVER['PHP_SELF']); 

switch ($pageName) {
    case "client-inventory.php":
        $branch = "MG Cafe";
        break;
    case "spa-inventory.php":
        $branch = "MG Spa";
        break;
    case "mghub-inventory.php":
        $branch = "MG Hub";
        break;
    default:
        $branch = "Unknown";
}
$_SESSION['branch'] = $branch;


include __DIR__ . '/../php/get-inventory.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inventory</title>
<?php include '../includes/favicon.php'; ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="../css/style.css">
</head>
<body class="client-dashboard" data-role="<?= $_SESSION['user_role'] ?>">
<section class="sidebar">
    <?php include '../includes/client-sidebar.php'; ?>
</section>

<section class="content">
<nav>
    <i class="material-icons icon-menu">menu</i>
    <form action="#">
        <div class="form-input">
            <input type="search" placeholder="Search...">
            <button type="submit" class="search-btn"><i class="material-icons search-icon">search</i></button>
        </div>
    </form>
    <?php include '../includes/admin-navbar.php'; ?>
</nav>

<div class="main">
    <div class="head-title">
        <div class="left">
            <h1>Inventory</h1>
            <ul class="breadcrumb">
                <li><a>Inventory Management</a></li>
                <li><i class='material-icons right-icon'>chevron_right</i></li>
                <li><a class="active">Home</a></li>
            </ul>
        </div>
    </div>

    <div class="table-container">
        <div class="table-card">
            <div class="head">
                <h2>Inventory Management</h2>
                <?php include '../includes/inventory-dropdown.php' ?>
                <button type="button" class="btn-add" id="openInventoryModal">
                    <i class="material-icons icon-add">add</i> Add Item
                </button>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ITEM NAME</th>
                        <th>QUANTITY</th>
                        <th>CATEGORY</th>
                        <th>STATUS</th>
                        <th>CREATED</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($inventory)) : $counter=1; ?>
                    <?php foreach ($inventory as $row): ?>
                    <tr>
                        <td><?= $counter++; ?></td>
                        <td><?= htmlspecialchars($row['item_name']); ?></td>
                        <td><?= htmlspecialchars($row['item_quantity']); ?></td>
                        <td><?= htmlspecialchars($row['item_category']); ?></td>
                        <td><span class="status <?= strtolower(str_replace(' ', '-', $row['item_status'])); ?>"><?= htmlspecialchars($row['item_status']); ?></span></td>
                        <td><?= date("F j, Y", strtotime($row['created_at'])); ?></td>
                        <td>
                            <div class="icon-circle" tabindex="0">
                                <div class="dropdown-toggle icon-toggle">
                                    <i class="material-icons icon-more-vert">more_horiz</i>
                                </div>
                                <div class="dropdown-menu">
                                    <ul>
                                        <li class="edit-inv-btn"
                                            data-inventory-id="<?= $row['inventory_id']; ?>"
                                            data-name="<?= htmlspecialchars($row['item_name']); ?>"
                                            data-quantity="<?= htmlspecialchars($row['item_quantity']); ?>"
                                            data-category="<?= htmlspecialchars($row['item_category']); ?>"
                                            data-status="<?= htmlspecialchars($row['item_status']); ?>">Edit</li>
                                        <li class="delete-inv-btn"
                                            data-inventory-id="<?= $row['inventory_id']; ?>"
                                            data-inventory-name="<?= htmlspecialchars($row['item_name']); ?>">Delete</li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7">No inventory items found</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="table-pagination-container">
            <div class="total-rows" id="totalInventoryRows">
                Showing 1-10 of <?= $totalInventory; ?> items
            </div>
            <div class="table-pagination" id="inventoryPagination"></div>
        </div>
    </div>

<?php
// Setup categories depending on branch
switch ($branch) {
    case "MG Cafe":
        $branchCategories = ["Food", "Beverages", "Snacks", "Ingredients"];
        break;
    case "MG Spa":
        $branchCategories = ["Lotions", "Oils", "Scrubs", "Wellness Products"];
        break;
    case "MG Hub":
        $branchCategories = ["Cosmetics", "Haircare", "Skincare", "Accessories"];
        break;
    default:
        $branchCategories = ["General"];
}  ?>                  
    
    <?php include '../includes/inventory-modal.php'; ?>
</div>
</section>

<script src="../js/inventory-filter.js"></script>
<script src="../js/dropdown.js"></script>
<script src="../js/dashboard.js"></script>
<script src="../js/inventory.js"></script>
</body>
</html>
