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
                <a href="../admin/admin-request.php">
                    <i class="material-icons icon-btn">mark_email_read</i>
                    <span class="text">Request</span>
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
                <a href="" class="logout" id="accountBtn">
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
        <div class="modal">
            <h2 class="modal-title">Change Password</h2>

            <div class="input-group">
            <input type="password" id="currentPassword" required>
            <label for="currentPassword">Current Password</label>
            </div>

            <div class="input-group">
            <input type="password" id="newPassword" required>
            <label for="newPassword">New Password</label>
            </div>

            <div class="input-group">
            <input type="password" id="confirmPassword" required>
            <label for="confirmPassword">Confirm Password</label>
            </div>

            <div class="modal-footer">
            <button class="btn cancel" id="closeModalBtn">Cancel</button>
            <button class="btn save" id="saveModalBtn">Save</button>
            </div>
        </div>
    </div>

