<!-- Personnel Modal -->
<div class="modal" id="personnelModal">

    <div class="modal-content">

        <div class="modal-header">
            <h3 id="personnelModalTitle">Add Personnel</h3>

            <span class="close" onclick="closePersonnelModal()">
                &times;
            </span>
        </div>

        <form method="POST" id="personnelForm">

            <input type="hidden" name="id" id="personnelId">

            <div class="modal-grid">

                <div class="form-group">
                    <label>Employee ID</label>
                    <input type="text" name="employee_id" id="employee_id" required>
                </div>

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="fullname" id="fullname" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email">
                </div>

                <div class="form-group">
                    <label>Contact Number</label>

                    <input type="text" name="contact_number" id="contact_number"></div>

                <div class="form-group">
                    <label>Position/Designation</label>
                    <input type="text" name="position" id="position">
                </div>

                <div class="form-group">
                <label>Designation</label>
                    <input type="text" name="designation" id="designation">
                </div>

                <div class="form-group">

                <label>Division / PMO</label>

                    <select name="division" id="division" required>

                    <option value="">-- Select Division --</option>

                    <option value="Administrative and Finance Division">Administrative and Finance Division</option>

                    <option value="Technical Operations Division">Technical Operations Division</option>

                    </select>

                </div>

<div class="form-group">
<label>Status</label>

<select name="status" id="status">
    <option value="Active">Active</option>
    <option value="Inactive">Inactive</option>
    <option value="Blocked">Blocked</option>

</select>
</div>

<div class="form-group">
<label>Employment Status</label>

<select name="employment_status" id="employment_status">
    <option value="Permanent">Permanent</option>
    <option value="Contractual">Contractual</option>
    <option value="COS">COS</option>
    <option value="JO">JO</option>
</select>
</div>

</div>

<div class="modal-buttons">

    <button type="button"
            class="cancel-btn"
            onclick="closePersonnelModal()">
        Cancel
    </button>

    <button type="submit"
            id="savePersonnelBtn"
            name="add_personnel"
            class="save-btn">
        Save Personnel
    </button>

</div>

</form>

</div>

</div>

<!-- Personnel Details Modal -->
<div class="modal" id="personnelDetailsModal">

    <div class="modal-content personnel-details-modal">

        <div class="personnel-details-header">

            <button
                type="button"
                class="back-btn"
                onclick="closePersonnelDetailsModal()">
                ← Back
            </button>

            <h2>Personnel Information</h2>

            <button
                type="button"
                class="add-equipment-btn">
                + Assign Equipment
            </button>

        </div>

        <div
            class="personnel-details-body"
            id="personnelDetailsBody">

        </div>

    </div>

</div>  


<div id="designationModal" class="modal">

    <div class="modal-content">

        <div class="modal-header">

            <h3 id="designationModalTitle">
                Add Designation
            </h3>

            <span
                class="close"
                onclick="closeDesignationModal()">
                &times;
            </span>

        </div>

        <form method="POST" id="designationForm">

            <input
                type="hidden"
                name="id"
                id="designation_id">

            <div class="modal-grid">

                <div class="form-group">

                    <label>Designation ID</label>

                    <input
                        type="text"
                        name="class_id"
                        id="designation_class_id"
                        required>

                </div>

                <div class="form-group">

                    <label>Designation Name</label>

                    <input
                        type="text"
                        name="class_name"
                        id="designation_class_name"
                        required>

                </div>

                <div class="form-group">

                    <label>Designation Level</label>

                    <input
                        type="number"
                        name="class_level"
                        id="designation_class_level"
                        required>

                </div>

            </div>

            <div class="modal-buttons">

                <button
                    type="button"
                    class="cancel-btn"
                    onclick="closeDesignationModal()">

                    Cancel

                </button>

                <button
                    type="submit"
                    id="saveDesignationBtn"
                    name="add_designation"
                    class="save-btn">

                    Save

                </button>

            </div>

        </form>

    </div>

</div>