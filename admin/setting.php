<!-- Settings -->
<div id="settings" class="page" style="<?= $currentPage === 'settings' ? '' : 'display:none;'; ?>">

    <div class="settings-header">

        <div>
            <span class="section-label">
                SYSTEM SETTINGS
            </span>

            <h2>Settings</h2>

            <p>
                Configure the DICT Inventory Management System.
            </p>
        </div>

    </div>


    <div class="settings-grid">

        <!-- System Information -->
        <div class="settings-card">

            <h3>🏢 System Information</h3>

            <div class="form-group">
                <label>Office Name</label>
                <input
                    type="text"
                    value="Department of Information and Communications Technology"
                    readonly>
            </div>

            <div class="form-group">
                <label>Office Code</label>
                <input
                    type="text"
                    value="DICT"
                    readonly>
            </div>

            <div class="form-group">
                <label>System Version</label>
                <input
                    type="text"
                    value="1.0.0"
                    readonly>
            </div>

        </div>


        <!-- Account -->
        <div class="settings-card">

            <h3>👤 Administrator</h3>

            <div class="form-group">
                <label>Full Name</label>
                <input
                    type="text"
                    value="<?= htmlspecialchars($_SESSION['fullname']); ?>"
                    readonly>
            </div>

            <div class="form-group">
                <label>Role</label>
                <input
                    type="text"
                    value="<?= ucfirst($_SESSION['role']); ?>"
                    readonly>
            </div>

            <button class="save-btn">
                Change Password 
            </button>

        </div>


        <!-- Inventory -->
        <div class="settings-card">

            <h3>📦 Inventory Settings</h3>

            <label>

                <input type="checkbox" checked>

                Enable QR Code Generation

            </label>

            <label>

                <input type="checkbox" checked>

                Auto Update Equipment Status

            </label>

            <label>

                <input type="checkbox">

                Archive Disposed Equipment

            </label>

        </div>


        <!-- Borrowing -->
        <div class="settings-card">

            <h3>📋 Borrowing Settings</h3>

            <label>

                <input type="checkbox" checked>

                Require Approval Before Borrowing

            </label>

            <label>

                <input type="checkbox" checked>

                Enable Return Validation

            </label>

            <label>

                <input type="checkbox">

                Email Notifications

            </label>

        </div>

                <!-- Database -->
<div class="form-group">
    <label>Database</label>
    <input type="text" value="<?= $dbname; ?>" readonly>
</div>

<div class="form-group">
    <label>Server</label>
    <input type="text" value="localhost" readonly>
</div>

<button class="action-btn">
    💾 Backup Database
</button>


        <!-- Maintenance -->
        <div class="settings-card">

            <h3>⚙ Maintenance</h3>

<button class="action-btn">
    🗑 Clear Cache
</button>

<button class="action-btn">
    📄 Export Logs
</button>

<button class="logout-btn action-btn">
    🚪 Logout
</button>

        </div>

    </div>

</div>

</div>