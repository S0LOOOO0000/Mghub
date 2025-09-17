<link rel="stylesheet" href="../css/components/request-preview.css">

<!-- Request Preview Modal -->
<div id="requestPreviewModal" class="preview-modal">
  <div class="preview-modal-content">
    <div class="head">
      <h3>Request Preview</h3>
      <span class="close-btn">&times;</span>
    </div>

    <div class="preview-body">
      <!-- General Info -->
      <div class="preview-section">
        <h4>Employee Information</h4>
        <p><strong>Name:</strong> <span id="previewName"></span></p>
        <p><strong>Station:</strong> <span id="previewStation"></span></p>
        <p><strong>Role:</strong> <span id="previewRole"></span></p>
      </div>

      <!-- Change Shift Info -->
      <div class="preview-section change-shift" style="display:none;">
        <h4>Change Shift Request</h4>
        <p><strong>From:</strong> <span id="previewFromShift"></span></p>
        <p><strong>To:</strong> <span id="previewToShift"></span></p>
        <p><strong>Swap With:</strong> <span id="previewSwapName"></span></p>
        <p><strong>Date:</strong> <span id="previewDate"></span></p>
      </div>

      <!-- Leave Request Info -->
      <div class="preview-section leave-request" style="display:none;">
        <h4>Leave Request</h4>
        <p><strong>Leave Type:</strong> <span id="previewLeaveType"></span></p>
        <p><strong>Date:</strong> <span id="previewDateLeave"></span></p>
        <p><strong>Reason:</strong></p>
        <p id="previewReason"></p>
      </div>
    </div>

    <!-- Actions -->
    <div class="preview-actions">
      <button class="btn-approve" id="approveRequest">Approve</button>
      <button class="btn-decline" id="declineRequest">Decline</button>
    </div>
  </div>
</div>

<!-- Decline Reason Modal -->
<div id="declineModal" class="preview-modal">
  <div class="preview-modal-content">
    <div class="head">
      <h3>Decline Request</h3>
      <span class="close-btn">&times;</span>
    </div>
    <div class="preview-body">
      <label for="declineReason">Reason for Decline</label>
      <textarea id="declineReason" placeholder="Enter reason..." rows="4"></textarea>
    </div>
    <div class="preview-actions">
      <button class="btn-approve" id="confirmDecline">Submit</button>
    </div>
  </div>
</div>
