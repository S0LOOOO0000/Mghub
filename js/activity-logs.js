// =========================
// activity-logs.js
// Filtering + Pagination for Activity Logs
// =========================

document.addEventListener("DOMContentLoaded", () => {
    const rowsPerPage = 10;

    // Table configurations
    const tables = [
        {
            id: "inventoryLogsTable",
            paginationId: "paginationInventoryLogs",
            totalRowsId: "totalInventoryLogs",
            currentPage: 1,
            filters: { role: "all", action: "all" }
        },
        {
            id: "eventLogsTable",
            paginationId: "paginationEventLogs",
            totalRowsId: "totalEventLogs",
            currentPage: 1,
            filters: { role: "all", action: "all" }
        }
    ];

    // --------------------
    // Dropdown Filter Logic
    // --------------------
    document.querySelectorAll(".custom-dropdown .dropdown-menu li").forEach(item => {
        item.addEventListener("click", () => {
            const value = item.dataset.value.toLowerCase();
            const dropdown = item.closest(".custom-dropdown");
            const toggle = dropdown.querySelector(".dropdown-toggle");
            const isRoleDropdown = toggle.textContent.includes("Role");

            // Update the display text of the dropdown toggle
            toggle.innerHTML = `${isRoleDropdown ? 'Role' : 'Action'}: ${item.textContent} <i class="material-icons dropdown-icon">expand_more</i>`;

            // Update filters for both tables
            tables.forEach(table => {
                if (isRoleDropdown) {
                    table.filters.role = value;
                } else {
                    table.filters.action = value;
                }
                table.currentPage = 1; // Reset to first page after filtering
                renderTable(table);
            });
        });
    });

    // --------------------
    // Search Filter Logic
    // --------------------
    function setupLiveSearch(inputId, tableConfig) {
        const searchInput = document.getElementById(inputId);
        if (!searchInput) return;

        searchInput.addEventListener("input", () => {
            tableConfig.search = searchInput.value.toLowerCase();
            tableConfig.currentPage = 1; // Reset to first page on search
            renderTable(tableConfig);
        });
    }

    // --------------------
    // Rendering Function
    // --------------------
    function renderTable(tableConfig) {
        const tableEl = document.getElementById(tableConfig.id);
        if (!tableEl) {
            console.error(`Table element with id '${tableConfig.id}' not found`);
            return;
        }
        
        const tbody = tableEl.querySelector("tbody");
        if (!tbody) {
            console.error(`Table body not found in table '${tableConfig.id}'`);
            return;
        }
        
        const allRows = Array.from(tbody.querySelectorAll("tr"));
        
        const filteredRows = allRows.filter(row => {
            const rowRole = row.cells[1]?.textContent.trim().toLowerCase();
            const rowAction = row.cells[2]?.textContent.trim().toLowerCase();
            const rowText = row.innerText.toLowerCase();
            
            return (
                (tableConfig.filters.role === "all" || rowRole === tableConfig.filters.role) &&
                (tableConfig.filters.action === "all" || rowAction === tableConfig.filters.action) &&
                (!tableConfig.search || rowText.includes(tableConfig.search))
            );
        });

        // Hide all rows initially
        allRows.forEach(row => row.style.display = "none");

        const totalRows = filteredRows.length;
        const totalPages = Math.max(1, Math.ceil(totalRows / rowsPerPage));
        
        // Ensure current page is valid
        if (tableConfig.currentPage > totalPages) {
            tableConfig.currentPage = totalPages;
        }

        const start = (tableConfig.currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        filteredRows.slice(start, end).forEach((row, index) => {
            row.style.display = ""; // Show the row
            row.querySelector("td:first-child").textContent = start + index + 1;

            // Ensure the activity-log-row class is preserved
            if (!row.classList.contains('activity-log-row')) {
                row.classList.add('activity-log-row');
            }

            // Apply badges
            const roleCell = row.cells[1];
            const actionCell = row.cells[2];
            if (roleCell && !roleCell.querySelector('.badge')) {
                const roleText = roleCell.textContent.trim().toLowerCase();
                roleCell.innerHTML = `<span class="badge role-${roleText}">${roleCell.textContent}</span>`;
            }
            if (actionCell && !actionCell.querySelector('.badge')) {
                const actionText = actionCell.textContent.trim().toLowerCase();
                actionCell.innerHTML = `<span class="badge action-${actionText}">${actionCell.textContent}</span>`;
            }
        });

        // Update total rows display
        const totalRowsEl = document.getElementById(tableConfig.totalRowsId);
        totalRowsEl.textContent = totalRows === 0 ? "No logs found" : `Showing ${start + 1}-${Math.min(end, totalRows)} of ${totalRows} items`;

        renderPagination(tableConfig, totalPages);
    }

    // --------------------
    // Pagination Rendering
    // --------------------
    function renderPagination(tableConfig, totalPages) {
        const paginationContainer = document.getElementById(tableConfig.paginationId);
        paginationContainer.innerHTML = "";
        
        if (totalPages <= 1) return;

        const makeBtn = (label, isDisabled, isActive = false) => {
            const btn = document.createElement("button");
            btn.textContent = label;
            btn.disabled = isDisabled;
            if (isActive) btn.classList.add("active");
            return btn;
        };

        const prevBtn = makeBtn("«", tableConfig.currentPage === 1);
        prevBtn.addEventListener("click", () => {
            tableConfig.currentPage--;
            renderTable(tableConfig);
        });
        paginationContainer.appendChild(prevBtn);

        for (let i = 1; i <= totalPages; i++) {
            const pageBtn = makeBtn(i, false, i === tableConfig.currentPage);
            pageBtn.addEventListener("click", () => {
                tableConfig.currentPage = i;
                renderTable(tableConfig);
            });
            paginationContainer.appendChild(pageBtn);
        }

        const nextBtn = makeBtn("»", tableConfig.currentPage === totalPages);
        nextBtn.addEventListener("click", () => {
            tableConfig.currentPage++;
            renderTable(tableConfig);
        });
        paginationContainer.appendChild(nextBtn);
    }

    // --------------------
    // Activity Log Modal Functionality
    // --------------------
    function initializeActivityLogModal() {
        const modal = document.getElementById('activityLogModal');
        const closeModal = document.getElementById('closeActivityModal');
        const closeModalBtn = document.getElementById('closeActivityModalBtn');
        
        // Modal initialization
        
        // Close modal functions
        function closeModalFunc() {
            modal.style.display = 'none';
        }
        
        if (closeModal) {
            closeModal.addEventListener('click', closeModalFunc);
        }
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', closeModalFunc);
        }
        
        // Close modal when clicking outside
        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModalFunc();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && modal.style.display === 'block') {
                closeModalFunc();
            }
        });
    }
    
    // --------------------
    // Activity Log Row Click Handler
    // --------------------
    function setupActivityLogRowClicks() {
        document.addEventListener('click', (event) => {
            const row = event.target.closest('.activity-log-row');
            
            if (row) {
                const modal = document.getElementById('activityLogModal');
                
                if (modal) {
                    // Populate modal with row data
                    const logId = row.dataset.logId || '-';
                    const role = row.dataset.role || '-';
                    const action = row.dataset.action || '-';
                    const date = row.dataset.date || '-';
                    const time = row.dataset.time || '-';
                    const details = row.dataset.details || 'No details available';
                    
                    document.getElementById('modalLogId').textContent = logId;
                    document.getElementById('modalRole').textContent = role;
                    document.getElementById('modalAction').textContent = action;
                    document.getElementById('modalDate').textContent = date;
                    document.getElementById('modalTime').textContent = time;
                    document.getElementById('modalDetails').textContent = details;
                    
                    // Show modal
                    modal.style.display = 'block';
                } else {
                    console.error('Modal element not found!');
                }
            }
        });
    }

    // --------------------
    // Initialize
    // --------------------
    tables.forEach(table => {
        // Initialize the table and its associated search
        setupLiveSearch(table.id.replace('Table', 'LogSearch'), table);
        renderTable(table);
    });
    
    // Initialize modal functionality
    initializeActivityLogModal();
    setupActivityLogRowClicks();
    
    // Test modal functionality
    console.log('Testing modal functionality...');
    const testModal = document.getElementById('activityLogModal');
    if (testModal) {
        console.log('Modal found and ready');
    } else {
        console.error('Modal not found in DOM!');
    }
});