/* export-booking.js - handles download confirmation + export PDF */

document.addEventListener("DOMContentLoaded", () => {
  const downloadModal       = document.getElementById("downloadModal");
  const downloadModalTitle  = downloadModal?.querySelector("#downloadModalTitle");
  const downloadModalMessage= downloadModal?.querySelector("#downloadModalMessage");
  const confirmDownloadBtn  = downloadModal?.querySelector("#confirmDownloadBtn");
  const cancelDownloadBtn   = downloadModal?.querySelector("#cancelDownloadBtn");
  const closeDownloadModal  = downloadModal?.querySelector("#closeDownloadModal");
  const previewModal        = document.getElementById("previewEventModal");
  const downloadEventBtn    = document.getElementById("downloadEventPDFBtn");

  const openModal  = modal => modal?.classList.add("show");
  const closeModal = modal => modal?.classList.remove("show");

  // Open download confirmation when "Download PDF" clicked in preview modal
  downloadEventBtn?.addEventListener("click", () => {
    if (!previewModal || !downloadModal || !confirmDownloadBtn) return;

    // Grab preview modal data
    const eventName        = document.getElementById("preview_event_name")?.textContent.trim() || "";
    const customerName     = document.getElementById("preview_customer_name")?.textContent.trim() || "";
    const customerEmail    = document.getElementById("preview_customer_email")?.textContent.trim() || "";
    const customerContact  = document.getElementById("preview_customer_contact")?.textContent.trim() || "";
    const eventDate        = document.getElementById("preview_event_date")?.textContent.trim() || "";
    const eventTime        = document.getElementById("preview_event_time")?.textContent.trim() || "";
    const eventDescription = document.getElementById("preview_event_description")?.textContent.trim() || "";
    const eventStatus      = document.getElementById("preview_event_status")?.textContent.trim() || "";

    // Close preview modal and open confirmation modal
    closeModal(previewModal);
    downloadModalTitle && (downloadModalTitle.textContent = "Download Booking PDF");
    downloadModalMessage && (downloadModalMessage.textContent = `Do you want to download the booking PDF for "${eventName}"?`);
    openModal(downloadModal);

    // Confirm download
    const handler = () => {
      const query = `?name=${encodeURIComponent(customerName)}&email=${encodeURIComponent(customerEmail)}&contact=${encodeURIComponent(customerContact)}&event=${encodeURIComponent(eventName)}&date=${encodeURIComponent(eventDate)}&time=${encodeURIComponent(eventTime)}&description=${encodeURIComponent(eventDescription)}&status=${encodeURIComponent(eventStatus)}`;
      window.location.href = `../php/export-booking-pdf.php${query}`;
      closeModal(downloadModal);
    };

    // Remove any previous listener and attach new one
    confirmDownloadBtn.removeEventListener("click", handler);
    confirmDownloadBtn.addEventListener("click", handler, { once: true });
  });

  // Cancel / Close modal handlers
  cancelDownloadBtn?.addEventListener("click", () => closeModal(downloadModal));
  closeDownloadModal?.addEventListener("click", () => closeModal(downloadModal));

  // Optional: clicking outside modal closes
  downloadModal?.addEventListener("click", e => {
    if (e.target === downloadModal) closeModal(downloadModal);
  });
});
