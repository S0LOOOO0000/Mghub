<link rel="stylesheet" href="../css/components/form.css">
<link rel="stylesheet" href="../css/style.css">


<!-- Employee Add Modal -->
<div id="employeeModal" class="modal">
  <div class="modal-content">
        <div class="head">
            <h3>Add New Employee</h3>
            <span class="close-btn" id="closeModal">&times;</span>
        </div>

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


        <form class="form-container" method="POST" action="../php/add-employee.php" enctype="multipart/form-data">

        <!-- Group 1: Identity -->
        <div class="form-group">
            <label>Identity Information</label>
            <div style="display: flex; gap: 10px;">
            <input type="text" id="first_name" name="first_name" placeholder="First Name" required>
            <input type="text" id="last_name" name="last_name" placeholder="Last Name" required>
            </div>
        </div>
        
        <!-- Group 2: Contact -->
        <div class="form-group">
            <label>Contact Information</label>
            <div style="display: flex; gap: 10px;">
            <input type="email" id="email_address" name="email_address" placeholder="Email Address" required>
            <input type="text" id="contact_number" name="contact_number" placeholder="Contact Number" required>
            </div>
        </div>

        <!-- Group 3: Job Info -->
        <div class="form-group">
            <label>Job Information</label>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <select id="work_station" name="work_station" required>
                <option value="">-- Select Work Station --</option>
                <option value="Cafe">Cafe</option>
                <option value="Spa">Spa</option>
                <option value="Beauty Lounge">Beauty Lounge</option>
            </select>

            <select id="role" name="role" required>
                <option value="">-- Select Role --</option>
            </select>

            <select id="shift" name="shift" required>
                <option value="">-- Select Shift --</option>
                <option value="Morning">Morning</option>
                <option value="Mid">Mid</option>
                <option value="Night">Night</option>
                <option value="Fixed">Fixed</option>
            </select>
            </div>
        </div>

        <!-- Group 4: Employee Image -->
        <div class="form-group">
            <label>Employee Image</label>
            <input type="file" id="employee_image" name="employee_image" accept="image/*">
        </div>

        <button type="submit" class="btn-submit">Save Employee</button>
        </form>
  </div>
</div>




<!-- Employee Edit Modal -->
<div id="editEmployeeModal" class="modal">
  <div class="modal-content">
    <div class="head">
      <h3>Edit Employee</h3>
      <span class="close-btn" id="closeEditModal">&times;</span>
    </div>

    <form class="form-container" method="POST" action="../php/edit-employee.php" enctype="multipart/form-data">

      <!-- Top Section: Image Left / QR Right -->
      <div class="form-group top-section" style="display:flex; justify-content:space-between; gap:20px; margin-bottom:20px;">
        <!-- Left: Employee Image + Employee Code below -->
        <div class="image-section" style="text-align:center; flex:1;">
          <img id="edit_employee_image_preview" class="employee-image" style="border-radius:50%; width:120px; height:120px; display:block; margin-bottom:5px;">
          <p class="employee-code" style="margin:0;"><strong>Employee Code:</strong> <span id="edit_employee_code_text"></span></p>
        </div>

        <!-- Right: QR Code -->
        <div class="qr-section" style="flex:1; text-align:left;">
          <img id="edit_employee_qrcode" class="qr-code" src="https://placehold.co/100x100.png" style="width:100px; height:100px;">
        </div>
      </div>

      <!-- Hidden Employee ID -->
      <input type="hidden" id="edit_employee_id" name="employee_id">

      <!-- Identity Info -->
      <div class="form-group">
        <label>Identity Information</label>
        <div style="display: flex; gap: 10px;">
          <input type="text" id="edit_first_name" name="first_name" placeholder="First Name" required>
          <input type="text" id="edit_last_name" name="last_name" placeholder="Last Name" required>
        </div>
      </div>

      <!-- Contact Info -->
      <div class="form-group">
        <label>Contact Information</label>
        <div style="display: flex; gap: 10px;">
          <input type="email" id="edit_email_address" name="email_address" placeholder="Email Address" required>
          <input type="text" id="edit_contact_number" name="contact_number" placeholder="Contact Number" required>
        </div>
      </div>

      <!-- Job Info -->
      <div class="form-group">
        <label>Job Information</label>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
          <select id="edit_work_station" name="work_station" required>
            <option value="">-- Select Work Station --</option>
            <option value="Cafe">Cafe</option>
            <option value="Spa">Spa</option>
            <option value="Beauty Lounge">Beauty Lounge</option>
          </select>

          <select id="edit_role" name="role" required>
            <option value="">-- Select Role --</option>
          </select>

          <select id="edit_shift" name="shift" required>
            <option value="">-- Select Shift --</option>
            <option value="Morning">Morning</option>
            <option value="Mid">Mid</option>
            <option value="Night">Night</option>
            <option value="Fixed">Fixed</option>
          </select>
        </div>
      </div>

      <!-- Employee Image Upload -->
      <div class="form-group">
        <label>Change Employee Image</label>
        <input type="file" id="edit_employee_image" name="employee_image" accept="image/*">
      </div>

      <button type="submit" class="btn-submit">Update Employee</button>
    </form>
  </div>
</div>


<!-- 🔹 Image Preview Modal -->
<div id="imagePreviewModal" class="img-preview-modal">
  <div class="modal-box">
    <span class="close-btn">&times;</span>
    <img class="modal-content" id="previewImage">
  </div>
</div>



<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="modal">
  <div class="modal-content small">
    <div class="head">
      <h3>Confirm Delete</h3>
      <span class="close-btn" id="closeDeleteModal">&times;</span>
    </div>

    <p id="deleteMessage">Are you sure you want to delete this employee?</p>

    <div class="modal-actions">
      <button type="button" class="btn-cancel" id="cancelDeleteBtn">Cancel</button>
      <button type="button" class="btn-danger" id="confirmDeleteBtn">Delete</button>
    </div>
  </div>
</div>


<!-- Download Modal -->
<div id="downloadModal" class="confirmation-modal">
  <div class="modal-content">
    <div class="head">
      <h3 id="downloadModalTitle">Download File</h3>
      <span class="close-btn" id="closeDownloadModal">&times;</span>
    </div>
    <p id="downloadModalMessage">Do you want to download this file?</p>
    <div class="modal-actions">
      <button type="button" class="btn-cancel" id="cancelDownloadBtn">Cancel</button>
      <button type="button" class="btn-danger" id="confirmDownloadBtn">Download</button>
    </div>
  </div>
</div>

