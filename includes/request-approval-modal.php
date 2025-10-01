<link rel="stylesheet" href="../css/components/button.css">
<link rel="stylesheet" href="../css/components/form2.css">
<link rel="stylesheet" href="../css/components/alerts.css">

<!-- Approve confirmation modal -->
<div id="requestApproveModal" class="confirmation-modal">
  <div class="modal-content">
    <div class="head">
      <h3 id="approveRequestTitle">Approve Request</h3>
      <span class="close-btn" data-close="requestApproveModal">&times;</span>
    </div>
    <p id="approveRequestMessage">Are you sure you want to approve this request?</p>
    <div class="modal-actions">
      <button type="button" class="btn-cancel" data-close="requestApproveModal">Cancel</button>
      <button type="button" class="btn-submit" id="confirmRequestApproveBtn">Approve</button>
    </div>
  </div>
</div>

<!-- Decline confirmation modal -->
<div id="requestDeclineModal" class="confirmation-modal">
  <div class="modal-content">
    <div class="head">
      <h3 id="declineRequestTitle">Decline Request</h3>
      <span class="close-btn" data-close="requestDeclineModal">&times;</span>
    </div>
    <p id="declineRequestMessage">Are you sure you want to decline this request?</p>
    <div class="modal-actions">
      <button type="button" class="btn-cancel" data-close="requestDeclineModal">Cancel</button>
      <button type="button" class="btn-danger" id="confirmRequestDeclineBtn">Decline</button>
    </div>
  </div>
</div>
