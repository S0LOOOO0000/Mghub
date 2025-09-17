document.addEventListener("DOMContentLoaded", () => {
    /** -------------------- UTILITIES -------------------- **/
    function showPopup(message, type = "success") {
        if (!message) return;
        const popup = document.createElement("div");
        popup.className = type === "success" ? "success-popup" : "error-popup";
        popup.textContent = message;
        document.body.appendChild(popup);
        requestAnimationFrame(() => (popup.style.opacity = "1"));
        setTimeout(() => {
            popup.style.opacity = "0";
            setTimeout(() => popup.remove(), 500);
        }, 3000);
    }

    function postData(url, data) {
        return fetch(url, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams(data).toString(),
        }).then(res => res.text());
    }

    function clearUrlParams() {
        if (window.history.replaceState) {
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
        }
    }

    /** -------------------- MODALS -------------------- **/
    const modals = {
        changeShift: document.getElementById("changeShiftModal"),
        leaveRequest: document.getElementById("leaveRequestModal"),
    };

    function openModal(modal) { if (modal) modal.classList.add("show"); }
    function closeModal(modal) { if (modal) modal.classList.remove("show"); }

    Object.values(modals).forEach(modal => {
        if (!modal) return;
        modal.querySelector(".close-btn")?.addEventListener("click", () => closeModal(modal));
        window.addEventListener("click", e => { if (e.target === modal) closeModal(modal); });
    });

    /** -------------------- MODAL POPULATORS -------------------- **/
    function populateChangeShiftModal(btn) {
        const empId = btn.dataset.employeeId || btn.closest("tr")?.dataset.employeeId;
        if (!empId) { showPopup("Missing employee ID for change shift request", "error"); return; }

        document.getElementById("change_request_employee_id").value = empId;
        const select = document.getElementById("target_employee_id");
        select.innerHTML = "<option>Loading employees...</option>";

        fetch(`../php/get-target-employees.php?employee_id=${empId}`)
            .then(res => res.json())
            .then(data => {
                select.innerHTML = "";
                if (!data || data.length === 0) { select.innerHTML = "<option value=''>No employees available</option>"; return; }

                let currentShift = null, currentGroup = null;
                data.forEach(emp => {
                    if (emp.shift !== currentShift) {
                        currentShift = emp.shift;
                        currentGroup = document.createElement("optgroup");
                        currentGroup.label = currentShift + " Shift";
                        select.appendChild(currentGroup);
                    }
                    const option = document.createElement("option");
                    option.value = emp.employee_id;
                    option.textContent = `${emp.first_name} ${emp.last_name} (${emp.shift})`;
                    currentGroup.appendChild(option);
                });
            })
            .catch(err => { console.error("Error fetching employees:", err); select.innerHTML = "<option value=''>Error loading employees</option>"; });

        openModal(modals.changeShift);
    }

    function populateLeaveModal(btn) {
        const empId = btn.dataset.employeeId || btn.closest("tr")?.dataset.employeeId;
        if (!empId) { showPopup("Missing employee ID for leave request", "error"); return; }

        document.getElementById("leave_request_employee_id").value = empId;
        openModal(modals.leaveRequest);
    }

    /** -------------------- REQUEST STATUS UPDATE -------------------- **/
    function updateRequestStatus(btn, status) {
        const requestId = btn.dataset.id;
        postData("../php/update-request-status.php", { request_id: requestId, status })
            .then(data => {
                if (data.trim() === "success") {
                    const row = btn.closest("tr");
                    const statusCell = row.querySelector(".status");
                    statusCell.textContent = status;
                    statusCell.className = `status ${status.toLowerCase()}`;
                    btn.closest("td").innerHTML = "<span>-</span>";
                    showPopup(`Request ${status.toLowerCase()}!`, "success");
                } else showPopup("Failed: " + data, "error");
            })
            .catch(err => showPopup("Error: " + err, "error"));
    }

    /** -------------------- EVENT LISTENERS -------------------- **/
    document.addEventListener("click", e => {
        if (e.target.closest(".request-btn")) populateChangeShiftModal(e.target.closest(".request-btn"));
        if (e.target.closest(".leave-btn")) populateLeaveModal(e.target.closest(".leave-btn"));
        if (e.target.closest(".btn-approve")) updateRequestStatus(e.target.closest(".btn-approve"), "Approved");
        if (e.target.closest(".btn-decline")) updateRequestStatus(e.target.closest(".btn-decline"), "Declined");
    });

    /** -------------------- FILTERS & PAGINATION -------------------- **/
    const selectedFilters = { status: "all", shift: "all", station: "all", type: "all" };
    const tbody = document.querySelector("table tbody");
    const totalRowsEl = document.getElementById("totalRows");
    const paginationContainer = document.getElementById("pagination");
    const rowsPerPage = 10;
    let currentPage = 1;

    function getFilteredRows() {
        const rows = Array.from(tbody.querySelectorAll("tr"));
        return rows.filter(row => {
            const rowStatus = row.querySelector(".status")?.textContent.trim().toLowerCase() || "";
            const rowShift = row.querySelector(".shift")?.textContent.trim().toLowerCase() || "";
            const rowStation = row.querySelector("td:nth-child(4) span")?.textContent.trim().toLowerCase() || "";
            const rowType = row.querySelector("td:nth-child(5)")?.textContent.trim().toLowerCase() || "";

            return (
                (selectedFilters.status === "all" || rowStatus === selectedFilters.status.toLowerCase()) &&
                (selectedFilters.shift === "all" || rowShift === selectedFilters.shift.toLowerCase()) &&
                (selectedFilters.station === "all" || rowStation === selectedFilters.station.toLowerCase()) &&
                (selectedFilters.type === "all" || rowType === selectedFilters.type.toLowerCase())
            );
        });
    }

    function renderTable() {
        const filteredRows = getFilteredRows();
        Array.from(tbody.querySelectorAll("tr")).forEach(row => (row.style.display = "none"));

        const totalPages = Math.ceil(filteredRows.length / rowsPerPage) || 1;
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const pageRows = filteredRows.slice(start, end);

        pageRows.forEach(row => (row.style.display = ""));
        totalRowsEl.textContent = `Showing ${start + 1}-${start + pageRows.length} of ${filteredRows.length} requests`;

        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        paginationContainer.innerHTML = "";
        const createButton = (label, disabled, handler, active = false) => {
            const btn = document.createElement("button");
            btn.textContent = label;
            if (disabled) btn.disabled = true;
            if (active) btn.classList.add("active");
            btn.addEventListener("click", handler);
            return btn;
        };
        paginationContainer.appendChild(createButton("«", currentPage === 1, () => { currentPage--; renderTable(); }));
        for (let i = 1; i <= totalPages; i++) {
            paginationContainer.appendChild(createButton(i, false, () => { currentPage = i; renderTable(); }, i === currentPage));
        }
        paginationContainer.appendChild(createButton("»", currentPage === totalPages, () => { currentPage++; renderTable(); }));
    }

    renderTable();
    new MutationObserver(() => renderTable()).observe(tbody, { childList: true });

    /** -------------------- POPUP FROM URL PARAMS -------------------- **/
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has("success")) { showPopup(urlParams.get("success"), "success"); clearUrlParams(); }
    if (urlParams.has("error")) { showPopup(urlParams.get("error"), "error"); clearUrlParams(); }
});

document.addEventListener("DOMContentLoaded", () => {
  const previewModal = document.getElementById("requestPreviewModal");
  const declineModal = document.getElementById("declineModal");

  const closeBtns = document.querySelectorAll(".preview-modal .close-btn");

  // Fields
  const nameEl = document.getElementById("previewName");
  const stationEl = document.getElementById("previewStation");
  const roleEl = document.getElementById("previewRole");
  const fromShiftEl = document.getElementById("previewFromShift");
  const toShiftEl = document.getElementById("previewToShift");
  const swapNameEl = document.getElementById("previewSwapName");
  const dateEl = document.getElementById("previewDate");
  const leaveTypeEl = document.getElementById("previewLeaveType");
  const dateLeaveEl = document.getElementById("previewDateLeave");
  const reasonEl = document.getElementById("previewReason");

  // Open preview
  document.querySelectorAll(".action-btn.preview").forEach(btn => {
    btn.addEventListener("click", () => {
      // Get dataset (expand with PHP if needed)
      const requester = btn.dataset.requester;
      const station = btn.dataset.station;
      const role = btn.dataset.role;
      const type = btn.dataset.type;
      const reason = btn.dataset.reason;
      const targetDate = btn.dataset.date;
      const fromShift = btn.dataset.fromshift;
      const toShift = btn.dataset.toshift;
      const swapName = btn.dataset.swapname;
      const leaveType = btn.dataset.leavetype;

      // Reset
      document.querySelector(".change-shift").style.display = "none";
      document.querySelector(".leave-request").style.display = "none";

      // Fill general
      nameEl.textContent = requester;
      stationEl.textContent = station;
      roleEl.textContent = role;

      if (type === "Change Shift") {
        document.querySelector(".change-shift").style.display = "block";
        fromShiftEl.textContent = fromShift;
        toShiftEl.textContent = toShift;
        swapNameEl.textContent = swapName;
        dateEl.textContent = targetDate;
      } else if (type === "On Leave") {
        document.querySelector(".leave-request").style.display = "block";
        leaveTypeEl.textContent = leaveType;
        dateLeaveEl.textContent = targetDate;
        reasonEl.textContent = reason;
      }

      previewModal.classList.add("show");
    });
  });

  // Close modals
  closeBtns.forEach(btn => {
    btn.addEventListener("click", () => {
      btn.closest(".preview-modal").classList.remove("show");
    });
  });

  // Approve
  document.getElementById("approveRequest").addEventListener("click", () => {
    alert("Request approved! (connect AJAX here)");
    previewModal.classList.remove("show");
  });

  // Decline
  document.getElementById("declineRequest").addEventListener("click", () => {
    previewModal.classList.remove("show");
    declineModal.classList.add("show");
  });

  // Confirm Decline
  document.getElementById("confirmDecline").addEventListener("click", () => {
    const reason = document.getElementById("declineReason").value;
    if (!reason.trim()) {
      alert("Please provide a decline reason.");
      return;
    }
    alert("Declined with reason: " + reason + " (connect AJAX here)");
    declineModal.classList.remove("show");
  });
});

