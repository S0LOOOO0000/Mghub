// =========================
// filter.js
// Filtering + Pagination + Search
// =========================

document.addEventListener("DOMContentLoaded", () => {
  const ROWS_PER_PAGE = 10;

  // Helper to normalize strings for comparison
  const normalize = s => (s || "").trim().toLowerCase().replace(/[_\s]+/g, "-");

  // =========================
  // EMPLOYEE TABLE
  // =========================
  const empTbody = document.querySelector("#employeeTable tbody");
  const empTotalRowsEl = document.getElementById("employeeTotalRows");
  const empPaginationEl = document.getElementById("employeePagination");
  const empSearchInput = document.querySelector("#employeeSearch");
  const empFilters = { status: "all", shift: "all", station: "all", search: "" };

  function getFilteredEmployeeRows() {
    return Array.from(empTbody.querySelectorAll("tr")).filter(row => {
      const rowStatus = normalize(row.querySelector(".emp-status span")?.textContent);
      const rowShift = normalize(row.querySelector(".emp-shift .shift")?.textContent);
      const rowStation = normalize(row.querySelector(".emp-station span")?.textContent);
      const rowText = row.innerText.toLowerCase();

      return (
        (empFilters.status === "all" || rowStatus === empFilters.status) &&
        (empFilters.shift === "all" || rowShift === empFilters.shift) &&
        (empFilters.station === "all" || rowStation === empFilters.station) &&
        (empFilters.search === "" || rowText.includes(empFilters.search))
      );
    });
  }

  function renderEmployeeTable(page = 1) {
    const rows = getFilteredEmployeeRows();
    renderTable(empTbody, empTotalRowsEl, empPaginationEl, rows, page, ROWS_PER_PAGE, "employees");
  }

  // =========================
  // ATTENDANCE TABLE
  // =========================
  const attTbody = document.querySelector("#attendanceTable tbody");
  const attTotalRowsEl = document.getElementById("attendanceTotalRows");
  const attPaginationEl = document.getElementById("attendancePagination");
  const attSearchInput = document.querySelector("#attendanceSearch");
  const attendanceFilters = { status: "all", shift: "all", station: "all", search: "" };

  function getFilteredAttendanceRows() {
    return Array.from(attTbody.querySelectorAll("tr")).filter(row => {
      const rowStatus = normalize(row.querySelector(".status span")?.textContent);
      const rowShift = normalize(row.querySelector(".emp-shift .shift")?.textContent);
      const rowStation = normalize(row.querySelector(".emp-station span")?.textContent);
      const rowText = row.innerText.toLowerCase();

      return (
        (attendanceFilters.status === "all" || rowStatus === attendanceFilters.status) &&
        (attendanceFilters.shift === "all" || rowShift === attendanceFilters.shift) &&
        (attendanceFilters.station === "all" || rowStation === attendanceFilters.station) &&
        (attendanceFilters.search === "" || rowText.includes(attendanceFilters.search))
      );
    });
  }

  function renderAttendanceTable(page = 1) {
    const rows = getFilteredAttendanceRows();
    renderTable(attTbody, attTotalRowsEl, attPaginationEl, rows, page, ROWS_PER_PAGE, "records");
  }

  // =========================
  // REQUEST TABLE
  // =========================
  const reqTbody = document.querySelector("#requestTable tbody");
  const reqTotalRowsEl = document.getElementById("requestTotalRows");
  const reqPaginationEl = document.getElementById("requestPagination");
  const reqSearchInput = document.querySelector("#requestSearch");
  const requestFilters = { status: "all", type: "all", station: "all", search: "" };

  function getFilteredRequestRows() {
    return Array.from(reqTbody.querySelectorAll("tr")).filter(row => {
      const rowStatus = normalize(row.querySelector(".req-status span")?.textContent);
      const rowType = normalize(row.querySelector(".req-type span")?.textContent);
      const rowStation = normalize(row.querySelector(".req-station span")?.textContent);
      const rowText = row.innerText.toLowerCase();

      return (
        (requestFilters.status === "all" || rowStatus === requestFilters.status) &&
        (requestFilters.type === "all" || rowType === requestFilters.type) &&
        (requestFilters.station === "all" || rowStation === requestFilters.station) &&
        (requestFilters.search === "" || rowText.includes(requestFilters.search))
      );
    });
  }

  function renderRequestTable(page = 1) {
    const rows = getFilteredRequestRows();
    renderTable(reqTbody, reqTotalRowsEl, reqPaginationEl, rows, page, ROWS_PER_PAGE, "requests");
  }

  // =========================
  // GENERIC TABLE RENDER FUNCTION
  // =========================
  function renderTable(tbody, totalEl, paginationEl, rows, page, rowsPerPage, label) {
    tbody.querySelectorAll("tr").forEach(row => row.style.display = "none");

    const totalPages = Math.max(1, Math.ceil(rows.length / rowsPerPage));
    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;

    rows.slice(start, end).forEach((row, idx) => {
      row.style.display = "";
      const numberCell = row.querySelector("td:first-child");
      if (numberCell) numberCell.textContent = start + idx + 1;
    });

    totalEl.textContent =
      rows.length === 0
        ? `No ${label} found`
        : `Showing ${start + 1}-${Math.min(end, rows.length)} of ${rows.length} ${label}`;

    renderPagination(paginationEl, totalPages, page, (newPage) => {
    if (label === "employees") renderEmployeeTable(newPage);
    else if (label === "records") renderAttendanceTable(newPage);
    else renderRequestTable(newPage);
    });

  }

  // =========================
  // DROPDOWN HANDLER
  // =========================
  document.querySelectorAll(".custom-dropdown").forEach(dropdown => {
    const filterType = dropdown.dataset.filter;
    const toggle = dropdown.querySelector(".dropdown-toggle");

    dropdown.querySelectorAll(".dropdown-menu li").forEach(item => {
      item.addEventListener("click", () => {
        const value = item.dataset.value.toLowerCase();

        // Employee filters
        if (filterType === "employee-status") empFilters.status = value;
        else if (filterType === "employee-shift") empFilters.shift = value;
        else if (filterType === "employee-station") empFilters.station = value;

        // Attendance filters
        else if (filterType === "attendance-status") attendanceFilters.status = value;
        else if (filterType === "attendance-shift") attendanceFilters.shift = value;
        else if (filterType === "attendance-station") attendanceFilters.station = value;

        // Request filters
        else if (filterType === "request-status") requestFilters.status = value;
        else if (filterType === "request-type") requestFilters.type = value;
        else if (filterType === "request-station") requestFilters.station = value;

        toggle.innerHTML = `${toggle.textContent.split(":")[0]}: ${item.textContent} <i class="material-icons dropdown-icon">expand_more</i>`;

        if (filterType.startsWith("employee")) renderEmployeeTable();
        else if (filterType.startsWith("attendance")) renderAttendanceTable();
        else renderRequestTable();
      });
    });
  });

  // =========================
  // SEARCH HANDLER
  // =========================
  if (empSearchInput) empSearchInput.addEventListener("input", () => {
    empFilters.search = empSearchInput.value.toLowerCase();
    renderEmployeeTable();
  });

  if (attSearchInput) attSearchInput.addEventListener("input", () => {
    attendanceFilters.search = attSearchInput.value.toLowerCase();
    renderAttendanceTable();
  });

  if (reqSearchInput) reqSearchInput.addEventListener("input", () => {
    requestFilters.search = reqSearchInput.value.toLowerCase();
    renderRequestTable();
  });

  // =========================
  // PAGINATION HELPER
  // =========================
function renderPagination(container, totalPages, currentPage, onClick) {
    container.innerHTML = "";

    const createButton = (label, disabled = false, pageNum = null, active = false) => {
        const btn = document.createElement("button");
        btn.textContent = label;
        if (disabled) btn.disabled = true;
        if (active) btn.classList.add("active");
        if (pageNum !== null) {
            btn.addEventListener("click", () => onClick(pageNum));
        }
        return btn;
    };

    // Prev button
    container.appendChild(createButton("«", currentPage === 1, currentPage - 1));

    // Number buttons
    for (let i = 1; i <= totalPages; i++) {
        container.appendChild(createButton(i, false, i, i === currentPage));
    }

    // Next button
    container.appendChild(createButton("»", currentPage === totalPages, currentPage + 1));
}



  // =========================
  // INITIAL RENDER
  // =========================
  if (empTbody) renderEmployeeTable();
  if (attTbody) renderAttendanceTable();
  if (reqTbody) renderRequestTable();

  // =========================
  // OBSERVERS
  // =========================
  if (empTbody) new MutationObserver(() => renderEmployeeTable()).observe(empTbody, { childList: true });
  if (attTbody) new MutationObserver(() => renderAttendanceTable()).observe(attTbody, { childList: true });
  if (reqTbody) new MutationObserver(() => renderRequestTable()).observe(reqTbody, { childList: true });

});


  // =========================
  // BOOKING REQUEST TABLE
  // =========================
  const bookTbody = document.querySelector("#bookingTable tbody");
  const bookTotalRowsEl = document.getElementById("bookingTotalRows");
  const bookPaginationEl = document.getElementById("bookingPagination");
  const bookSearchInput = document.querySelector("#bookingSearch");
  const bookingFilters = { search: "" };

  function getFilteredBookingRows() {
    return Array.from(bookTbody.querySelectorAll("tr")).filter(row => {
      const rowText = row.innerText.toLowerCase();
      return bookingFilters.search === "" || rowText.includes(bookingFilters.search);
    });
  }

  function renderBookingTable(page = 1) {
    const rows = getFilteredBookingRows();
    renderTable(bookTbody, bookTotalRowsEl, bookPaginationEl, rows, page, ROWS_PER_PAGE, "requests");
  }

  if (bookSearchInput) bookSearchInput.addEventListener("input", () => {
    bookingFilters.search = bookSearchInput.value.toLowerCase();
    renderBookingTable();
  });

  if (bookTbody) renderBookingTable();
  if (bookTbody) new MutationObserver(() => renderBookingTable()).observe(bookTbody, { childList: true });
