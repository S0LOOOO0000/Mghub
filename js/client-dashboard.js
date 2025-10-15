// Client Dashboard JavaScript
// This file contains functionality specific to the client dashboard

const allSideMenuLinks = document.querySelectorAll('.sidebar .side-menu.top li a');

// ✅ Get current file name without query string (# or ?)
const currentPage = window.location.pathname.split('/').pop().split(/[?#]/)[0];

allSideMenuLinks.forEach(link => {
    const hrefPage = link.getAttribute('href').split('/').pop();

    if (hrefPage === currentPage) {
        link.parentElement.classList.add('active');
    } else {
        link.parentElement.classList.remove('active');
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const menuBar = document.querySelector('.icon-menu');
    const sidebar = document.querySelector('.sidebar');

    if (menuBar && sidebar) {
        menuBar.addEventListener('click', () => {
            sidebar.classList.toggle('hide');
        });
    }
});

function adjustSidebar() {
    const sidebar = document.querySelector('.sidebar');
    if (window.innerWidth <= 500) {
        sidebar.classList.add('hide');
        sidebar.classList.remove('show');
    } else {
        sidebar.classList.remove('hide');
        sidebar.classList.add('show');
    }
}

window.addEventListener('load', adjustSidebar);
window.addEventListener('resize', adjustSidebar);

// Search button toggle
const searchButton = document.querySelector('.content nav form .form-input button');
const searchButtonIcon = document.querySelector('.content nav form .form-input button .material-icon');
const searchForm = document.querySelector('.content nav form');

if (searchButton && searchButtonIcon && searchForm) {
    searchButton.addEventListener('click', function (e) {
        if (window.innerWidth < 768) {
            e.preventDefault();
            searchForm.classList.toggle('show');
            if (searchForm.classList.contains('show')) {
                searchButtonIcon.classList.replace('search-icon');
            } else {
                searchButtonIcon.classList.replace('search-icon');
            }
        }
    });
}

// Client Dashboard Bookings Filtering - Using same design as admin dashboard
document.addEventListener('DOMContentLoaded', function() {
    const ROWS_PER_PAGE = 10;
    
    // Helper to normalize strings for comparison
    const normalize = s => (s || "").trim().toLowerCase().replace(/[_\s]+/g, "-");
    
    const tbody = document.querySelector('.table-data .order table tbody');
    const totalRowsEl = document.getElementById('paginationInfo');
    const paginationEl = document.getElementById('bookingPagination');
    const statusFilter = document.querySelector('.table-data .order .filterStatus');
    const typeFilter = document.querySelector('.table-data .order .filterType');
    
    const bookingFilters = { status: "all", type: "all" };

    function getFilteredBookingRows() {
        return Array.from(tbody.querySelectorAll("tr")).filter(row => {
            // Skip empty state row
            if (row.querySelector('td[colspan]')) return false;
            
            const rowStatus = normalize(row.querySelector('.booking-status span')?.textContent);
            const rowType = normalize(row.querySelector('.event-type span')?.textContent);

            return (
                (bookingFilters.status === "all" || rowStatus === bookingFilters.status) &&
                (bookingFilters.type === "all" || 
                 (bookingFilters.type === 'birthday' && rowType.includes('birthday')) ||
                 (bookingFilters.type === 'wedding' && rowType.includes('wedding')) ||
                 (bookingFilters.type === 'corporate' && rowType.includes('corporate')))
            );
        });
    }

    function renderBookingTable(page = 1) {
        const rows = getFilteredBookingRows();
        renderTable(tbody, totalRowsEl, paginationEl, rows, page, ROWS_PER_PAGE, "bookings");
    }

    // Filter event listeners
    if (statusFilter) {
        statusFilter.addEventListener('change', () => {
            bookingFilters.status = statusFilter.value.toLowerCase();
            renderBookingTable();
        });
    }
    
    if (typeFilter) {
        typeFilter.addEventListener('change', () => {
            bookingFilters.type = typeFilter.value.toLowerCase();
            renderBookingTable();
        });
    }

    // Generic table render function (same as admin dashboard)
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
            renderBookingTable(newPage);
        });
    }

    // Pagination helper (same as admin dashboard)
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

    // Initial render
    if (tbody) renderBookingTable();
});

// Todo filtering functionality
function filterTodos(status) {
    const todos = document.querySelectorAll('.todo-list li');
    todos.forEach(todo => {
        if (status === 'all' || (status === 'completed' && todo.classList.contains('completed')) || (status === 'pending' && todo.classList.contains('not-completed'))) {
            todo.style.display = 'flex';
        } else {
            todo.style.display = 'none';
        }
    });
}

// Todo dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    // Handle todo dropdown menus
    const menuIcons = document.querySelectorAll('.todo-actions .menu-icon');
    
    menuIcons.forEach(icon => {
        icon.addEventListener('click', function(e) {
            e.stopPropagation();
            
            // Close all other open dropdowns
            document.querySelectorAll('.content-menu').forEach(menu => {
                if (menu !== this.querySelector('.content-menu')) {
                    menu.style.display = 'none';
                }
            });
            
            // Toggle current dropdown
            const dropdown = this.querySelector('.content-menu');
            if (dropdown) {
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            }
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        document.querySelectorAll('.content-menu').forEach(menu => {
            menu.style.display = 'none';
        });
    });
    
    // Prevent dropdown from closing when clicking inside it
    document.querySelectorAll('.content-menu').forEach(menu => {
        menu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
});

// Progress bar functionality
document.querySelectorAll('.todo-list li').forEach(function(item) {
    var progress = item.getAttribute('data-progress');
    if (progress) {
        item.style.setProperty('--progress-width', progress + '%');
    }
});
