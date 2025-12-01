<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="../css/components/sidebar.css">
<link rel="stylesheet" href="../css/components/account-modal.css">
<script src="../js/account-modal.js"></script>

 <!-- SIDEBAR -->

        <a class="brand">
            <i class="material-icons icon-logo">coffee</i>
            <span class="text title">MG Admin</span>
        </a>
        <ul class="side-menu top">
            <li>
                <a href="../admin/admin-dashboard.php">
                    <i class="material-icons icon-btn">dashboard</i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="../admin/admin-employees.php">
                    <i class="material-icons icon-btn">group</i>
                    <span class="text">Employees</span>
                </a>
            </li>
            <li>
                <a href="../admin/admin-inventory.php">
                    <i class="material-icons icon-btn">inventory_2</i>
                    <span class="text">Inventory</span>
                </a>
            </li>
            <li>
                <a href="../admin/admin-bookings.php">
                    <i class="material-icons icon-btn">event</i>
                    <span class="text">Bookings</span>
                </a>
            </li>
            <li>
                <a href="../admin/admin-booking-approval.php">
                    <i class="material-icons icon-btn">event_available</i>
                    <span class="text">Booking Approval</span>
                </a>
            </li>
            <li>
                <a href="../admin/admin-activity-logs.php">
                    <i class="material-icons icon-btn">history</i>
                    <span class="text">Activity Logs</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu bottom">
            <li>
                <a class="logout" id="accountBtn">
                    <i class="material-icons icon-btn">person</i>
                    <span class="text">Account</span>
                </a>
            </li>
            <li>
                <a href="../logout.php" class="logout">
                    <i class="material-icons icon-btn">logout</i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>


    <div class="modal-overlay" id="accountModal">
        <div class="user-account-modal">
            <h2 class="modal-title">Change Password</h2>
            <p class="modal-description">
                To change your password, please enter your current password and then provide a new one. 
                Your new password must be at least 8 characters long and confirmed in the field below.
            </p>

            <div class="input-group">
                <input type="password" id="currentPassword" name="currentPassword" placeholder=" " required>
                <label for="currentPassword">Current Password</label>
            </div>

            <div class="input-group">
                <input type="password" id="newPassword" name="newPassword" placeholder=" " required minlength="8">
                <label for="newPassword">New Password</label>
            </div>

            <div class="input-group">
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder=" " required minlength="8">
                <label for="confirmPassword">Confirm Password</label>
            </div>

            <p class="modal-alert" id="alert-text">Your new password must be at least 8 characters long.</p>

            <div class="modal-footer">
                <button type="button" class="btn cancel" id="closeModalBtn">Cancel</button>
                <button type="submit" class="btn save" id="saveModalBtn">Save</button>
            </div>
        </div>
    </div>

