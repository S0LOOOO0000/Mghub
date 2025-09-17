document.addEventListener("DOMContentLoaded", () => {

  // ---------- Helper Functions ----------
  function closeAllDropdowns(except = null, type = "all") {
    // General dropdowns (notifications, profile, custom dropdowns)
    if (type === "all" || type === "general") {
      document.querySelectorAll('.custom-dropdown.active, .notification-menu.show, .profile-menu.show')
        .forEach(dd => {
          if (dd !== except) {
            dd.classList.remove('active', 'show');
          }
        });
    }

    // Icon-based dropdowns (icon-circle, attendance-icon)
    if (type === "all" || type === "icon") {
      document.querySelectorAll('.floating-dropdown').forEach(fd => fd.remove());
      document.querySelectorAll('.icon-circle, .attendance-icon').forEach(ic => ic.dataset.open = "false");
    }
  }

  function toggleMenu(menu) {
    if (menu.classList.contains('active') || menu.classList.contains('show')) {
      menu.classList.remove('active', 'show');
    } else {
      menu.classList.add('active', 'show');
    }
  }

  // ---------- Notification ----------
  const notificationIcon = document.getElementById('notificationIcon');
  const notificationMenu = document.getElementById('notificationMenu');
  if (notificationIcon && notificationMenu) {
    notificationIcon.addEventListener('click', e => {
      e.stopPropagation();
      toggleMenu(notificationMenu);
      closeAllDropdowns(notificationMenu, "general");
    });
  }

  // ---------- Profile ----------
  const profileIcon = document.getElementById('profileIcon');
  const profileMenu = document.getElementById('profileMenu');
  if (profileIcon && profileMenu) {
    profileIcon.addEventListener('click', e => {
      e.stopPropagation();
      toggleMenu(profileMenu);
      closeAllDropdowns(profileMenu, "general");
    });
  }

  // ---------- Custom Dropdowns ----------
  document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
    const toggleBtn = dropdown.querySelector('.dropdown-toggle');
    if (!toggleBtn) return;

    toggleBtn.addEventListener('click', e => {
      e.stopPropagation();
      const isActive = dropdown.classList.contains('active');
      closeAllDropdowns(null, "general");
      if (!isActive) dropdown.classList.add('active');
    });
  });

  // ---------- Delegated Handler for Icon-Circle & Attendance ----------
  document.addEventListener("click", e => {
    const toggleBtn = e.target.closest('.icon-circle .dropdown-toggle, .attendance-icon .dropdown-toggle');
    if (!toggleBtn) return;

    e.stopPropagation();
    const container = toggleBtn.closest('.icon-circle, .attendance-icon');
    const isActive = container.dataset.open === "true";
    closeAllDropdowns(null, "icon");

    if (!isActive) {
      container.dataset.open = "true";

      // Clone menu as floating
      const menu = container.querySelector('.dropdown-menu');
      if (!menu) return;

      const rect = container.getBoundingClientRect();
      const menuClone = menu.cloneNode(true);
      menuClone.classList.add('floating-dropdown');
      document.body.appendChild(menuClone);

      // Vertical positioning
      const spaceBelow = window.innerHeight - rect.bottom;
      const spaceAbove = rect.top;
      menuClone.style.top = (spaceBelow < menuClone.offsetHeight && spaceAbove > menuClone.offsetHeight)
                            ? rect.top - menuClone.offsetHeight + "px"
                            : rect.bottom + "px";

      // Horizontal positioning
      const spaceRight = window.innerWidth - rect.right;
      const spaceLeft = rect.left;
      menuClone.style.left = (spaceRight < menuClone.offsetWidth && spaceLeft > menuClone.offsetWidth)
                            ? rect.right - menuClone.offsetWidth + "px"
                            : rect.left + "px";

      // Close when clicking outside
      function handleClickOutside(event) {
        if (!menuClone.contains(event.target) && !container.contains(event.target)) {
          menuClone.remove();
          container.dataset.open = "false";
          document.removeEventListener("click", handleClickOutside);
        }
      }
      document.addEventListener("click", handleClickOutside);
    }
  });

  // ---------- Global Close ----------
  window.addEventListener('click', () => closeAllDropdowns());
});
