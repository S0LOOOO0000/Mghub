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


// Arama butonunu toggle etme
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



// Menülerin açılıp kapanması için fonksiyon
    function toggleMenu(menuId) {
      var contentMenu = document.getElementById(menuId);
      var allMenus = document.querySelectorAll('.content-menu');

      // Diğer tüm menüleri kapat
      allMenus.forEach(function(m) {
        if (m !== contentMenu) {
          m.style.display = 'none';
        }
      });

      // Tıklanan menü varsa aç, yoksa kapat
      if (contentMenu.style.display === 'none' || contentMenu.style.display === '') {
        contentMenu.style.display = 'block';
      } else {
        contentMenu.style.display = 'none';
      }
    }

    // Başlangıçta tüm menüleri kapalı tut
    document.addEventListener("DOMContentLoaded", function() {
      var allMenus = document.querySelectorAll('.content-menu');
      allMenus.forEach(function(contentMenu) {
        contentMenu.style.display = 'none';
      });
    });
	
document.querySelectorAll('.todo-list li').forEach(function(item) {
    var progress = item.getAttribute('data-progress'); // 'data-progress' attribute'u alınıyor
    item.style.setProperty('--progress-width', progress + '%'); // Dinamik olarak CSS değişkeni ayarlanıyor
});	

document.querySelectorAll('.menu-icon').forEach(function(icon) {
    icon.addEventListener('click', function(e) {
        // Menü öğesinin görünürlük durumunu değiştir
        var menu = icon.querySelector('.content-menu');
        var isVisible = menu.style.display === 'block';
        
        // Diğer menüler kapalıysa sadece tıklanan menüyü aç
        document.querySelectorAll('.content-menu').forEach(function(otherMenu) {
            if (otherMenu !== menu) {
                otherMenu.style.display = 'none';
            }
        });
        
        // Menü görünürse gizle, değilse göster
        menu.style.display = isVisible ? 'none' : 'block';
        
        // Tıklama olayının başka yerlere yayılmasını engelle
        e.stopPropagation();
    });
});

// Menü dışında bir yere tıklanınca menüyü kapatma
document.addEventListener('click', function() {
    document.querySelectorAll('.content-menu').forEach(function(menu) {
        menu.style.display = 'none';
    });
});
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

document.querySelectorAll('.notification-menu li').forEach(notification => {
    notification.addEventListener('click', function() {
        this.classList.add('read');
        updateNotificationCount();
    });
});

function updateNotificationCount() {
    const unreadNotifications = document.querySelectorAll('.notification-menu li:not(.read)').length;
    document.querySelector('.notification .num').textContent = unreadNotifications;
}

// Admin Dashboard Requests Filtering
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.querySelector('.table-data .order .filterStatus');
    const typeFilter = document.querySelector('.table-data .order .filterType');
    
    if (statusFilter) {
        statusFilter.addEventListener('change', filterAdminRequests);
    }
    if (typeFilter) {
        typeFilter.addEventListener('change', filterAdminRequests);
    }
});

function filterAdminRequests() {
    const statusFilter = document.querySelector('.table-data .order .filterStatus')?.value || 'all';
    const typeFilter = document.querySelector('.table-data .order .filterType')?.value || 'all';

    document.querySelectorAll('.table-data .order table tbody tr').forEach(row => {
        // Skip empty state row
        if (row.querySelector('td[colspan]')) return;
        
        const statusCell = row.querySelector('td:nth-child(9) .status');
        const typeCell = row.querySelector('td:nth-child(5) span');
        
        if (!statusCell || !typeCell) return;
        
        const status = statusCell.textContent.toLowerCase().trim();
        const type = typeCell.textContent.toLowerCase().trim();
        
        const matchesStatus = statusFilter === 'all' || status === statusFilter;
        const matchesType = typeFilter === 'all' || 
                           (typeFilter === 'on_leave' && type === 'on leave') ||
                           (typeFilter === 'change_shift' && type === 'change shift');

        if (matchesStatus && matchesType) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}


document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('previewRequestModal');
    const closeBtn = modal?.querySelector('.close-btn');

    // Use event delegation for dynamically generated rows
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-view');
        if (!btn) return;

        const row = btn.closest('tr');
        if (!row) return;

        // Get modal fields safely
        const employee = row.querySelector('td:nth-child(2) strong')?.textContent.trim() || '';
        const email = row.querySelector('td:nth-child(2) p')?.textContent.trim() || '';
        const station = row.querySelector('td.req-station span')?.textContent.trim() || '';
        const role = row.querySelector('td.req-station p')?.textContent.trim() || '';
        const shift = row.querySelector('td.emp-shift')?.textContent.trim() || '';
        const type = row.querySelector('td.req-type span')?.textContent.trim() || '';
        const details = row.querySelector('td:nth-child(6)')?.textContent.trim() || '';
        const reason = row.querySelector('td:nth-child(7)')?.textContent.trim() || '';
        const targetDate = row.querySelector('td:nth-child(8)')?.textContent.trim() || '';
        const status = row.querySelector('td.req-status span')?.textContent.trim() || '';

        // Fill modal fields
        document.getElementById('preview_employee_name').textContent = employee;
        document.getElementById('preview_employee_email').textContent = email;
        document.getElementById('preview_station_role').textContent = `${station} • ${role}`;
        document.getElementById('preview_shift').textContent = shift;
        document.getElementById('preview_request_type_text').textContent = type;
        document.getElementById('preview_request_details').textContent = details;
        document.getElementById('preview_request_reason').textContent = reason;
        document.getElementById('preview_target_date').textContent = targetDate;
        document.getElementById('preview_request_status').textContent = status;

        // Show modal
        modal.classList.add('show');
    });

    // Close modal
    closeBtn?.addEventListener('click', () => {
        modal.classList.remove('show');
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('show');
        }
    });
});

document.addEventListener("DOMContentLoaded", function() {
    function initializePagination() {
        const rows = document.querySelectorAll('.table-data .order table tbody tr');
        if (!rows.length) return;

        const itemsPerPage = 5;
        let currentPage = 1;
        const totalPages = Math.ceil(rows.length / itemsPerPage);

        const prevBtn = document.getElementById('prevPage');
        const nextBtn = document.getElementById('nextPage');
        const currentPageSpan = document.getElementById('currentPage');
        const paginationInfo = document.getElementById('paginationInfo');

        function updatePagination() {
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;

            rows.forEach((row, index) => {
                row.style.display = (index >= startIndex && index < endIndex) ? '' : 'none';
            });

            currentPageSpan.textContent = currentPage;
            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage === totalPages;

            const startItem = startIndex + 1;
            const endItem = Math.min(endIndex, rows.length);
            paginationInfo.textContent = `Showing ${startItem}-${endItem} of ${rows.length} requests`;
        }

        prevBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                updatePagination();
            }
        });

        nextBtn.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                updatePagination();
            }
        });

        updatePagination();
    }

    initializePagination();
});



// Inventory filtering functionality
function filterInventory(status) {
    const items = document.querySelectorAll('.inventory-item');
    const buttons = document.querySelectorAll('.inventory-filters button');
    
    // Update active button
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    // Filter items with animation
    items.forEach((item, index) => {
        const itemStatus = item.dataset.status;
        
        setTimeout(() => {
            if (status === 'all' || 
                (status === 'low' && itemStatus === 'low_stock') ||
                (status === 'out' && itemStatus === 'out_of_stock')) {
                item.style.display = 'flex';
                item.style.opacity = '0';
                setTimeout(() => {
                    item.style.opacity = '1';
                }, 50);
            } else {
                item.style.opacity = '0';
                setTimeout(() => {
                    item.style.display = 'none';
                }, 200);
            }
        }, index * 50);
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

