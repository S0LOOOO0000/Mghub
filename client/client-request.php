<?php
include __DIR__ . '/../php/get-employee.php';
$success = $_GET['success'] ?? null;
$error = $_GET['error'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Requests</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/components/forrm-request.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js" integrity="sha512-r6rDA7W6ZeQhvl8S7yRVQUKVHdexq+GAlNkNNqVC7YyIV+NwqCTJe2hDWCiffTyRNOeGEzRRJ9ifvRm/HCzGYg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  </head>
<body>

<section class="sidebar">
  <?php include '../includes/client-sidebar.php'; ?>
</section>

<section class="content">
  <nav>
    <i class="material-icons icon-menu">menu</i>
    <?php include '../includes/admin-navbar.php'; ?>
  </nav>

  <div class="main">
    <div class="head-title">
      <div class="left">
        <h1>Employee Requests</h1>
        <ul class="breadcrumb">
          <li><a>Attendance Management</a></li>
          <li><i class='material-icons right-icon'>chevron_right</i></li>
          <li><a class="active">Home</a></li>
        </ul>
      </div>
    </div>

    <?php if ($success): ?>
      <div class="success-popup"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="error-popup"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="table-container">
      <div class="table-card">
        <div class="head">
          <h2>Employee Management</h2>
          <?php include '../includes/employee-dropdown.php'; ?>

          <!-- QR Scanner Buttons -->
          <button class="btn-time-in-icon" onclick="openQrScanner('change')">
            <i class="material-icons">login</i> Request Change
          </button>
          <button class="btn-time-out-icon" onclick="openQrScanner('leave')">
            <i class="material-icons">logout</i> Request Leave
          </button>
        </div>

        <table id="employeeTable">
          <thead>
            <tr>
              <th>#</th>
              <th>ID</th>
              <th>IMAGE</th>
              <th>NAME</th>
              <th>CONTACT</th>
              <th>STATION & ROLE</th>
              <th>SHIFT</th>
              <th>STATUS</th>
              <th>EMPLOYED</th>
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($employees)): $counter=1; ?>
              <?php foreach($employees as $emp): ?>
                <tr data-employee-id="<?= $emp['employee_id'] ?>">
                  <td><?= $counter++ ?></td>
                  <td><?= htmlspecialchars($emp['employee_code']) ?></td>
                  <td>
                    <img src="<?= !empty($emp['employee_image']) ? '../images/employee-photos/' . htmlspecialchars($emp['employee_image']) : 'https://placehold.co/50x50.png' ?>" width="50">
                  </td>
                  <td><?= htmlspecialchars($emp['first_name'].' '.$emp['last_name']) ?><br><?= htmlspecialchars($emp['email_address']) ?></td>
                  <td><?= htmlspecialchars($emp['contact_number']) ?></td>
                  <td><?= htmlspecialchars($emp['work_station'].' / '.$emp['role']) ?></td>
                  <td><span class="shift <?= strtolower($emp['shift']) ?>"><?= htmlspecialchars($emp['shift']) ?></span></td>
                  <td><?= htmlspecialchars($emp['status']) ?></td>
                  <td><?= date("F j, Y", strtotime($emp['created_at'])) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="9">No employees found</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <div class="table-pagination-container">
        <div class="total-rows" id="employeeTotalRows">
          Showing 1-10 of <?= $totalEmployees ?> employees
        </div>
        <div class="table-pagination" id="employeePagination"></div>
      </div>
    </div>
  </div>
</section>

<!-- QR Scanner Modal -->
<div id="qrModal">
  <div class="modal-content">
    <span id="closeQrModal" class="close">&times;</span>
    <h3 id="qrModalTitle">Scan QR</h3>
    <div id="qr-reader"></div>
  </div>
</div>

<!-- Attendance/Request Result Modal -->
<div id="attendanceModal" class="attendance-modal">
  <div class="modal-box">
    <h3 id="attendanceMessage"></h3>
    <p id="attendanceDetails"></p>
  </div>
</div>

<!-- 🔹 Change Shift Modal -->
<div id="changeShiftModal" class="request-modal">
  <div class="modal-content">
    <div class="head">
      <h3>Request Change Shift</h3>
      <span class="close-btn">&times;</span>
    </div>

    <form method="POST" action="../php/request-change-shift.php">
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
<div id="leaveRequestModal" class="request-modal">
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

<style>
#qrModal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:10000; justify-content:center; align-items:center; }
.modal-content { background:#fff; padding:20px; border-radius:12px; width:500px; max-width:90%; text-align:center; }
#qr-reader { width:100%; height:400px; margin:10px auto; }
#qr-reader video { width:100% !important; height:100% !important; object-fit:cover; border-radius:8px; }
.close { float:right; font-size:22px; cursor:pointer; }

.attendance-modal { display:none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.8); justify-content:center; align-items:center; z-index:2000; }
.attendance-modal .modal-box { background:#fff; padding:20px; border-radius:10px; text-align:center; max-width:350px; }
.attendance-modal.success .modal-box { border:2px solid green; }
.attendance-modal.error .modal-box { border:2px solid red; }
</style>

    <script src="../js/dashboard.js"></script>
    <script src="../js/request.js"></script>
</body>
</html>
