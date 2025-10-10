// request-approval.js
document.addEventListener("DOMContentLoaded", () => {
  // ---------- Elements ----------
  const table = document.querySelector(".order table");
  const tbody = table?.querySelector("tbody");
  const totalRowsEl = document.getElementById("requestTotalRows");
  const paginationContainer = document.getElementById("requestPagination");
  const searchInput = document.getElementById("requestSearch");
  const statusDropdown = document.querySelector('.custom-dropdown[data-filter="request-status"]');

  // Modals & buttons
  const approveModal = document.getElementById("requestApproveModal");
  const declineModal = document.getElementById("requestDeclineModal");
  const confirmApproveBtn = document.getElementById("confirmRequestApproveBtn");
  const confirmDeclineBtn = document.getElementById("confirmRequestDeclineBtn");

  // ---------- Config ----------
  const ROWS_PER_PAGE = 10;
  const filters = { status: "all", search: "" };
  let pendingAction = null;

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

  const normalize = s => (s || "").trim().toLowerCase();

  // ---------- Row filtering ----------
  function getAllRows() {
    return tbody ? Array.from(tbody.querySelectorAll("tr")) : [];
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
    if (!totalRowsEl) return;
    totalRowsEl.textContent = total === 0 
      ? "No requests found" 
      : `Showing ${start}-${end} of ${total} requests`;
  }

  // ---------- Pagination ----------
  function renderTable(page = 1) {
    if (!tbody) return;
    const rows = getFilteredRows();
    const totalRows = rows.length;
    const totalPages = Math.max(1, Math.ceil(totalRows / ROWS_PER_PAGE));
    if (page < 1) page = 1;
    if (page > totalPages) page = totalPages;

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
    if (!paginationContainer) return;
    paginationContainer.innerHTML = "";

    const makeBtn = (label, disabled, pageNum, active = false) => {
      const btn = document.createElement("button");
      btn.type = "button";
      btn.textContent = label;
      if (disabled) btn.disabled = true;
      if (active) btn.classList.add("active");
      if (pageNum !== null) btn.addEventListener("click", () => renderTable(pageNum));
      return btn;
    };

    paginationContainer.appendChild(makeBtn("«", currentPage === 1, Math.max(1, currentPage - 1)));
    for (let i = 1; i <= totalPages; i++) {
      paginationContainer.appendChild(makeBtn(i, false, i, i === currentPage));
    }
    paginationContainer.appendChild(makeBtn("»", currentPage === totalPages, Math.min(totalPages, currentPage + 1)));
  }

  // ---------- Dropdown ----------
  function initStatusDropdown() {
    if (!statusDropdown || statusDropdown.dataset.init === "1") return;
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
      renderTable(1);
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
    statusDropdown.dataset.init = "1";
  }

  // ---------- Search ----------
  searchInput?.addEventListener("input", e => {
    filters.search = normalize(e.target.value);
    renderTable();
  });

  // ---------- Modals ----------
  const showModal = (modal) => { if(modal) modal.classList.add("show"); modal.style.display="flex"; };
  const hideModal = (modal) => { if(modal) { modal.classList.remove("show"); modal.style.display="none"; pendingAction=null; } };

  [approveModal, declineModal].forEach(modal => {
    modal?.addEventListener("click", e => { if(e.target===modal) hideModal(modal); });
    modal?.querySelector(".modal-content")?.addEventListener("click", e => e.stopPropagation());
  });

  document.querySelectorAll("[data-close]").forEach(btn => {
    btn.addEventListener("click", () => hideModal(document.getElementById(btn.dataset.close)));
  });

  // ---------- Table actions ----------
  tbody?.addEventListener("click", e => {
    const btn = e.target.closest("button");
    if (!btn) return;

    const id = btn.dataset.id;
    if (!id) return showPopup("Missing request ID", "error");

    if (btn.classList.contains("btn-approve") || btn.classList.contains("btn-reject")) {
      pendingAction = { id, action: btn.classList.contains("btn-approve") ? "approve" : "decline" };
      showModal(pendingAction.action === "approve" ? approveModal : declineModal);
    }
  });

  // ---------- Confirm actions ----------
  const handleAction = async (id, action) => {
    try {
      const res = await fetch("../php/approve-request.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `request_id=${encodeURIComponent(id)}&action=${encodeURIComponent(action)}`
      });
      const data = await res.json();

      if (data.status === "success") {
        showPopup(data.message, "success");
        const row = tbody.querySelector(`button[data-id="${id}"]`)?.closest("tr");
        if (row) {
          const statusSpan = row.querySelector(".req-status span");
          const newStatus = action === "approve" ? "Approved" : "Declined";
          if (statusSpan) {
            statusSpan.textContent = newStatus;
            statusSpan.className = "status " + newStatus.toLowerCase();
          }
          row.querySelector(".btn-approve")?.remove();
          row.querySelector(".btn-reject")?.remove();
        }
        renderTable();
      } else {
        showPopup(data.message || "Action failed", "error");
      }
    } catch (err) {
      console.error("Fetch error:", err);
      showPopup("Request failed. See console.", "error");
    }
  };

  confirmApproveBtn?.addEventListener("click", async () => {
    if (!pendingAction) return;
    confirmApproveBtn.disabled = true;
    await handleAction(pendingAction.id, "approve");
    hideModal(approveModal);
    confirmApproveBtn.disabled = false;
  });

  confirmDeclineBtn?.addEventListener("click", async () => {
    if (!pendingAction) return;
    confirmDeclineBtn.disabled = true;
    await handleAction(pendingAction.id, "decline");
    hideModal(declineModal);
    confirmDeclineBtn.disabled = false;
  });

  // ---------- Init ----------
  initStatusDropdown();
  renderTable();
});
