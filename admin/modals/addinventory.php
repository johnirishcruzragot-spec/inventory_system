<!-- Add Inventory Modal -->
<div class="modal" id="addInventoryModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="addInventoryModalTitle">Add Equipment</h3>
            <span class="close" onclick="closeInventoryModal()">&times;</span>
        </div>

        <form method="POST" enctype="multipart/form-data" id="inventoryForm">

        <div class="modal-page active" id="inventoryPage1">

    <div class="modal-grid">

        <div class="form-group">
            <label>ICS No.</label>
            <input type="text" name="ics_no" id="inventory_ics_no">
        </div>

        <div class="form-group">
            <label>Province</label>
            <input type="text" name="province" id="inventory_province">
        </div>

        <div class="form-group">
            <label>Property Number</label>
            <input type="text" name="property_no" id="inventory_property_no" required>
        </div>

        <div class="form-group">
            <label>Year</label>
            <input type="text" name="year" id="inventory_year">
        </div>

        <div class="form-group">
            <label>Month</label>
            <input type="text" name="month" id="inventory_month">
        </div>

        <div class="form-group">
            <label>Object Code</label>
            <input type="text" name="object_code" id="inventory_object_code">
        </div>

        <div class="form-group">
            <label>Series</label>
            <input type="text" name="series" id="inventory_series">
        </div>

        <div class="form-group">
            <label>Location Code</label>
            <input type="text" name="location_code" id="inventory_location_code">
        </div>

    </div>

</div>

<div class="modal-page" id="inventoryPage2">

    <div class="modal-grid">

        <div class="form-group">

            <label>Article</label>

            <select name="article_id" id="inventory_article_id" required>

                <option value="">Select Article</option>

                <?php
                $articles = $conn->query("SELECT id, article_name FROM articles ORDER BY article_name");
                while($a = $articles->fetch_assoc()){
                ?>

                <option value="<?= $a['id']; ?>">
                    <?= htmlspecialchars($a['article_name']); ?>
                </option>

                <?php } ?>

            </select>

        </div>

        <div class="form-group">
            <label>Type of PPE</label>
            <input type="text" name="type_of_ppe" id="inventory_type_of_ppe">
        </div>

        <div class="form-group" style="grid-column:1/-1;">
            <label>Item Description</label>
            <textarea name="item_description" id="inventory_item_description" rows="3"></textarea>
        </div>

        <div class="form-group">
            <label>Serial Number</label>
            <input type="text" name="serial_number" id="inventory_serial_number">
        </div>

        <div class="form-group">
            <label>Date Acquired</label>
            <input type="date" name="date_acquired" id="inventory_date_acquired">
        </div>

        <div class="form-group">
            <label>Qty</label>
            <input type="number" name="qty" id="inventory_qty" min="1">
        </div>

        <div class="form-group">
            <label>Unit of Measure</label>
            <input type="text" name="unit_of_measure" id="inventory_unit_of_measure">
        </div>

        <div class="form-group">
            <label>Unit Value</label>
            <input type="number" step="0.01" name="unit_value" id="inventory_unit_value">
        </div>

    </div>

</div>

<div class="modal-page" id="inventoryPage3">

    <div class="modal-grid">

        <div class="form-group">
            <label>SE/E</label>
            <input type="text" name="se_e" id="inventory_se_e">
        </div>

        <div class="form-group">
            <label>Condition</label>

            <select name="condition" id="inventory_condition">

                <option value="">Select Condition</option>

                <option value="Serviceable">Serviceable</option>
                <option value="Unserviceable">Unserviceable</option>
                <option value="Missing">Missing</option>

            </select>

        </div>

        <div class="form-group" style="grid-column:1/-1;">
            <label>Remarks</label>
            <textarea name="remarks" id="inventory_remarks" rows="3"></textarea>
        </div>

        <div class="form-group" style="grid-column:1/-1;">

            <label>Accountable Officer</label>

            <select name="accountable_officer_id" id="inventory_accountable_officer_id">

                <option value="">Unassigned</option>

                <?php
                $personnelList = $conn->query("SELECT id, fullname FROM personnel WHERE archived='No' ORDER BY fullname");
                while($p = $personnelList->fetch_assoc()){
                ?>

                <option value="<?= $p['id']; ?>">
                    <?= htmlspecialchars($p['fullname']); ?>
                </option>

                <?php } ?>

            </select>

        </div>

    </div>

</form>

</div>

<div class="modal-buttons">

    <button
        type="button"
        class="cancel-btn"
        onclick="closeInventoryModal()">

        Cancel

    </button>

    <button
        type="button"
        class="prev-btn"
        id="prevInventoryBtn"
        onclick="previousInventoryPage()"
        style="display:none;">

        Previous

    </button>

    <button
        type="button"
        class="next-btn"
        id="nextInventoryBtn"
        onclick="nextInventoryPage()">

        Next

    </button>

    <button
        type="submit"
        class="save-btn"
        id="addInventorySaveBtn"
        name="add_inventory"
        style="display:none;">

        Save Equipment

    </button>

</div>
        </form>
    </div>
</div>