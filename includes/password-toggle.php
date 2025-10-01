<?php
/**
 * Universal Password Toggle Component
 * 
 * This component provides a reusable show/hide password functionality
 * that can be applied to any password input field in the application.
 * 
 * Features:
 * - Toggle between password visibility states
 * - Eye icon that changes based on visibility state
 * - Positioned beside the password input field
 * - Uses query selectors for universal application
 * 
 * Usage:
 * 1. Include this file in your HTML
 * 2. Add the CSS classes for styling
 * 3. Call initPasswordToggle() function after DOM is loaded
 * 4. Add data-password-toggle attribute to password input containers
 * 
 * Author: MGCAFE Development Team
 * Date: <?php echo date('Y-m-d'); ?>
 */
?>

<!-- Material Icons for eye icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<style>
.password-container {
    position: relative;
    display: flex;
    align-items: center;
}

.password-toggle-btn {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
    color: #6b7280;
    transition: color 0.2s ease;
    z-index: 10;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.password-toggle-btn:hover {
    color: #0072ff;
}

.password-toggle-btn:focus {
    outline: none;
    color: #0072ff;
}

/* Material Design eye icons */
.password-toggle-btn {
    font-family: 'Material Icons';
    font-size: 20px;
    line-height: 1;
}

.password-toggle-btn.show::before {
    content: "visibility_off";
}

.password-toggle-btn.hide::before {
    content: "visibility";
}
</style>

<script>
/**
 * Initialize password toggle functionality
 * This function should be called after the DOM is fully loaded
 */
function initPasswordToggle() {
    // Find all password input containers with toggle functionality
    const passwordContainers = document.querySelectorAll('[data-password-toggle]');
    
    passwordContainers.forEach(container => {
        const passwordInput = container.querySelector('input[type="password"], input[type="text"]');
        
        if (passwordInput) {
            // Add password-container class for styling
            container.classList.add('password-container');
            
            // Create toggle button
            const toggleBtn = document.createElement('button');
            toggleBtn.type = 'button';
            toggleBtn.className = 'password-toggle-btn hide';
            toggleBtn.setAttribute('aria-label', 'Toggle password visibility');
            toggleBtn.setAttribute('title', 'Show/Hide Password');
            
            // Add click event listener
            toggleBtn.addEventListener('click', function() {
                togglePasswordVisibility(passwordInput, toggleBtn);
            });
            
            // Insert toggle button into container
            container.appendChild(toggleBtn);
        }
    });
}

/**
 * Toggle password visibility for a specific input field
 * @param {HTMLInputElement} passwordInput - The password input field
 * @param {HTMLButtonElement} toggleBtn - The toggle button
 */
function togglePasswordVisibility(passwordInput, toggleBtn) {
    if (passwordInput.type === 'password') {
        // Show password
        passwordInput.type = 'text';
        toggleBtn.className = 'password-toggle-btn show';
        toggleBtn.setAttribute('aria-label', 'Hide password');
        toggleBtn.setAttribute('title', 'Hide Password');
    } else {
        // Hide password
        passwordInput.type = 'password';
        toggleBtn.className = 'password-toggle-btn hide';
        toggleBtn.setAttribute('aria-label', 'Show password');
        toggleBtn.setAttribute('title', 'Show Password');
    }
}

/**
 * Auto-initialize when DOM is loaded
 * This ensures the password toggle works across all pages
 */
document.addEventListener('DOMContentLoaded', function() {
    initPasswordToggle();
});
</script>
