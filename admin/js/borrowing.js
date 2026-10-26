// ======================================
// DICT IMS - BORROWING JAVASCRIPT
// ======================================

let currentStatus = "";

// ======================================
// OPEN BORROW MODAL
// ======================================

function openBorrowModal() {

    const form = document.getElementById("borrowForm");

    if (form) {
        form.reset();
    }

    document.getElementById("borrowTitle").textContent =
        "New Borrowing Request";

    document.getElementById("borrow_id").value = "0";

    document.getElementById("borrow_status").value =
        "Pending";

    document.getElementById("borrow_status_display").value =
        "Pending";

    document.getElementById("borrowModal").style.display =
        "flex";

}

// ======================================
// CLOSE BORROW MODAL
// ======================================

function closeBorrowModal(){

    document.getElementById("borrowModal").style.display =
        "none";

}

// ======================================
// EDIT BORROWING
// ======================================

function editBorrow(
    id,
    article,
    borrower,
    borrowDate,
    expectedReturn,
    purpose,
    remarks,
    status
){

    document.getElementById("borrowTitle").textContent =
        "Edit Borrowing Request";

    document.getElementById("borrow_id").value = id;

    document.getElementById("borrow_article").value =
        article;

    document.getElementById("borrow_borrower").value =
        borrower;

    document.getElementById("borrow_date").value =
        borrowDate;

    if(
        expectedReturn &&
        expectedReturn !== "0000-00-00"
    ){

        document.getElementById("expected_return").value =
            expectedReturn;

    }else{

        document.getElementById("expected_return").value =
            "";

    }

    document.getElementById("borrow_purpose").value =
        purpose;

    document.getElementById("borrow_remarks").value =
        remarks;

    document.getElementById("borrow_status").value =
        status;

    document.getElementById("borrow_status_display").value =
        status;

    document.getElementById("borrowModal").style.display =
        "flex";

}

// ======================================
// SEARCH
// ======================================

function searchBorrow(){

    applyBorrowFilters();

}

// ======================================
// STATUS FILTER
// ======================================

function filterBorrowStatus(status,button){

    currentStatus = status;

    document.querySelectorAll(".borrow-tab").forEach(tab=>{

        tab.classList.remove("active");

    });

    if(button){

        button.classList.add("active");

    }

    applyBorrowFilters();

}

// ======================================
// TABLE FILTER
// ======================================

function applyBorrowFilters(){

    const keyword =
        document.getElementById("searchBorrow")
        .value
        .trim()
        .toUpperCase();

    document.querySelectorAll("#borrowTable tbody tr")
    .forEach(row=>{

        const text =
            row.innerText.toUpperCase();

        const badge =
            row.querySelector(".status-badge");

        const status =
            badge ? badge.innerText.trim() : "";

        const matchSearch =
            text.includes(keyword);

        const matchStatus =
            currentStatus === "" ||
            status === currentStatus;

        row.style.display =
            (matchSearch && matchStatus)
            ? ""
            : "none";

    });

}

// ======================================
// DATE VALIDATION
// ======================================

document.addEventListener("DOMContentLoaded",function(){

    const borrow =
        document.getElementById("borrow_date");

    const expected =
        document.getElementById("expected_return");

    if(!borrow || !expected){

        return;

    }

    expected.addEventListener("change",function(){

        if(
            borrow.value &&
            expected.value &&
            expected.value < borrow.value
        ){

            alert(
                "Expected return cannot be earlier than borrow date."
            );

            expected.value = "";

        }

    });

});

// ======================================
// PREVENT DOUBLE SUBMIT
// ======================================

document.addEventListener("DOMContentLoaded",function(){

    const form =
        document.getElementById("borrowForm");

    if(!form){

        return;

    }

    form.addEventListener("submit",function(){

        const btn =
            form.querySelector("button[type='submit']");

        if(btn){

            btn.disabled = true;

            btn.innerHTML =
                "<i class='fa-solid fa-spinner fa-spin'></i> Saving...";

        }

    });

});

// ======================================
// CLOSE MODAL WHEN CLICK OUTSIDE
// ======================================

window.addEventListener("click",function(e){

    const borrowModal =
        document.getElementById("borrowModal");

    const processModal =
        document.getElementById("processModal");

    if(
        borrowModal &&
        e.target === borrowModal
    ){

        closeBorrowModal();

    }

    if(
        processModal &&
        e.target === processModal
    ){

        closeProcessModal();

    }

});

// ======================================
// OPEN PROCESS MODAL
// ======================================

function openProcessModal(
    id,
    requestNo,
    borrower,
    borrowDate,
    expectedReturn,
    purpose,
    status
){

    document.getElementById("process_request_id").value =
        id;

    document.getElementById("view_request_no").value =
        requestNo;

    document.getElementById("view_status").value =
        status;

    document.getElementById("view_borrower").value =
        borrower;

    document.getElementById("view_borrow_date").value =
        borrowDate;

    document.getElementById("view_return_date").value =
        (
            !expectedReturn ||
            expectedReturn === "0000-00-00"
        )
        ? "No Return Required"
        : expectedReturn;

    document.getElementById("view_purpose").value =
        purpose;

    const division =
        document.getElementById("view_division");

    if(division){

        division.value = "";

    }

    document.getElementById("processModal").style.display =
        "flex";

}

// ======================================
// CLOSE PROCESS MODAL
// ======================================

function closeProcessModal(){

    document.getElementById("processModal").style.display =
        "none";

}

function openProcessModal(id, status){

    document.getElementById("process_request_id").value = id;

    document.getElementById("process_current_status").value = status;

    let next = "";

    switch(status){

        case "Pending Review":
            next = "Custodian Verification";
            break;

        case "Custodian Verification":
            next = "Supply Officer Approval";
            break;

        case "Supply Officer Approval":
            next = "Admin Approval";
            break;

        case "Admin Approval":
            next = "Ready for Release";
            break;

        case "Ready for Release":
            next = "Borrowed";
            break;

        case "Borrowed":
            next = "Return Requested";
            break;

        case "Return Requested":
            next = "Inspection";
            break;

        case "Inspection":
            next = "Completed";
            break;

        default:
            next = "Completed";

    }

    document.getElementById("process_next_action").value = next;

    openModal("processModal");

}

function closeProcessModal(){

    closeModal("processModal");

}