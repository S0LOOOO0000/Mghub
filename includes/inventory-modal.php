<link rel="stylesheet" href="../css/components/modal-inventory.css">
<link rel="stylesheet" href="../css/components/alerts.css">


<!-- ADD INVENTORY MODAL -->
<div id="addInventoryModal" class="modal-inv">
  <div class="modal-contentss">
    <button class="close-btn">&times;</button>
    <h2>Add Inventory Item</h2>

    <?php
    $success = $_GET['success'] ?? null;
    $error = $_GET['error'] ?? null;
    ?>

    <!-- Success popup -->
    <?php if ($success): ?>
    <div id="success-popup" class="success-popup"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <!-- Error popup -->
    <?php if ($error): ?>
    <div id="error-popup" class="error-popup"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- ✅ Connect to backend -->
    <form id="addInventoryForm" method="POST" action="../php/add-inventory.php">
      <div class="form-group">
        <label for="add_item_name">Item Name</label>
        <input type="text" id="add_item_name" name="item_name" required>
      </div>

      <div class="form-group">
        <label for="add_item_quantity">Quantity</label>
        <input type="number" id="add_item_quantity" name="item_quantity" min="0" required>
      </div>

<div class="form-group">
  <label for="add_item_category">Category</label>
  <select id="add_item_category" name="item_category" required>
    <option value="">Select Category</option>
    <?php foreach ($branchCategories as $cat): ?>
      <option value="<?php echo htmlspecialchars($cat); ?>">
        <?php echo htmlspecialchars($cat); ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>

      <div class="modal-actions">
        <button type="submit" class="btn-submit">Add Item</button>
      </div>
    </form>
  </div>
</div>

<!-- EDIT INVENTORY MODAL -->
<div id="editInventoryModal" class="modal-inv">
  <div class="modal-contentss">
    <button class="close-btn">&times;</button>
    <h2>Edit Inventory Item</h2>

    <!-- ✅ Connect to backend -->
    <form id="editInventoryForm" method="POST" action="../php/edit-inventory.php">
      <!-- hidden ID -->
      <input type="hidden" name="inventory_id" id="edit_inventory_id">

      <div class="form-group">
        <label for="edit_item_name">Item Name</label>
        <input type="text" id="edit_item_name" name="item_name" required>
      </div>

      <div class="form-group">
        <label for="edit_item_quantity">Quantity</label>
        <input type="number" id="edit_item_quantity" name="item_quantity" min="0" required>
      </div>

<div class="form-group">
  <label for="add_item_category">Category</label>
  <select id="add_item_category" name="item_category" required>
    <option value="">Select Category</option>
    <?php foreach ($branchCategories as $cat): ?>
      <option value="<?php echo htmlspecialchars($cat); ?>">
        <?php echo htmlspecialchars($cat); ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>

      <div class="modal-actions">
        <button type="submit" class="btn-submit">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- DELETE INVENTORY MODAL -->
<div id="deleteInventoryModal" class="modal-inv">
  <div class="modal-contentss">
    <button class="close-btn" id="closeDeleteInventoryBtn">&times;</button>
    <h2>Delete Inventory Item</h2>

    <form id="deleteInventoryForm">
      <!-- hidden ID -->
      <input type="hidden" name="inventory_id" id="delete_inventory_id">

      <p id="delete_item_name">Are you sure you want to delete this item?</p>

      <div class="modal-actions">
        <button type="button" class="btn-cancel" id="cancelDeleteInventoryBtn">Cancel</button>
        <button type="button" class="btn-danger" id="confirmDelete">Delete</button>
      </div>
    </form>
  </div>
</div>
