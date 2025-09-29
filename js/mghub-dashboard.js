// MGHub Dashboard JavaScript
// This file contains functionality specific to the MGHub dashboard

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

// MGHub Dashboard Inventory Filtering
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
