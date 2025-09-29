<div id="customerEventModal" class="modal">
  <div class="modal-content">
    <button class="close-btn">&times;</button>
    <h2>Book an Event</h2>

    <?php
      $success = $_GET['success'] ?? null;
      $error = $_GET['error'] ?? null;
    ?>
    <?php if ($success): ?>
      <div class="success-popup"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="error-popup"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="scrollable">
      <form id="customerEventForm" method="POST" class="form-container">
        <div class="form-group">
          <label for="cust_name">Customer Name</label>
          <input type="text" id="cust_name" name="customer_name" required>
        </div>
        <div class="form-group">
          <label for="cust_email">Email</label>
          <input type="email" id="cust_email" name="customer_email" required>
        </div>
        <div class="form-group">
          <label for="cust_contact">Contact Number</label>
          <input type="text" id="cust_contact" name="customer_contact" required>
        </div>
        <div class="form-group">
          <label for="cust_event_name">Event Name</label>
          <input type="text" id="cust_event_name" name="event_name" required>
        </div>
        <div class="form-group">
          <label for="cust_event_date">Event Date</label>
          <input type="date" id="cust_event_date" name="event_date" required readonly>
        </div>
        <div class="form-group">
          <label for="cust_event_time">Event Time</label>
          <input type="time" id="cust_event_time" name="event_time" required>
        </div>
        <div class="form-group">
          <label for="cust_event_desc">Description</label>
          <textarea id="cust_event_desc" name="event_description"></textarea>
        </div>

        <button type="submit" class="btn-submit">Submit Booking</button>
      </form>
    </div>
  </div>
</div>
