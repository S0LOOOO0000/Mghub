document.addEventListener("DOMContentLoaded", () => {
  const bookingTable = document.getElementById("bookingTable");
  const modal = document.getElementById("previewEventModal");
  const closeBtn = modal.querySelector(".close-btn");

  bookingTable.addEventListener("click", async (e) => {
    const btn = e.target.closest(".action-btn");
    if (!btn) return;

    // --- PREVIEW ---
    if (btn.classList.contains("preview")) {
      document.getElementById("preview_event_name").textContent = btn.dataset.event;
      document.getElementById("preview_customer_name").textContent = btn.dataset.name;
      document.getElementById("preview_customer_email").textContent = btn.dataset.email;
      document.getElementById("preview_customer_contact").textContent = btn.dataset.contact;
      document.getElementById("preview_event_date").textContent = btn.dataset.date;
      document.getElementById("preview_event_time").textContent = btn.dataset.time;
      document.getElementById("preview_event_description").textContent = btn.dataset.description;
      document.getElementById("preview_event_status").textContent = btn.dataset.status;

      modal.classList.add("show");
      return; // ✅ stop here
    }

    // --- APPROVE or DECLINE ---
    if (btn.classList.contains("approve") || btn.classList.contains("decline")) {
      const bookingId = btn.dataset.id;
      const action = btn.classList.contains("approve") ? "approve" : "decline";

      if (!bookingId) return;

      if (confirm(`Are you sure you want to ${action} this booking?`)) {
        try {
          const res = await fetch("../php/approve-booking.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `booking_id=${bookingId}&action=${action}`
          });
          const data = await res.json();
          alert(data.message);
          if (data.status === "success") location.reload();
        } catch (err) {
          alert("Error: " + err);
        }
      }
    }
  });

  // Close modal
  closeBtn.addEventListener("click", () => modal.classList.remove("show"));
  window.addEventListener("click", e => {
    if (e.target === modal) modal.classList.remove("show");
  });
});
