function searchInventory() {
    const input = (document.getElementById("searchInventory")?.value || "").toLowerCase();
    const rows = document.querySelectorAll("#inventoryTable tbody tr.inventory-main-row");

    rows.forEach(function (row) {
        row.style.display = row.innerText.toLowerCase().includes(input) ? "" : "none";
    });
}

function filterInventoryCategory() {
    const filter = (document.getElementById("categoryFilter")?.value || "").toLowerCase();
    const rows = document.querySelectorAll("#inventoryTable tbody tr.inventory-main-row");

    rows.forEach(function (row) {
        const articleCell = row.cells[3];
        const article = articleCell ? articleCell.innerText.toLowerCase() : "";
        row.style.display = (filter === "" || article.includes(filter)) ? "" : "none";
    });
}

function toggleInventoryDetails(id) {

    const currentRow = document.getElementById("details-row-" + id);

    if (!currentRow) return;

    // Close every other details row
    document.querySelectorAll(".inventory-details-row").forEach(function(row){

        if(row !== currentRow){
            row.style.display = "none";
        }

    });

    // Toggle selected row
    if(currentRow.style.display === "table-row"){

        currentRow.style.display = "none";

    }else{

        currentRow.style.display = "table-row";

    }

}

function editInventoryInline(button){

    const details = button.closest(".inventory-details");

    if(!details) return;

    details.querySelectorAll(".view-mode").forEach(function(el){

        el.style.display = "none";

    });

    details.querySelectorAll(".edit-mode").forEach(function(el){

        el.style.display = "";

    });

    button.style.display = "none";

    details.querySelector(".save-btn").style.display = "inline-flex";

    details.querySelector(".cancel-btn").style.display = "inline-flex";

}

function cancelInventoryInline(button){

    const details = button.closest(".inventory-details");

    if(!details) return;

    details.querySelectorAll(".view-mode").forEach(function(el){

        el.style.display = "";

    });

    details.querySelectorAll(".edit-mode").forEach(function(el){

        el.style.display = "none";

    });

    details.querySelector(".edit-btn").style.display = "inline-flex";

    details.querySelector(".save-btn").style.display = "none";

    button.style.display = "none";

}

function viewInventory(id) {
    const row = document.getElementById("details-row-" + id);
    if (!row) return;

    row.style.display = "table-row";
    row.scrollIntoView({ behavior: "smooth", block: "center" });
}

function deleteInventory(id) {
    if (confirm("Delete this equipment?")) {
        window.location.href = "dashboard.php?page=inventory&delete_inventory=" + id;
    }
}

function openInventoryModal() {

    const form = document.getElementById("inventoryForm");

    if(form){
        form.reset();
    }

    showInventoryPage(1);

    const title = document.getElementById("addInventoryModalTitle");
    const btn = document.getElementById("addInventorySaveBtn");
    const modal = document.getElementById("addInventoryModal");

    if(title){
        title.textContent = "Add Equipment";
    }

    if(btn){
        btn.textContent = "Save Equipment";
        btn.name = "add_inventory";
    }

    if(modal){
        modal.classList.add("show");
        modal.style.display = "flex";
    }

}

function closeInventoryModal(){

    const modal = document.getElementById("addInventoryModal");

    if(modal){

        modal.classList.remove("show");
        modal.style.display = "none";

    }

    showInventoryPage(1);

}

function closeEditInventoryModal() {
    const modal = document.getElementById("editInventoryModal");
    if (modal) modal.style.display = "none";
}

function previewInventoryImage(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        const preview = document.getElementById("inventoryPreviewAdd");
        if (preview) preview.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

function previewInventoryImageAdd(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        const preview = document.getElementById("inventoryPreview");
        if (preview) preview.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

function openImportCSVModal(){

    document.getElementById("importCSVModal").style.display = "flex";

}

function closeImportCSVModal(){

    document.getElementById("importCSVModal").style.display = "none";

}

let inventoryPage = 1;

function showInventoryPage(page){

    document.querySelectorAll("#addInventoryModal .modal-page").forEach(function(p){
        p.classList.remove("active");
    });

    document
        .getElementById("inventoryPage" + page)
        .classList.add("active");

    document.getElementById("prevInventoryBtn").style.display =
        page === 1 ? "none" : "inline-block";

    document.getElementById("nextInventoryBtn").style.display =
        page === 3 ? "none" : "inline-block";

    document.getElementById("addInventorySaveBtn").style.display =
        page === 3 ? "inline-block" : "none";

    inventoryPage = page;
}

function nextInventoryPage(){

    if(inventoryPage < 3){
        showInventoryPage(inventoryPage + 1);
    }

}

function previousInventoryPage(){

    if(inventoryPage > 1){
        showInventoryPage(inventoryPage - 1);
    }

}

document.addEventListener("DOMContentLoaded", function(){

    showInventoryPage(1);

});

// QR
function openQRModal(
    id,
    ics,
    property,
    article,
    description,
    serial,
    officer
){

    document.getElementById("qrModal").style.display="block";

    document.getElementById("qrICS").textContent=ics;
    document.getElementById("qrProperty").textContent=property;
    document.getElementById("qrArticle").textContent=article;
    document.getElementById("qrDescription").textContent=description;
    document.getElementById("qrSerial").textContent=serial;
    document.getElementById("qrOfficer").textContent=officer;

    let qr=document.getElementById("qrcode");

    qr.innerHTML="";

let qrData =
`DICT Inventory Management System

Inventory ID: ${id}
ICS No: ${ics}
Property No: ${property}
Article: ${article}
Description: ${description}
Serial Number: ${serial}
Accountable Officer: ${officer}`;

new QRCode(document.getElementById("qrcode"),{
    text: qrData,
    width:250,
    height:250,
    correctLevel: QRCode.CorrectLevel.H
});

}

function closeQRModal(){

    document.getElementById("qrModal").style.display="none";

}

// Print Function

function printQRCode(){

    let content=document.querySelector(".qr-modal").innerHTML;

    let w=window.open("");

    w.document.write(`
        <html>

        <head>

        <title>Inventory QR Code</title>

        <style>

        body{

            font-family:Arial;
            text-align:center;
            padding:20px;

        }

        #qrcode{

            margin:20px auto;

        }

        p{

            margin:6px;

        }

        </style>

        </head>

        <body>

        ${content}

        <script>
        window.onload=function(){

            window.print();

            window.close();

        }
        <\/script>

        </body>

        </html>
    `);

    w.document.close();

}