// bookings-approval.js (final applied version)
document.addEventListener("DOMContentLoaded", () => {
  // ---------- Elements ----------
  const bookingTable = document.getElementById("bookingTable");
  const bookingTbody = bookingTable?.querySelector("tbody");
  const statusDropdown = document.querySelector('.custom-dropdown[data-filter="booking-status"]');
  const bookingTotalRows = document.getElementById("bookingTotalRows");
  const bookingSearch = document.getElementById("bookingSearch");
  const bookingPagination = document.getElementById("bookingPagination");
  const previewModal = document.getElementById("previewEventModal");

  // Confirmation modals/buttons
  const approveModal = document.getElementById("approveModal");
  const declineModal = document.getElementById("declineModal");
  const confirmApproveBtn = document.getElementById("confirmApproveBtn");
  const confirmDeclineBtn = document.getElementById("confirmDeclineBtn");

  // ---------- Config ----------
  const ROWS_PER_PAGE = 10;
  const filters = { status: "all", search: "" };
  let pendingAction = null; // { id, action }

  // ---------- Popup ----------
  const showPopup = (msg, type = "success") => {
    if (!msg) return;
    const popup = document.createElement("div");
    popup.className = type === "success" ? "success-popup" : "error-popup";
    popup.textContent = msg;
    document.body.appendChild(popup);
    requestAnimationFrame(() => (popup.style.opacity = "1"));
    setTimeout(() => {
      popup.style.opacity = "0";
      setTimeout(() => popup.remove(), 500);
    }, 3000);
  };

  // ---------- Helpers ----------
  const normalize = s => (s || "").trim().toLowerCase();

  function getAllRows() {
    return bookingTbody ? Array.from(bookingTbody.querySelectorAll("tr")) : [];
  }

  function getFilteredRows() {
    return getAllRows().filter(row => {
      const statusCell = row.querySelector(".req-status span");
      const rowStatus = normalize(statusCell?.textContent);
      const rowText = normalize(row.textContent || "");
      const passStatus = filters.status === "all" || rowStatus === filters.status;
      const passSearch = !filters.search || rowText.includes(filters.search);
      return passStatus && passSearch;
    });
  }

  function updateTotals(start, end, total) {
    if (!bookingTotalRows) return;
    bookingTotalRows.textContent =
      total === 0 ? "No requests found" : `Showing ${start}-${end} of ${total} requests`;
  }

  // ---------- Pagination & Rendering ----------
  function renderBookingTable(page = 1) {
    if (!bookingTbody) return;
    const rows = getFilteredRows();
    const totalRows = rows.length;
    const totalPages = Math.max(1, Math.ceil(totalRows / ROWS_PER_PAGE));
    if (page < 1) page = 1;
    if (page > totalPages) page = totalPages;

    // hide all rows
    getAllRows().forEach(r => (r.style.display = "none"));

    const start = (page - 1) * ROWS_PER_PAGE;
    const end = Math.min(start + ROWS_PER_PAGE, totalRows);

    rows.slice(start, end).forEach((row, idx) => {
      row.style.display = "";
      const numberCell = row.querySelector("td:first-child");
      if (numberCell) numberCell.textContent = start + idx + 1;
    });

    updateTotals(start + 1, end, totalRows);
    renderPagination(totalPages, page);
  }

  function renderPagination(totalPages, currentPage) {
    if (!bookingPagination) return;
    bookingPagination.innerHTML = "";

    const makeBtn = (label, disabled, pageNum, active = false) => {
      const btn = document.createElement("button");
      btn.type = "button";
      btn.textContent = label;
      if (disabled) btn.disabled = true;
      if (active) btn.classList.add("active");
      if (pageNum !== null) btn.addEventListener("click", () => renderBookingTable(pageNum));
      return btn;
    };

    bookingPagination.appendChild(makeBtn("«", currentPage === 1, Math.max(1, currentPage - 1)));
    for (let i = 1; i <= totalPages; i++) {
      bookingPagination.appendChild(makeBtn(i, false, i, i === currentPage));
    }
    bookingPagination.appendChild(makeBtn("»", currentPage === totalPages, Math.min(totalPages, currentPage + 1)));
  }

  // ---------- Dropdown (status) ----------
  function initStatusDropdown() {
    if (!statusDropdown || statusDropdown.dataset.baInit === "1") return;
    const toggleBtn = statusDropdown.querySelector(".dropdown-toggle");
    const menu = statusDropdown.querySelector(".dropdown-menu");
    if (!toggleBtn || !menu) return;

    menu.style.display = "none";

    toggleBtn.addEventListener("click", e => {
      e.stopPropagation();
      const open = menu.style.display === "block";
      menu.style.display = open ? "none" : "block";
      statusDropdown.classList.toggle("active", !open);
    });

    menu.addEventListener("click", e => {
      const li = e.target.closest("li");
      if (!li) return;
      menu.querySelectorAll("li").forEach(i => i.classList.remove("selected"));
      li.classList.add("selected");
      filters.status = (li.dataset.value || "all").toLowerCase();
      toggleBtn.innerHTML = `Status: ${li.textContent} <i class="material-icons dropdown-icon">expand_more</i>`;
      menu.style.display = "none";
      statusDropdown.classList.remove("active");
      renderBookingTable(1);
    });

    document.addEventListener("click", e => {
      if (!statusDropdown.contains(e.target)) {
        menu.style.display = "none";
        statusDropdown.classList.remove("active");
      }
    });
    document.addEventListener("keydown", e => {
      if (e.key === "Escape") {
        menu.style.display = "none";
        statusDropdown.classList.remove("active");
      }
    });

    const defaultLi = menu.querySelector('li[data-value="all"]');
    if (defaultLi && !menu.querySelector("li.selected")) defaultLi.classList.add("selected");
    statusDropdown.dataset.baInit = "1";
  }

  // ---------- Search ----------
  bookingSearch?.addEventListener("input", e => {
    filters.search = normalize(e.target.value);
    renderBookingTable();
  });

  // ---------- Modal helpers ----------
  function showModal(modal) {
    if (!modal) return;
    modal.classList.add("show");
    modal.style.display = "flex";
  }
  function hideModal(modal) {
    if (!modal) return;
    modal.classList.remove("show");
    modal.style.display = "none";
  }

  // ---------- Table actions ----------
  bookingTable?.addEventListener("click", e => {
    const btn = e.target.closest(".action-btn");
    if (!btn) return;

    // Preview
    if (btn.classList.contains("preview")) {
      const ev = btn.dataset;
      if (!previewModal) return;
      previewModal.querySelector("#preview_event_name").textContent = ev.event || "";
      previewModal.querySelector("#preview_customer_name").textContent = ev.name || "";
      previewModal.querySelector("#preview_customer_email").textContent = ev.email || "";
      previewModal.querySelector("#preview_customer_contact").textContent = ev.contact || "";
      previewModal.querySelector("#preview_event_date").textContent = ev.date || "";
      previewModal.querySelector("#preview_event_time").textContent = ev.time || "";
      previewModal.querySelector("#preview_event_description").textContent = ev.description || "";
      previewModal.querySelector("#preview_event_status").textContent = ev.status || "";
      return showModal(previewModal);
    }

    // Approve/Decline
    if (btn.classList.contains("approve") || btn.classList.contains("decline")) {
      const bookingId = btn.dataset.id;
      if (!bookingId) return showPopup("Missing booking ID.", "error");
      pendingAction = { id: bookingId, action: btn.classList.contains("approve") ? "approve" : "decline" };
      showModal(pendingAction.action === "approve" ? approveModal : declineModal);
    }
  });

  // ---------- Confirm actions ----------
  confirmApproveBtn?.addEventListener("click", async () => {
  if (!pendingAction || confirmApproveBtn.disabled) return;
  confirmApproveBtn.disabled = true; // prevent double click
  await handleBookingAction(pendingAction.id, "approve");
  hideModal(approveModal);
  pendingAction = null;
  setTimeout(() => (confirmApproveBtn.disabled = false), 3000); // re-enable after 1s
});

confirmDeclineBtn?.addEventListener("click", async () => {
  if (!pendingAction || confirmDeclineBtn.disabled) return;
  confirmDeclineBtn.disabled = true;
  await handleBookingAction(pendingAction.id, "decline");
  hideModal(declineModal);
  pendingAction = null;
  setTimeout(() => (confirmDeclineBtn.disabled = false), 3000);
});


  // Close modal buttons
  document.querySelectorAll("[data-close], [data-cancel]").forEach(btn => {
    btn.addEventListener("click", () => {
      const targetId = btn.getAttribute("data-close") || btn.getAttribute("data-cancel");
      hideModal(document.getElementById(targetId));
      pendingAction = null;
    });
  });

  [approveModal, declineModal, previewModal].forEach(m => {
    m?.addEventListener("click", e => {
      if (e.target === m) hideModal(m);
    });
  });

  // ---------- Backend call ----------
  async function handleBookingAction(bookingId, action) {
    try {
      const res = await fetch("../php/approve-booking.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `booking_id=${encodeURIComponent(bookingId)}&action=${encodeURIComponent(action)}`
      });
      const data = await res.json();

      if (data.status === "success") {
        showPopup(data.message || "Booking updated successfully!", "success");
        const rowBtn = bookingTbody?.querySelector(`.action-btn[data-id="${bookingId}"]`);
        const row = rowBtn?.closest("tr");
        if (row) {
          const newStatus = action === "approve" ? "Approved" : "Declined";
          const statusSpan = row.querySelector(".req-status span");
          if (statusSpan) {
            statusSpan.textContent = newStatus;
            statusSpan.className = newStatus.toLowerCase();
          }
          row.querySelector(".action-btn.approve")?.remove();
          row.querySelector(".action-btn.decline")?.remove();
          const previewBtn = row.querySelector(".action-btn.preview");
          if (previewBtn) previewBtn.dataset.status = newStatus;
        }
        renderBookingTable();
      } else {
        showPopup(data.message || "Action failed.", "error");
      }
    } catch (err) {
      console.error(err);
      showPopup("Request failed. See console for details.", "error");
    }
  }

  // ---------- Preview close ----------
  previewModal?.querySelector(".close-btn")?.addEventListener("click", () => hideModal(previewModal));

  // ---------- Init ----------
  initStatusDropdown();
  renderBookingTable();
});
