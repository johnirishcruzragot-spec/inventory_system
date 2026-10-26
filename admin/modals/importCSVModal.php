<!-- importCSVModal -->

<div class="modal" id="importCSVModal">

    <div class="modal-content" style="max-width:550px;">

        <div class="modal-header">

            <h3>Import Inventory CSV</h3>

            <span
                class="close"
                onclick="closeImportCSVModal()">
                &times;
            </span>

        </div>

        <form
            method="POST"
            enctype="multipart/form-data">

            <div class="modal-body">

                <div class="form-group">

                    <label>Select CSV File</label>

                    <input
                        type="file"
                        name="inventory_csv"
                        accept=".csv"
                        required>

                </div>

                <small>

                    Accepted format:
                    .csv (Comma Separated Values)

                </small>

            </div>

            <div class="modal-buttons">

                <button
                    type="button"
                    class="cancel-btn"
                    onclick="closeImportCSVModal()">

                    Cancel

                </button>

                <button
                    type="submit"
                    name="import_inventory_csv"
                    class="save-btn">

                    Import CSV

                </button>

            </div>

        </form>

    </div>

</div>

<!-- QR modal -->

<div id="qrModal" class="modal">

    <div class="modal-content qr-modal">

        <span class="close" onclick="closeQRModal()">&times;</span>

        <h2>
            <i class="fa-solid fa-qrcode"></i>
            Inventory QR Code
        </h2>

        <div id="qrcode"></div>

        <div class="qr-info">

            <p><strong>ICS No:</strong> <span id="qrICS"></span></p>

            <p><strong>Property No:</strong> <span id="qrProperty"></span></p>

            <p><strong>Article:</strong> <span id="qrArticle"></span></p>

            <p><strong>Description:</strong> <span id="qrDescription"></span></p>

            <p><strong>Serial No:</strong> <span id="qrSerial"></span></p>

            <p><strong>Accountable Officer:</strong> <span id="qrOfficer"></span></p>

        </div>

        <button onclick="printQRCode()" class="print-btn">

            <i class="fa-solid fa-print"></i>

            Print QR

        </button>

    </div>

</div>