<!-- ==========================================
     ARTICLE MODAL
========================================== -->

<div class="modal" id="articleModal">

    <div class="modal-content article-modal">

        <div class="modal-header">

            <h3 id="articleModalTitle">

                Add Article

            </h3>

            <span
                class="close"
                onclick="closeArticleModal()">

                &times;

            </span>

        </div>

        <form
            method="POST"
            id="articleForm">

            <input
                type="hidden"
                name="id"
                id="article_id">

            <div class="modal-grid">

                <!-- Article Code -->

                <div class="form-group">

                    <label>

                        Article Code

                    </label>

                    <input
                        type="text"
                        name="article_code"
                        id="article_code"
                        placeholder="AUTO or ART-001">

                </div>

                <!-- Article Name -->

                <div class="form-group">

                    <label>

                        Article Name

                        <span style="color:red">*</span>

                    </label>

                    <input
                        type="text"
                        name="article_name"
                        id="article_name"
                        required>

                </div>

                <!-- Category -->

                <div class="form-group">

                    <label>

                        Category

                    </label>

                    <select
                        name="category"
                        id="category"
                        required>

                        <option value="">-- Select Category --</option>

                        <option value="Appliances">Appliances</option>
                        <option value="Communication Equipment">Communication Equipment</option>
                        <option value="Computer Equipment">Computer Equipment</option>
                        <option value="Furniture and Fixtures">Furniture and Fixtures</option>
                        <option value="ICT Equipment">ICT Equipment</option>
                        <option value="Networking Equipment">Networking Equipment</option>
                        <option value="Office Equipment">Office Equipment</option>
                        <option value="Power Equipment">Power Equipment</option>
                        <option value="Printers and Scanners">Printers and Scanners</option>
                        <option value="Tools and Instruments">Tools and Instruments</option>

                    </select>

                </div>

                <!-- Quantity -->

                <div class="form-group">

                    <label>

                        Total Quantity

                    </label>

                    <input
                        type="number"
                        name="quantity"
                        id="quantity"
                        min="1"
                        value="1"
                        required>

                </div>

                <!-- Available Quantity -->

                <div class="form-group">

                    <label>

                        Available Quantity

                    </label>

                    <input
                        type="number"
                        name="available_quantity"
                        id="available_quantity"
                        min="0"
                        value="1"
                        required>

                </div>

                <!-- Description -->

                <div
                    class="form-group"
                    style="grid-column:1/-1;">

                    <label>

                        Description

                    </label>

                    <textarea
                        name="description"
                        id="description"
                        rows="5"></textarea>

                </div>

            </div>

            <div class="modal-buttons">

                <button
                    type="button"
                    class="cancel-btn"
                    onclick="closeArticleModal()">

                    Cancel

                </button>

                <button
                    type="submit"
                    id="saveArticleBtn"
                    name="add_article"
                    class="save-btn">

                    <i class="fa-solid fa-floppy-disk"></i>

                    Save Article

                </button>

            </div>

        </form>

    </div>

</div>
