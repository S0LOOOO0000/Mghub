let html5QrCode = null;

// --- Helper Functions ---
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

function openModal(modal) {
    if (modal) modal.classList.add("show");
}
function closeModal(modal) {
    if (modal) modal.classList.remove("show");
}

// --- QR Scanner ---
function openQrScanner(action) {
    const modal = document.getElementById("qrModal");
    const title = document.getElementById("qrModalTitle");
    title.textContent = action === "change" ? "Request Change - Scan QR" : "Request Leave - Scan QR";
    modal.style.display = "flex";

    if (html5QrCode) closeQrScanner();

    html5QrCode = new Html5Qrcode("qr-reader");

    Html5Qrcode.getCameras()
        .then(cameras => {
            if (cameras.length > 0) {
                html5QrCode.start(
                    cameras[0].id,
                    { fps: 10, qrbox: 250 },
                    qrMessage => {
                        closeQrScanner();
                        showPopup(`QR Scanned: ${qrMessage}. Opening form...`, "success");
                        if (action === "change") populateChangeShiftModal(qrMessage);
                        if (action === "leave") populateLeaveModal(qrMessage);
                    },
                    err => console.warn("QR Scan error:", err)
                );
            }
        })
        .catch(err => console.error("Camera error:", err));
}

function closeQrScanner() {
    const modal = document.getElementById("qrModal");
    modal.style.display = "none";
    if (html5QrCode) {
        const instance = html5QrCode;
        html5QrCode = null;
        instance.stop().then(() => instance.clear()).catch(err => console.error("Stop error:", err));
    }
}

document.getElementById("closeQrModal")?.addEventListener("click", closeQrScanner);

// --- Leave Request Submit ---
function submitLeaveRequest(e) {
    e.preventDefault();

    const employeeId = document.getElementById("leave_request_employee_id").value;
    const leaveDate = document.getElementById("leave_date").value;
    const leaveType = document.getElementById("leave_type").value;
    const reason = document.getElementById("leave_reason").value;

    if (!employeeId || !leaveDate || !leaveType || !reason) {
        showPopup("All fields are required", "error");
        return;
    }

    const formData = new FormData();
    formData.append("employee_id", employeeId);
    formData.append("target_date", leaveDate);
    formData.append("leave_type", leaveType);
    formData.append("reason", reason);

    fetch("../php/request-leave.php", { method: "POST", body: formData })
        .then(res => {
            if (!res.ok) throw new Error("Network error");
            return res.text();
        })
        .then(() => {
            showPopup("Leave request submitted", "success");
            closeModal(document.getElementById("leaveRequestModal"));
        })
        .catch(err => {
            console.error("Submit leave error:", err);
            showPopup("Failed to submit leave request", "error");
        });
}

function submitChangeShiftRequest(e) {
    e.preventDefault();

    const employeeId = document.getElementById("change_request_employee_id").value;
    const targetEmployeeId = document.getElementById("target_employee_id").value;
    const targetDate = document.getElementById("change_shift_date").value;
    const reason = document.getElementById("change_shift_reason").value;

    if (!employeeId || !targetEmployeeId || !targetDate || !reason) {
        showPopup("All fields are required", "error");
        return;
    }

    const formData = new FormData();
    formData.append("employee_id", employeeId);
    formData.append("target_employee_id", targetEmployeeId);
    formData.append("target_shift_date", targetDate);
    formData.append("reason", reason);
    formData.append("request_type", "change");

    console.log("FormData being sent:");
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }

    fetch("../php/request-change-shift.php", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                showPopup(data.message, "success");
                closeModal(document.getElementById("changeShiftModal"));
            } else {
                showPopup(data.message, "error");
            }
            console.log("Server response:", data);
        })
        .catch(err => {
            console.error("Submit change shift error:", err);
            showPopup("Failed to submit change shift request", "error");
        });
}

// --- Load All Employees ---
function loadAllEmployees(containerId, hiddenInputId) {
    fetch("../php/fetch-all-employee.php")
        .then(res => {
            if (!res.ok) throw new Error("Network error");
            return res.json().catch(() => {
                return res.text().then(txt => {
                    console.error("Non-JSON response:", txt);
                    throw new Error("Invalid JSON response");
                });
            });
        })
        .then(data => {
            console.log(data);
            if (!data || data.status !== "success") {
                showPopup(data?.message || "Failed to fetch employees", "error");
                return;
            }
            const container = document.getElementById(containerId);
            const hiddenInput = document.getElementById(hiddenInputId);
            if (!container || !hiddenInput) {
                console.error("Dropdown container or hidden input not found");
                return;
            }
            createCustomDropdown(container, hiddenInput, data.employees);
        })
        .catch(err => {
            console.error("Error loading employees:", err);
            showPopup("Error loading employees", "error");
        });
}

// --- Custom Dropdown ---
function createCustomDropdown(container, hiddenInput, employees) {
    container.innerHTML = `
        <div class="cds-selected">-- Select Employee --</div>
        <div class="cds-dropdown">
            <input type="text" class="cds-search" placeholder="Search by name or code...">
            <div class="cds-options"></div>
        </div>
    `;

    const selected = container.querySelector(".cds-selected");
    const dropdown = container.querySelector(".cds-dropdown");
    const searchInput = container.querySelector(".cds-search");
    const optionsContainer = container.querySelector(".cds-options");

    function renderOptions(list) {
        optionsContainer.innerHTML = "";
        list.forEach(emp => {
            const opt = document.createElement("div");
            opt.className = "cds-option";
            opt.innerHTML = `<strong>${emp.first_name} ${emp.last_name}</strong><span>${emp.employee_code}</span>`;
            opt.addEventListener("click", () => {
                selected.textContent = `${emp.first_name} ${emp.last_name} (${emp.employee_code})`;
                hiddenInput.value = emp.employee_id;
                dropdown.style.display = "none";
            });
            optionsContainer.appendChild(opt);
        });
    }

    renderOptions(employees);

    selected.addEventListener("click", () => {
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        searchInput.value = "";
        renderOptions(employees);
        searchInput.focus();
    });

    searchInput.addEventListener("input", () => {
        const val = searchInput.value.toLowerCase();
        const filtered = employees.filter(emp =>
            emp.first_name.toLowerCase().includes(val) ||
            emp.last_name.toLowerCase().includes(val) ||
            emp.employee_code.toLowerCase().includes(val)
        );
        renderOptions(filtered);
    });

    document.addEventListener("click", e => {
        if (!container.contains(e.target)) dropdown.style.display = "none";
    });
}

// --- DOMContentLoaded Setup ---
document.addEventListener("DOMContentLoaded", () => {
    const modals = {
        changeShift: document.getElementById("changeShiftModal"),
        leaveRequest: document.getElementById("leaveRequestModal"),
        preview: document.getElementById("requestPreviewModal"),
        decline: document.getElementById("declineModal"),
    };

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
                console.log(emp);
                document.getElementById("change_request_employee_id").value = emp.employee_id;
                openModal(document.getElementById("changeShiftModal"));
                loadAllEmployees("targetEmployeeDropdown", "target_employee_id");
                const header = document.querySelector("#changeShiftModal h3");
                if (header) header.textContent = `Request Change Shift for ${emp.first_name} ${emp.last_name}`;

                // Attach form submit
                const form = document.querySelector("#changeShiftModal form");
                form.removeEventListener("submit", submitChangeShiftRequest);
                form.addEventListener("submit", submitChangeShiftRequest);
            })
            .catch(err => {
                console.error("Error:", err);
                showPopup("Failed to load employee", "error");
            });
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
                openModal(document.getElementById("leaveRequestModal"));
                const header = document.querySelector("#leaveRequestModal h3");
                if (header) header.textContent = `Request Leave for ${emp.first_name} ${emp.last_name}`;
                const form = document.querySelector("#leaveRequestModal form");
                form.removeEventListener("submit", submitLeaveRequest); // prevent multiple binds
                form.addEventListener("submit", submitLeaveRequest);
            })
            .catch(err => {
                console.error("Error:", err);
                showPopup("Failed to load employee", "error");
            });
    };

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
