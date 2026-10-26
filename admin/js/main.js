// ======================================
// DICT IMS MAIN JAVASCRIPT
// ======================================

document.addEventListener("DOMContentLoaded", function () {

    // ======================================
    // PAGE NAVIGATION
    // ======================================

    const params = new URLSearchParams(window.location.search);
    const page = params.get("page") || "dashboard";

    document.querySelectorAll(".page").forEach(function (p) {
        p.style.display = "none";
    });

    const current = document.getElementById(page);

    if (current) {
        current.style.display = "block";
    }

    const menus = document.querySelectorAll(".menu");

    menus.forEach(function (m) {
        m.classList.remove("active");
    });

    const menuMap = {
        dashboard: 0,
        users: 1,
        personnel: 2,
        article: 3,
        inventory: 4,
        gallery: 5,
        validation: 6,
        reports: 7,
        settings: 8
    };

    if (menuMap.hasOwnProperty(page) && menus[menuMap[page]]) {
        menus[menuMap[page]].classList.add("active");
    }

});


// ======================================
// PAGE CHANGE
// ======================================

function showPage(page) {
    window.location.href = "dashboard.php?page=" + page;
}


// ======================================
// UNIVERSAL MODALS
// ======================================

function openModal(id) {

    const modal = document.getElementById(id);

    if (!modal) {
        console.error("Modal not found:", id);
        return;
    }

    modal.style.display = "flex";
    modal.classList.add("show");
}

function closeModal(id) {

    const modal = document.getElementById(id);

    if (!modal) return;

    modal.classList.remove("show");
    modal.style.display = "none";
}

// ======================================
// DELETE USER
// ======================================

function openDeleteUserModal(id, name) {

    const hidden = document.getElementById("delete_user_id");
    const label = document.getElementById("deleteUserName");

    if (hidden) hidden.value = id;
    if (label) label.textContent = name;

    openModal("deleteUserModal");
}

function closeDeleteUserModal() {
    closeModal("deleteUserModal");
}


// ======================================
// CLOSE MODALS WHEN CLICKING BACKGROUND
// ======================================

window.addEventListener("click", function (e) {

    document.querySelectorAll(".modal").forEach(function (modal) {

        if (e.target === modal) {

            closeModal(modal.id);

        }

    });

});

// ======================================
// EDIT USER
// ======================================

function editUser(id, fullname, email, division, role, status) {

    openModal("userModal");

    const title = document.getElementById("userModalTitle");
    if (title) {
        title.textContent = "Edit User";
    }

    const btn = document.getElementById("saveUserBtn");
    if (btn) {
        btn.name = "update_user";
        btn.textContent = "Save Changes";
    }

    const set = (field, value) => {
        const el = document.getElementById(field);
        if (el) {
            el.value = value ?? "";
        }
    };

    // Hidden ID (VERY IMPORTANT)
    set("user_id", id);

    // User Information
    set("user_fullname", fullname);
    set("user_email", email);
    set("user_division", division);
    set("user_role", role);
    set("user_status", status);

    // Show Status field
    const statusGroup = document.getElementById("statusGroup");
    if (statusGroup) {
        statusGroup.style.display = "block";
    }

    // Password
    const passwordInput = document.getElementById("user_password");
    if (passwordInput) {
        passwordInput.value = "";
        passwordInput.required = false;
    }

    const passwordLabel = document.getElementById("passwordLabel");
    if (passwordLabel) {
        passwordLabel.textContent = "Reset Password";
    }

    const passwordNote = document.getElementById("passwordNote");
    if (passwordNote) {
        passwordNote.textContent =
            "Leave blank if you don't want to change the password.";
    }
}

// ======================================
// RESET USER MODAL
// ======================================

function openUserModal() {

    const form = document.getElementById("userForm");
    if (form) form.reset();

    const title = document.getElementById("userModalTitle");
    if (title) title.textContent = "Add User";

    const btn = document.getElementById("saveUserBtn");
    if (btn) {
        btn.name = "add_user";
        btn.textContent = "Save User";
    }

    const id = document.getElementById("user_id");
    if (id) id.value = "";

    openModal("userModal");
}

// ======================================
// RESET PERSONNEL MODAL
// ======================================

function openPersonnelModal() {

    const form = document.getElementById("personnelForm");
    if (form) form.reset();

    const title = document.getElementById("personnelModalTitle");
    if (title) title.textContent = "Add Personnel";

    const btn = document.getElementById("savePersonnelBtn");
    if (btn) {
        btn.name = "add_personnel";
        btn.textContent = "Save Personnel";
    }

    const id=document.getElementById("personnelId");
    if (id) id.value = "";

    openModal("personnelModal");
}

// ======================================
// RESET ARTICLE MODAL
// ======================================

function openArticleModal() {

    const form = document.getElementById("articleForm");
    if (form) form.reset();

    const title = document.getElementById("articleModalTitle");
    if (title) title.textContent = "Add Article";

    const btn = document.getElementById("saveArticleBtn");
    if (btn) {
        btn.name = "add_article";
        btn.textContent = "Save Article";
    }

    const id = document.getElementById("article_id");
    if (id) id.value = "";

    openModal("articleModal");
}

// ======================================
// RESET INVENTORY MODAL
// ======================================

function openInventoryModal() {

    const form = document.getElementById("inventoryForm");
    if (form) form.reset();

    const title = document.getElementById("addInventoryModalTitle");
    if (title) title.textContent = "Add Equipment";

    const btn = document.getElementById("addInventorySaveBtn");
    if (btn) {
        btn.name = "add_inventory";
        btn.textContent = "Save Equipment";
    }

    const id = document.getElementById("inventory_edit_id");
    if (id) id.value = "";

    openModal("addInventoryModal");
}

// ======================================
// CLOSE USER MODAL
// ======================================

function closeUserModal() {
    closeModal("userModal");
}

// ======================================
// CLOSE PERSONNEL MODAL
// ======================================

function closePersonnelModal() {
    closeModal("personnelModal");
}


// ======================================
// DESIGNATION
// ======================================

function openDesignationModal() {

    const form = document.getElementById("designationForm");
    if (form) form.reset();

    openModal("designationModal");
}

function closeDesignationModal() {
    closeModal("designationModal");
}

// ======================================
// CLOSE ARTICLE MODAL
// ======================================

function closeArticleModal() {
    closeModal("articleModal");
}

// ======================================
// CLOSE INVENTORY MODAL
// ======================================

function closeInventoryModal() {
    closeModal("addInventoryModal");
}

function closeEditInventoryModal() {
    closeModal("editInventoryModal");
}

// ======================================
// BORROWING
// ======================================

function openBorrowModal() {

    const form = document.getElementById("borrowForm");
    if (form) form.reset();

    openModal("borrowModal");
}

function closeBorrowModal() {
    closeModal("borrowModal");
}


// ======================================
// CLOSE PERSONNEL DETAILS MODAL
// ======================================

function closePersonnelDetailsModal() {
    closeModal("personnelDetailsModal");
}


// ======================================
// CLOSE DESIGNATION MODAL
// ======================================

function closeDesignationModal() {
    closeModal("designationModal");
}


// ======================================
// EDIT PERSONNEL
// ======================================

function editPersonnel(
    id,
    employee_id,
    fullname,
    email,
    contact_number,
    designation,
    position,
    division,
    employment_status,
    status
) {

    openModal("personnelModal");

    const set = (field, value) => {
        const el = document.getElementById(field);
        if (el) el.value = value ?? "";
    };

    const title = document.getElementById("personnelModalTitle");
    if (title) title.textContent = "Edit Personnel";

    const btn = document.getElementById("savePersonnelBtn");
    if (btn) {
        btn.name = "update_personnel";
        btn.textContent = "Update Personnel";
    }

    set("personnelId", id);
    set("employee_id", employee_id);
    set("fullname", fullname);
    set("email", email);
    set("contact_number", contact_number);
    set("designation", designation);
    set("position", position);
    set("division", division);
    set("employment_status", employment_status);
    set("status", status);
}


// ======================================
// EDIT ARTICLE
// ======================================

function editArticle(id, article_name, category, description) {

    openModal("articleModal");

    const title = document.getElementById("articleModalTitle");
    if (title) title.textContent = "Edit Article";

    const btn = document.getElementById("saveArticleBtn");
    if (btn) {
        btn.name = "update_article";
        btn.textContent = "Update Article";
    }

    const set = (field, value) => {
        const el = document.getElementById(field);
        if (el) el.value = value ?? "";
    };

    set("article_id", id);
    set("article_name", article_name);
    set("category", category);
    set("description", description);
}


// ======================================
// EDIT INVENTORY
// ======================================

function editInventory(
    id,
    ics_no,
    province,
    property_no,
    year,
    month,
    object_code,
    series,
    location_code,
    article_id,
    type_of_ppe,
    item_description,
    serial_number,
    date_acquired,
    qty,
    unit_of_measure,
    unit_value,
    se_e,
    condition,
    remarks,
    accountable_officer_id
){

    openModal("editInventoryModal");

    const set = (field, value) => {
        const el = document.getElementById(field);
        if(el){
            el.value = value ?? "";
        }
    };

    const title = document.getElementById("editInventoryModalTitle");
    if(title){
        title.textContent = "Edit Equipment";
    }

    const btn = document.getElementById("editInventorySaveBtn");
    if(btn){
        btn.name = "update_inventory";
        btn.textContent = "Update Equipment";
    }

    set("inventory_edit_id", id);
    set("inventory_ics_no", ics_no);
    set("inventory_province", province);
    set("inventory_property_no", property_no);
    set("inventory_year", year);
    set("inventory_month", month);
    set("inventory_object_code", object_code);
    set("inventory_series", series);
    set("inventory_location_code", location_code);
    set("inventory_article_id", article_id);
    set("inventory_type_of_ppe", type_of_ppe);
    set("inventory_item_description", item_description);
    set("inventory_serial_number", serial_number);
    set("inventory_date_acquired", date_acquired);
    set("inventory_qty", qty);
    set("inventory_unit_of_measure", unit_of_measure);
    set("inventory_unit_value", unit_value);
    set("inventory_se_e", se_e);
    set("inventory_condition", condition);
    set("inventory_remarks", remarks);
    set("inventory_accountable_officer_id", accountable_officer_id);

    document.querySelectorAll("#editInventoryModal .modal-page").forEach(function(page){
        page.classList.remove("active");
    });

    document.getElementById("inventoryPage1").classList.add("active");
}