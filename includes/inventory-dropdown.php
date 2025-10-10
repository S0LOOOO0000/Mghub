<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="../css/components/dropdown.css">
<!--
<?php
// Make sure $branchCategories is defined in the parent page before including this file
if (!isset($branchCategories)) {
    $branchCategories = ['All']; // fallback
}
?>



 Category Dropdown 
<div class="custom-dropdown">
  <button type="button" class="dropdown-toggle">
    Category: <i class="material-icons dropdown-icon">expand_more</i>
  </button>
  <ul class="dropdown-menu">
    <li data-value="all">All</li>
    <?php foreach ($branchCategories as $cat): ?>
      <li data-value="<?= strtolower(str_replace(' ', '-', $cat)) ?>">
        <?= htmlspecialchars($cat) ?>
      </li>
    <?php endforeach; ?>
  </ul>
</div>-->

<h3>Filtered By:</h3>

<!-- Stock Status Dropdown -->
<div class="custom-dropdown">
    <button type="button" class="dropdown-toggle">
        Status: <i class="material-icons dropdown-icon">expand_more</i>
    </button>
    <ul class="dropdown-menu">
        <li data-value="all">All</li>
        <li data-value="in-stock">In Stock</li>
        <li data-value="low-stock">Low Stock</li>
        <li data-value="out-of-stock">Out of Stock</li>
        <li data-value="overstock">Overstock</li>
    </ul>
</div>
