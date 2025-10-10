<link rel="stylesheet" href="../css/components/button.css">
<link rel="stylesheet" href="../css/components/form2.css">
<link rel="stylesheet" href="../css/components/alerts.css">

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

<!-- Approve confirmation modal -->
<div id="approveModal" class="confirmation-modal">
  <div class="modal-content">
    <div class="head">
      <h3 id="approveModalTitle">Approve Booking</h3>
      <span class="close-btn" data-close="approveModal">&times;</span>
    </div>
    <p id="approveModalMessage">Are you sure you want to approve this booking?</p>
    <div class="modal-actions">
      <button type="button" class="btn-cancel" data-close="approveModal">Cancel</button>
      <button type="button" class="btn-submit" id="confirmApproveBtn">Approve</button>
    </div>
  </div>
</div>

<!-- Decline confirmation modal -->
<div id="declineModal" class="confirmation-modal">
  <div class="modal-content">
    <div class="head">
      <h3 id="declineModalTitle">Decline Booking</h3>
      <span class="close-btn" data-close="declineModal">&times;</span>
    </div>
    <p id="declineModalMessage">Are you sure you want to decline this booking?</p>
    <div class="modal-actions">
      <button type="button" class="btn-cancel" data-close="declineModal">Cancel</button>
      <button type="button" class="btn-danger" id="confirmDeclineBtn">Decline</button>
    </div>
  </div>
</div>