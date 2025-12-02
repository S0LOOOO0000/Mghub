<link rel="stylesheet" href="../css/components/forrm-request.css">

<?php
$success = $_GET['success'] ?? null;
$error = $_GET['error'] ?? null;
?>

<!-- ✅ Success popup -->
<?php if ($success): ?>
<div id="success-popup" class="success-popup"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<!-- ❌ Error popup -->
<?php if ($error): ?>
<div id="error-popup" class="error-popup"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>


<!-- 🔹 Edit Attendance Modal -->
<div id="editAttendanceModal" class="modal">
  <div class="modal-content">
    <div class="head">
      <h3>Edit Attendance</h3>
      <span class="close-btn" id="closeEditAttendance">&times;</span>
    </div>

    <form class="form-container" method="POST" action="../php/edit-attendance.php">
      <input type="hidden" id="edit_attendance_id" name="attendance_id">

      <div class="form-group">
        <label>Attendance Date</label>
        <input type="date" id="edit_attendance_date" name="attendance_date" onkeydown="return false">
      </div>

      <div class="form-group">
        <label>Time In</label>
        <input type="time" id="edit_time_in" name="time_in" required>
      </div>

      <div class="form-group">
        <label>Time Out</label>
        <input type="time" id="edit_time_out" name="time_out" required>
      </div>

      <div class="form-group">
        <label>Attendance Status</label>
        <select id="edit_attendance_status" name="attendance_status" required>
          <option value="Present">Present</option>
          <option value="Late">Late</option>
          <option value="Absent">Absent</option>
          <option value="On Leave">On Leave</option>
          <option value="Request">Request</option>
        </select>
      </div>


      <div class="modal-actions">
      <button type="submit" class="btn-submit">Update Attendance</button>
      </div>
    </form>
  </div>
</div>


<!-- 🔹 Change Shift Modal -->
<div id="changeShiftModal" class="modal">
  <div class="modal-content">
    <div class="head">
      <h3>Request Change Shift</h3>
      <span class="close-btn">&times;</span>
    </div>

    <form class="form-container" method="POST" action="../php/request-change-shift.php">
      <input type="hidden" id="change_request_employee_id" name="employee_id">

      <div class="form-group">
        <label>Target Employee</label>
        <select id="target_employee_id" name="target_employee_id" required>
          <option value="">-- Select Employee --</option>
        </select>
      </div>

      <div class="form-group">
        <label>Target Shift Date</label>
        <input type="date" name="target_shift_date" required>
      </div>

      <div class="form-group">
        <label>Reason</label>
        <textarea name="reason" rows="3" placeholder="Enter reason..." required></textarea>
      </div>

      <div class="modal-actions"> 
      <button type="submit" class="btn-submit">Submit Request</button>
      </div>
    </form>
  </div>
</div>




<!-- 🔹 Leave Request Modal -->
<div id="leaveRequestModal" class="modal">
  <div class="modal-content">
    <div class="head">
      <h3>Request Leave</h3>
      <span class="close-btn" id="closeLeaveRequest">&times;</span>
    </div>

    <form class="form-container" method="POST" action="../php/request-leave.php">
      <input type="hidden" id="leave_request_employee_id" name="employee_id">

      <div class="form-group">
        <label>Leave Date</label>
        <input type="date" id="leave_date" name="target_date" required>
      </div>

      <div class="form-group">
        <label>Leave Type</label>
        <select id="leave_type" name="leave_type" required>
          <option value="">-- Select Leave Type --</option>
          <option value="Vacation">Vacation</option>
          <option value="Sick">Sick</option>
          <option value="Emergency">Emergency</option>
          <option value="Other">Other</option>
        </select>
      </div>

      <div class="form-group">
        <label>Reason</label>
        <textarea id="leave_reason" name="reason" rows="3" placeholder="Enter reason" required></textarea>
      </div>

      <button type="submit" class="btn-submit">Submit Leave</button>
    </form>
  </div>
</div>


<!-- Preview Modal -->
<div id="previewModal" class="modal">
  <div class="modal-content">
    <span class="modal-close">&times;</span>
    <h2>Request Preview</h2>
    <p><strong>Employee:</strong> <span id="previewEmployee"></span></p>
    <p><strong>Reason:</strong></p>
    <p id="previewReason"></p>
  </div>
</div>