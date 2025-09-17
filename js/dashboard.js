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
})



function updatePresentToday() {
  fetch('../php/get-attendance-dashboard.php') // ✅ correct endpoint
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        document.getElementById('presentToday').textContent = data.present_today;
      }
    })
    .catch(err => console.error('Error fetching attendance:', err));
}

// Initial load
updatePresentToday();

// Auto refresh every 30 seconds
setInterval(updatePresentToday, 30000);




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

document.getElementById('searchUser').addEventListener('input', filterOrders);
document.getElementById('filterStatus').addEventListener('change', filterOrders);

function filterOrders() {
    const searchText = document.getElementById('searchUser').value.toLowerCase();
    const statusFilter = document.getElementById('filterStatus').value;

    document.querySelectorAll('.order table tbody tr').forEach(row => {
        const user = row.querySelector('td:nth-child(2) span').textContent.toLowerCase();
        const status = row.querySelector('td:nth-child(4) .status').textContent.toLowerCase();

        const matchesSearch = user.includes(searchText);
        const matchesStatus = statusFilter === 'all' || status === statusFilter;

        if (matchesSearch && matchesStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

