<!-- Add User Modal -->
<div class="modal" id="userModal">

    <div class="modal-content">

        <div class="modal-header">

            <h3 id="userModalTitle">Add User</h3>

            <span class="close"
                onclick="closeUserModal()">
                &times;
            </span>

        </div>

        <form method="POST" id="userForm">

            <div class="modal-grid">

                <div class="form-group">

                    <input
                    type="hidden"
                    name="id" 
                    id="user_id">   

                    <label>Full Name</label>

                    <input
                    type="text"
                    name="fullname"
                    id="user_fullname">

                </div>

                <div class="form-group">

                    <label>Email</label>

                    <input
                    type="email"
                    name="email"
                    id="user_email">

                </div>

                <div class="form-group">

                    <label id="passwordLabel">
                        Password
                    </label>

                    <small id="passwordNote">
                    Required when creating a user.
                    </small>

                    <input
                        type="password"
                        name="password"
                        id="user_password"
                        required>

                </div>

                <div class="form-group">

                <label>Division / PMO</label>

                    <select name="division" id="user_division" required>

                    <option value="">-- Select Division --</option>

                    <option value="Administrative and Finance Division">Administrative and Finance Division</option>

                    <option value="Technical Operations Division">Technical Operations Division</option>

                    </select>

                </div>

                <div class="form-group">

                    <label>Role</label>

                    <select name="role" id="user_role">

                        <option value="admin">Admin</option>
                        <option value="custodian">Custodian</option>
                        <option value="encoder">Encoder</option>
                        <option value="supply_officer">Supply Officer</option>
                        <option value="viewer">Viewer</option>

                    </select>

                </div>

            </div>
            
<div class="form-group" id="statusGroup" style="display:none;">

    <label>Status</label>

    <select name="status" id="user_status">

        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
        <option value="blocked">Blocked</option>

    </select>

</div>

            <div class="modal-buttons">

                <button
                    type="button"
                    class="cancel-btn"
                    onclick="closeUserModal()">

                    Cancel

                </button>

<button
    type="submit"
    id="saveUserBtn"
    name="add_user"
    class="save-btn">

    Create User

</button>

            </div>

        </form>

    </div>

</div>