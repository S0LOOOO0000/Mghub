<?php
// preview-booking-approval.php
?>
<link rel="stylesheet" href="../css/components/form2.css">
<link rel="stylesheet" href="../css/components/alerts.css">

<!-- =========================
     Preview Booking Modal
========================= -->
<div class="modal" id="previewEventModal">
  <div class="modal-content preview-modal-content">
    <span class="close-btn">&times;</span>

    <h2 id="preview_event_name" class="preview-title"></h2>

    <div class="scrollable">
      <div class="preview-list">
        <div class="preview-item">
          <strong>Customer:</strong>
          <span id="preview_customer_name"></span>
        </div>
        <div class="preview-item">
          <strong>Email:</strong>
          <span id="preview_customer_email"></span>
        </div>
        <div class="preview-item">
          <strong>Contact:</strong>
          <span id="preview_customer_contact"></span>
        </div>
        <div class="preview-item">
          <strong>Date:</strong>
          <span id="preview_event_date"></span>
        </div>
        <div class="preview-item">
          <strong>Time:</strong>
          <span id="preview_event_time"></span>
        </div>
        <div class="preview-item">
          <strong>Description:</strong>
          <span id="preview_event_description"></span>
        </div>
        <div class="preview-item">
          <strong>Status:</strong>
          <span id="preview_event_status" class="status-pill"></span>
        </div>
      </div>
    </div>
  </div>
</div>







<style>
/* ==============================
   Preview Event Modal (Compact)
============================== */

/* Overlay */
#previewEventModal {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  z-index: 1000;
  justify-content: center;
  align-items: center;
  padding: 1rem;
}

#previewEventModal.show {
  display: flex;
  animation: fadeIn 0.25s ease;
}

/* Card */
.preview-modal-content {
  background: var(--light);
  border-radius: 16px;
  padding: 1.5rem 1.5rem 1.2rem;
  box-shadow: 0 6px 15px rgba(0,0,0,0.12);
  max-width: 380px;
  width: 100%;
  position: relative;
  font-family: var(--primary-font);
  color: var(--dark);
  animation: slideUp 0.3s ease;
}

/* Title */
#previewEventModal .preview-title {
  text-align: center;
  margin-bottom: 1.2rem;
  font-size: 1.3rem;
  font-weight: 700;
  color: var(--text-color);
}

/* Info List */
.preview-list {
  display: flex;
  flex-direction: column;
  gap: 0.8rem;
}

.preview-item {
  display: flex;
  flex-direction: column;
  gap: 0.2rem;
  padding: 0.7rem 0.9rem;
  border-radius: 10px;
  background: var(--secondary-bg-color);
  color: var(--text-color);
  box-shadow: 0 1px 3px rgba(0,0,0,0.05);
  font-size: 0.85rem;
}

.preview-item strong {
  font-weight: 600;
  color: var(--dark);
  font-size: 0.9rem;
}

/* Status Pill */
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

/* Animations */
@keyframes fadeIn {
  from { opacity: 0; } to { opacity: 1; }
}
@keyframes slideUp {
  from { transform: translateY(20px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
</style>
