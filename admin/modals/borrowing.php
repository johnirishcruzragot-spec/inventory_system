<!-- borrowing modals -->

<div class="modal" id="borrowModal">

    <div class="modal-content borrow-modal">

        <div class="modal-header">

            <h3 id="borrowTitle">

                New Borrowing Request

            </h3>

            <span
                class="close"
                onclick="closeBorrowModal()">

                &times;

            </span>

        </div>

        <form
            id="borrowForm"
            method="POST"
            action="dashboard.php?page=validation">

            <input
                type="hidden"
                name="borrow_id"
                id="borrow_id"
                value="0">  

            <div class="modal-grid">

                <!-- Equipment -->

                <div class="form-group">

                    <label>Equipment <span style="color:#e94804">*</span></label>

                    <select
                        name="article_id"
                        id="borrow_article"
                        required>

                        <option value="">
                            -- Select Equipment --
                        </option>

<?php

$equipment = $conn->query("

SELECT
    id,
    article_name
FROM articles
ORDER BY article_name ASC

");

while($item = $equipment->fetch_assoc()){

?>

<option value="<?= (int)$item['id']; ?>">

<?= htmlspecialchars($item['article_name']); ?>

(<?= (int)$item['available_qty']; ?> Available)

</option>

<?php } ?>

                    </select>

                </div>

                <!-- Borrower -->

                <div class="form-group">

                    <label>Borrower <span style="color:#e94804">*</span></label>

                    <select
                        name="borrower_id"
                        id="borrow_borrower"
                        required>

                        <option value="">
                            -- Select Borrower --
                        </option>

<?php

$list = $conn->query("

SELECT
    id,
    fullname

FROM personnel

WHERE archived='No'

ORDER BY fullname

");

while($p = $list->fetch_assoc()){

?>

<option value="<?= (int)$p['id']; ?>">

<?= htmlspecialchars($p['fullname']); ?>

</option>

<?php } ?>

                    </select>

                </div>

                <!-- Purpose -->

                <div
                    class="form-group"
                    style="grid-column:1 / span 2;">

                    <label>Purpose <span style="color:#e94804">*</span></label>

                    <textarea
                        name="purpose"
                        id="borrow_purpose"
                        rows="4"
                        required
                        placeholder="State the purpose of borrowing..."></textarea>

                </div>

                <!-- Borrow Date -->

                <div class="form-group">

                    <label>Borrow Date</label>

                    <input
                        type="date"
                        name="borrow_date"
                        id="borrow_date"
                        value="<?= date('Y-m-d'); ?>"
                        required>

                </div>

                <!-- Expected Return -->

                <div class="form-group">

                    <label>Expected Return</label>

                    <input
                        type="date"
                        name="expected_return"
                        id="expected_return">

                </div>

                <!-- Quantity -->

                <div class="form-group">

                    <label>Quantity</label>

                    <input
                        type="number"
                        name="quantity"
                        id="borrow_quantity"
                        value="1"
                        min="1"
                        required>

                </div>

                <!-- Permanent Release -->

                <div class="form-group">

                    <label>Permanent Release</label>

                    <select
                        name="permanent_release"
                        id="permanent_release">

                        <option value="No" selected>

                            No

                        </option>

                        <option value="Yes">

                            Yes

                        </option>

                    </select>

                </div>

                <!-- Remarks -->

                <div
                    class="form-group"
                    style="grid-column:1 / span 2;">

                    <label>Remarks</label>

                    <textarea
                        name="remarks"
                        id="borrow_remarks"
                        rows="3"
                        placeholder="Optional remarks..."></textarea>

                </div>

                <!-- Status -->

                <div class="form-group">

                    <label>Current Status</label>

                    <input
                        type="text"
                        id="borrow_status_display"
                        value="Pending"
                        readonly>

                    <input
                        type="hidden"
                        name="status"
                        id="borrow_status"
                        value="Pending">

                </div>

            </div>

            <div class="modal-buttons">

                <button
                    type="button"
                    class="cancel-btn"
                    onclick="closeBorrowModal()">

                    Cancel

                </button>

<input type="hidden" name="test_post" value="123">

                <button
                    type="submit"
                    name="save_borrowing"
                    class="save-btn">

                    <i class="fa-solid fa-floppy-disk"></i>

                    Save Borrowing

                </button>

            </div>

        </form>

    </div>

</div>
 
<!-- ==========================================
     PROCESS BORROW REQUEST MODAL
========================================== -->

<div class="modal" id="processModal">

    <div class="modal-content process-modal">

        <div class="modal-header">

            <h3 id="processTitle">
                Process Borrow Request
            </h3>

            <span class="close" onclick="closeProcessModal()">
                &times;
            </span>

        </div>

        <form
            id="processForm"
            method="POST"
            action="dashboard.php?page=validation">

            <!-- Request ID -->
            <input
                type="hidden"
                name="request_id"
                id="process_request_id">

            <!-- Approve / Reject -->
            <input
                type="hidden"
                name="decision"
                id="decision"
                value="Approved">

            <!-- Current Status -->
            <div class="form-group">

                <label>Current Status</label>

                <input
                    type="text"
                    id="process_current_status"
                    readonly>

            </div>

            <!-- Next Action -->
            <div class="form-group">

                <label>Next Action</label>

                <input
                    type="text"
                    id="process_next_action"
                    readonly>

            </div>

            <!-- Remarks -->
            <div class="form-group">

                <label>Remarks</label>

                <textarea
                    name="remarks"
                    rows="4"
                    placeholder="Enter remarks..."></textarea>

            </div>

            <div class="modal-buttons">

                <button
                    type="button"
                    class="cancel-btn"
                    onclick="closeProcessModal()">

                    Cancel

                </button>

                <button
                    type="submit"
                    name="process_request"
                    class="delete-btn"
                    onclick="document.getElementById('decision').value='Rejected';">

                    <i class="fa-solid fa-circle-xmark"></i>

                    Reject

                </button>

                <button
                    type="submit"
                    name="process_request"
                    class="save-btn"
                    onclick="document.getElementById('decision').value='Approved';">

                    <i class="fa-solid fa-circle-check"></i>

                    Approve

                </button>

            </div>

        </form>

    </div>

</div>
