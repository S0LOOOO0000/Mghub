let html5QrCode = null;

// --- Helper Functions (Defined in global scope for access by openQrScanner and other event handlers) ---

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

// Stubs for functions defined later in DOMContentLoaded, needed here by openQrScanner
let populateChangeShiftModal = () => showPopup("System loading...", 'error');
let populateLeaveModal = () => showPopup("System loading...", 'error');


// --- QR Scanner Functions ---

function openQrScanner(action) {
    const modal = document.getElementById("qrModal");
    const title = document.getElementById("qrModalTitle");
    title.textContent = action === "change" ? "Request Change - Scan QR" : "Request Leave - Scan QR";
    modal.style.display = "flex";

    if (html5QrCode) {
        closeQrScanner();
    }
    
    html5QrCode = new Html5Qrcode("qr-reader");

    Html5Qrcode.getCameras()
        .then(cameras => {
            if (cameras.length > 0) {
                html5QrCode.start(
                    cameras[0].id,
                    { fps: 10, qrbox: 250 },
                    qrMessage => {
                        closeQrScanner();
                        showPopup(`QR Scanned: ${qrMessage}. Opening form...`, 'success');
                        if (action === 'change') {
                            populateChangeShiftModal(qrMessage);
                        } else if (action === 'leave') {
                            populateLeaveModal(qrMessage);
                        }
                    },
                    errorMessage => {
                        console.warn("QR Scan error:", errorMessage);
                    }
                );
            }
        })
        .catch(err => console.error("Camera error:", err));
}

function closeQrScanner() {
    const modal = document.getElementById("qrModal");
    modal.style.display = "none";

    if (html5QrCode) {
        const instanceToStop = html5QrCode;
        html5QrCode = null;

        instanceToStop.stop()
            .then(() => instanceToStop.clear())
            .catch(err => console.error("Stop error:", err));
    }
}

document.getElementById("closeQrModal")?.addEventListener("click", closeQrScanner);


// --- DOMContentLoaded Setup ---

document.addEventListener("DOMContentLoaded", () => {
    
    function clearUrlParams() {
        if (window.history.replaceState) {
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
        }
    }

    const modals = {
        changeShift: document.getElementById("changeShiftModal"),
        leaveRequest: document.getElementById("leaveRequestModal"),
        preview: document.getElementById("requestPreviewModal"),
        decline: document.getElementById("declineModal"),
    };

    function openModal(modal) { if (modal) modal.classList.add("show"); }
    function closeModal(modal) { if (modal) modal.classList.remove("show"); }

    Object.values(modals).forEach(modal => {
        if (!modal) return;
        modal.querySelector(".close-btn")?.addEventListener("click", () => closeModal(modal));
        window.addEventListener("click", e => { if (e.target === modal) closeModal(modal); });
    });
    
    // --- Change Shift Modal ---
    populateChangeShiftModal = (empCode) => {
        if (!empCode) {
            showPopup("Missing employee code for change shift request", "error");
            return;
        }

        fetch(`../php/get-target-employees.php?employee_id=${encodeURIComponent(empCode)}`)
            .then(res => res.json())
            .then(data => {
                if (!data || data.status !== "success") {
                    showPopup(data?.message || "Employee not found", "error");
                    return;
                }

                const emp = data.employee;
                document.getElementById("change_request_employee_id").value = emp.employee_code;

                // For now, just fetch targets and print them
                fetch(`../php/get-target-employees.php?employee_id=${encodeURIComponent(emp.employee_code)}`)
                    .then(res => res.json())
                    .then(targets => {
                        console.log("Swap targets:", targets);
                    });
            })
            .catch(err => {
                console.error("Error:", err);
                showPopup("Failed to load employee", "error");
            });

        openModal(modals.changeShift);
    };


    // --- Leave Request Modal ---
    populateLeaveModal = (empCode) => {
        if (!empCode) {
            showPopup("Missing employee code for leave request", "error");
            return;
        }

        fetch(`../php/get-target-employees.php?employee_id=${encodeURIComponent(empCode)}`)
            .then(res => res.json())
            .then(data => {
                if (!data || data.status !== "success") {
                    showPopup(data?.message || "Employee not found", "error");
                    return;
                }

                const emp = data.employee;
                document.getElementById("leave_request_employee_id").value = emp.employee_code;

                console.log("Employee leave request:", emp);

                openModal(modals.leaveRequest);
            })
            .catch(err => {
                console.error("Error:", err);
                showPopup("Failed to load employee", "error");
            });
    };

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

    document.addEventListener("click", e => {
        // Handle table button clicks (extract ID from button/row)
        
        // Shift Change Request (non-QR)
        if (e.target.closest(".request-btn")) {
            const btn = e.target.closest(".request-btn");
            const empId = btn.dataset.employeeId || btn.closest("tr")?.dataset.employeeId;
            populateChangeShiftModal(empId);
        }
        
        // Leave Request (non-QR)
        if (e.target.closest(".leave-btn")) {
            const btn = e.target.closest(".leave-btn");
            const empId = btn.dataset.employeeId || btn.closest("tr")?.dataset.employeeId;
            populateLeaveModal(empId);
        }
        
        // Status Update (no change needed here as logic is contained and uses the button)
        if (e.target.closest(".btn-approve")) updateRequestStatus(e.target.closest(".btn-approve"), "Approved");
        if (e.target.closest(".btn-decline")) updateRequestStatus(e.target.closest(".btn-decline"), "Declined");
    });

    // --- Table Filtering and Pagination Logic ---

    const selectedFilters = { status: "all", shift: "all", station: "all", type: "all" };
    const tbody = document.querySelector("table tbody");
    
    // FIX: Updated element ID to match the HTML (employeeTotalRows)
    const totalRowsEl = document.getElementById("employeeTotalRows"); 
    
    // FIX: Updated element ID to match the HTML (employeePagination)
    const paginationContainer = document.getElementById("employeePagination");
    
    const rowsPerPage = 10;
    let currentPage = 1;

    function getFilteredRows() {
        const rows = Array.from(tbody.querySelectorAll("tr"));
        return rows.filter(row => {
            const rowStatus = row.querySelector(".status")?.textContent.trim().toLowerCase() || "";
            const rowShift = row.querySelector(".shift")?.textContent.trim().toLowerCase() || "";
            const rowStation = row.querySelector("td:nth-child(6)")?.textContent.trim().toLowerCase() || ""; // Changed index to target station/role column
            const rowType = "employee"; // Assuming this table is always showing employees, not a request type

            return (
                (selectedFilters.status === "all" || rowStatus === selectedFilters.status.toLowerCase()) &&
                (selectedFilters.shift === "all" || rowShift === selectedFilters.shift.toLowerCase()) &&
                (selectedFilters.station === "all" || rowStation.includes(selectedFilters.station.toLowerCase())) // Use includes since station/role is combined
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
        
        // FIX: Check if the element exists and update text to reflect "employees"
        if (totalRowsEl) {
            totalRowsEl.textContent = `Showing ${start + 1}-${start + pageRows.length} of ${filteredRows.length} employees`;
        }

        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        if (!paginationContainer) return;

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
    // Added null check for tbody observer in case the table body is also missing
    if (tbody) {
        new MutationObserver(() => renderTable()).observe(tbody, { childList: true });
    }

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has("success")) { showPopup(urlParams.get("success"), "success"); clearUrlParams(); }
    if (urlParams.has("error")) { showPopup(urlParams.get("error"), "error"); clearUrlParams(); }

    const previewModal = document.getElementById("requestPreviewModal");
    const declineModal = document.getElementById("declineModal");

    const closeBtns = document.querySelectorAll(".preview-modal .close-btn");

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

    document.querySelectorAll(".action-btn.preview").forEach(btn => {
        btn.addEventListener("click", () => {
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

            document.querySelector(".change-shift").style.display = "none";
            document.querySelector(".leave-request").style.display = "none";

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

    closeBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            btn.closest(".preview-modal").classList.remove("show");
        });
    });
});
