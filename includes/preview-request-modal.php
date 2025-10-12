<?php
// includes/preview-request-modal.php
?>
<link rel="stylesheet" href="../css/components/form2.css">
<link rel="stylesheet" href="../css/components/alerts.css">

<!-- =========================
     Preview Request Modal
========================= -->
<div class="modal" id="previewRequestModal">
  <div class="modal-content preview-modal-content">
    <span class="close-btn">&times;</span>

    <h2 id="preview_request_type" class="preview-title">Request Details</h2>

    <div class="scrollable">
      <div class="preview-list">
        <div class="preview-item">
          <strong>Employee:</strong>
          <span id="preview_employee_name"></span>
        </div>
        <div class="preview-item">
          <strong>Email:</strong>
          <span id="preview_employee_email"></span>
        </div>
        <div class="preview-item">
          <strong>Station & Role:</strong>
          <span id="preview_station_role"></span>
        </div>
        <div class="preview-item">
          <strong>Shift:</strong>
          <span id="preview_shift"></span>
        </div>
        <div class="preview-item">
          <strong>Request Type:</strong>
          <span id="preview_request_type_text"></span>
        </div>
        <div class="preview-item">
          <strong>Details:</strong>
          <span id="preview_request_details"></span>
        </div>
        <div class="preview-item">
          <strong>Reason:</strong>
          <span id="preview_request_reason"></span>
        </div>
        <div class="preview-item">
          <strong>Target Date:</strong>
          <span id="preview_target_date"></span>
        </div>
        <div class="preview-item">
          <strong>Status:</strong>
          <span id="preview_request_status" class="status-pill"></span>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* ==============================
   Preview Request Modal Styling
============================== */

#previewRequestModal {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.6);
  z-index: 2000;
  justify-content: center;
  align-items: center;
  padding: 1rem;
}

#previewRequestModal.show {
  display: flex;
  animation: fadeIn 0.25s ease;
}

.preview-modal-content {
  background: var(--light);
  border-radius: 16px;
  padding: 1.5rem 1.5rem 1.2rem;
  box-shadow: 0 6px 15px rgba(0,0,0,0.12);
  max-width: 420px;
  width: 100%;
  position: relative;
  color: var(--dark);
  font-family: var(--primary-font);
  animation: slideUp 0.3s ease;
}

.preview-title {
  text-align: center;
  margin-bottom: 1.2rem;
  font-size: 1.3rem;
  font-weight: 700;
  color: var(--text-color);
}

.preview-list {
  display: flex;
  flex-direction: column;
  gap: 0.8rem;
}

.preview-item {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
  padding: 0.8rem 1rem;
  border-radius: 10px;
  background: var(--secondary-bg-color);
  color: var(--text-color);
  box-shadow: 0 1px 3px rgba(0,0,0,0.05);
  font-size: 0.9rem;
}

.preview-item strong {
  font-weight: 600;
  color: var(--dark);
}

.status-pill {
  display: inline-block;
  align-self: start;
  padding: 0.25rem 0.6rem;
  border-radius: 16px;
  background: var(--button-color);
  color: #fff;
  font-weight: 600;
  font-size: 0.75rem;
}

@keyframes fadeIn {
  from { opacity: 0; } to { opacity: 1; }
}
@keyframes slideUp {
  from { transform: translateY(20px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
</style>
