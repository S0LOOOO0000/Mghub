<link rel="stylesheet" href="../css/components/small-modal.css">
<link rel="stylesheet" href="../css/components/alerts.css">

<!-- ADD EVENT MODAL -->
<div id="addEventModal" class="modal-univ">
  <div class="modal-contents">
    <button class="close-btn">&times;</button>
    <h2>Add New Event</h2>
    
    <?php
      $success = $_GET['success'] ?? null;
      $error = $_GET['error'] ?? null;
    ?>

    <?php if ($success): ?>
      <div id="success-popup" class="success-popup"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div id="error-popup" class="error-popup"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
<div class="scrollable">
    <form id="addEventForm" method="POST" action="../php/add-event.php" class="form-container">
      <div class="form-group">
        <label for="add_customer_name">Customer Name</label>
        <input type="text" id="add_customer_name" name="customer_name" required>
      </div>

      <div class="form-group">
        <label for="add_customer_email">Email</label>
        <input type="email" id="add_customer_email" name="customer_email" required>
      </div>

      <div class="form-group">
        <label for="add_customer_contact">Contact Number</label>
        <input type="text" id="add_customer_contact" name="customer_contact" required>
      </div>

      <div class="form-group">
        <label for="add_event_name">Event Name</label>
        <input type="text" id="add_event_name" name="event_name" required>
      </div>

      <div class="form-group">
        <label for="add_event_date">Event Date</label>
        <input type="date" id="add_event_date" name="event_date" required>
      </div>

      <div class="form-group">
        <label for="add_event_time">Event Time</label>
        <input type="time" id="add_event_time" name="event_time" required>
      </div>

      <div class="form-group">
        <label for="add_event_description">Description</label>
        <textarea id="add_event_description" name="event_description"></textarea>
      </div>

      <!-- Hidden field → always Booked -->
      <input type="hidden" name="event_status" value="Booked">

        <button type="submit" class="btn-submit">Save Event</button>
    </form>
  </div>
  </div>
</div>

<!-- EDIT EVENT MODAL -->
<div id="editEventModal" class="modal-univ">
  <div class="modal-contents">
    <button class="close-btn">&times;</button>
    <h2>Edit Event</h2>
      <div class="scrollable">
    <form id="editEventForm" method="POST" action="../php/edit-event.php" class="form-container">
      <input type="hidden" name="event_id" id="edit_event_id">

      <div class="form-group">
        <label for="edit_customer_name">Customer Name</label>
        <input type="text" id="edit_customer_name" name="customer_name" required>
      </div>

      <div class="form-group">
        <label for="edit_customer_email">Email</label>
        <input type="email" id="edit_customer_email" name="customer_email" required>
      </div>

      <div class="form-group">
        <label for="edit_customer_contact">Contact Number</label>
        <input type="text" id="edit_customer_contact" name="customer_contact" required>
      </div>

      <div class="form-group">
        <label for="edit_event_name">Event Name</label>
        <input type="text" id="edit_event_name" name="event_name" required>
      </div>

      <div class="form-group">
        <label for="edit_event_date">Event Date</label>
        <input type="date" id="edit_event_date" name="event_date" required>
      </div>

      <div class="form-group">
        <label for="edit_event_time">Event Time</label>
        <input type="time" id="edit_event_time" name="event_time" required>
      </div>

      <div class="form-group">
        <label for="edit_event_description">Description</label>
        <textarea id="edit_event_description" name="event_description"></textarea>
      </div>

      <!-- Editable status -->
      <div class="form-group">
        <label for="edit_event_status">Status</label>
        <select id="edit_event_status" name="event_status" required>
          <option value="Booked">Booked</option>
          <option value="Cancelled">Cancelled</option>
          <option value="Completed">Completed</option>
        </select>
      </div>

      <div class="form-group form-switch">
        <label class="switch-label" for="send_email">Send Email Notification:</label>
        <label class="switch">
          <input type="checkbox" id="edit_send_email" name="send_email" value="1">
          <span class="slider round"></span>
        </label>
      </div>

      <button type="submit" class="btn-submit">Save Changes</button>
    </form>
  </div>  
  </div>
</div>


<!-- Delete Event Confirmation Modal -->
<div id="eventDeleteModal" class="modal-univ">
  <div class="modal-contents small">
    <div class="head">
      <h3>Confirm Delete</h3>
      <span class="close-btn" id="closeEventDeleteModal">&times;</span>
    </div>

    <form id="eventDeleteForm">
      <input type="hidden" name="event_id" />
      <p id="eventDeleteText">Are you sure you want to delete this event?</p>

      <div class="modal-actions">
        <button type="button" class="btn-cancel" id="cancelEventDeleteBtn">Cancel</button>
        <button type="submit" class="btn-danger" id="confirmEventDeleteBtn">Delete</button>
      </div>
    </form>
  </div>
</div>







<style>

.form-switch {
  display: flex;
  align-items: center;
  gap: 8px;           /* space between label and switch */
}

/* Label for switch */
.form-switch .switch-label {
  font-weight: 500;
  white-space: nowrap;
  margin: 0;
}

/* Switch styling */
.form-switch .switch {
  position: relative;
  display: inline-block;
  width: 50px;       /* switch width */
  height: 24px;      /* switch height */
}

.form-switch .switch input {
  display: none;
}

.form-switch .slider {
  position: absolute;
  cursor: pointer;
  top: 0; left: 0; right: 0; bottom: 0;
  background-color: #ccc;
  transition: 0.4s;
  border-radius: 18px;
}

.form-switch .slider:before {
  position: absolute;
  content: "";
  height: 20px;  /* knob size smaller than slider height */
  width: 20px;
  left: 2px;
  right: 2px;
  bottom: 2px;
  background-color: white;
  transition: 0.4s;
  border-radius: 50%;
}

.form-switch .slider:after {
right: 2px;
}

.form-switch input:checked + .slider {
  background-color: var(--blue);
}

.form-switch input:checked + .slider:before {
  transform: translateX(18px);  /* width - knob - padding */
}


</style>