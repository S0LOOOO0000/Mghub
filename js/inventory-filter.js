// =========================
// inventory-filter.js
// Filtering + Pagination + Search for Inventory
// =========================

document.addEventListener("DOMContentLoaded", () => {
  const rowsPerPage = 10;
  let currentPage = 1;

  const tbody = document.querySelector("table tbody");
  const totalRowsEl = document.getElementById("totalInventoryRows");
  const paginationContainer = document.getElementById("inventoryPagination");
  const searchInput = document.getElementById("searchInventory");

  // Active filters
  const selectedFilters = { status: "all", category: "all", search: "" };

  // Normalize text for matching
  const normalize = (s) => (s || "").trim().toLowerCase().replace(/[_\s]+/g, "-");

  // --------------------
  // Handle Dropdowns
  // --------------------
  document.querySelectorAll(".custom-dropdown .dropdown-menu li").forEach((item) => {
    item.addEventListener("click", () => {
      const value = item.dataset.value.toLowerCase();
      const dropdown = item.closest(".custom-dropdown");
      const toggle = dropdown.querySelector(".dropdown-toggle");

      if (toggle.textContent.includes("Status")) {
        selectedFilters.status = value;
        toggle.innerHTML = `Status: ${item.textContent} <i class="material-icons dropdown-icon">expand_more</i>`;
      } else if (toggle.textContent.includes("Category")) {
        selectedFilters.category = value;
        toggle.innerHTML = `Category: ${item.textContent} <i class="material-icons dropdown-icon">expand_more</i>`;
      }

      currentPage = 1;
      renderTable();
    });
  });

  // --------------------
  // Handle Search
  // --------------------
  if (searchInput) {
    searchInput.addEventListener("input", () => {
      selectedFilters.search = searchInput.value.toLowerCase();
      currentPage = 1;
      renderTable();
    });
  }

  // --------------------
  // Get Filtered Rows
  // --------------------
  function getFilteredRows() {
    return Array.from(tbody.querySelectorAll("tr")).filter((row) => {
      const rowStatus = normalize(row.querySelector(".status")?.textContent);
      const rowCategory = normalize(row.querySelector(".inv-category")?.textContent);
      const rowText = row.innerText.toLowerCase();

      // Debug each row
      console.log("rowCategory:", rowCategory, "selected:", selectedFilters.category);

      return (
        (selectedFilters.status === "all" || rowStatus === selectedFilters.status) &&
        (selectedFilters.category === "all" || rowCategory === selectedFilters.category) &&
        (selectedFilters.search === "" || rowText.includes(selectedFilters.search))
      );
    });
  }

  // --------------------
  // Render Table
  // --------------------
  function renderTable() {
    const filteredRows = getFilteredRows();

    // Hide all rows first
    tbody.querySelectorAll("tr").forEach((row) => (row.style.display = "none"));

    const totalPages = Math.max(1, Math.ceil(filteredRows.length / rowsPerPage));
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;

    // Show only current page rows + reset numbering
    filteredRows.slice(start, end).forEach((row, index) => {
      row.style.display = "";
      const numberCell = row.querySelector("td:first-child");
      if (numberCell) numberCell.textContent = start + index + 1;
    });

    // Update summary text
    totalRowsEl.textContent =
      filteredRows.length === 0
        ? "No items found"
        : `Showing ${start + 1}-${Math.min(end, filteredRows.length)} of ${filteredRows.length} items`;

    renderPagination(totalPages);
  }

  // --------------------
  // Render Pagination
  // --------------------
  function renderPagination(totalPages) {
    paginationContainer.innerHTML = "";

    const makeBtn = (label, disabled, onClick, isActive = false) => {
      const btn = document.createElement("button");
      btn.textContent = label;
      if (disabled) btn.disabled = true;
      if (isActive) btn.classList.add("active");
      btn.addEventListener("click", onClick);
      return btn;
    };

    // Prev
    paginationContainer.appendChild(
      makeBtn("«", currentPage === 1, () => {
        currentPage--;
        renderTable();
      })
    );

    // Pages
    for (let i = 1; i <= totalPages; i++) {
      paginationContainer.appendChild(
        makeBtn(i, false, () => {
          currentPage = i;
          renderTable();
        }, i === currentPage)
      );
    }

    // Next
    paginationContainer.appendChild(
      makeBtn("»", currentPage === totalPages, () => {
        currentPage++;
        renderTable();
      })
    );
  }

  // --------------------
  // Initialize
  // --------------------
  renderTable();

  // Watch for dynamic changes (e.g., AJAX)
  new MutationObserver(renderTable).observe(tbody, { childList: true });
});
