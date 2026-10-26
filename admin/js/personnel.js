function openPersonnelModal(){

    document.getElementById("personnelModal").style.display="flex";

}

function closePersonnelModal(){

    document.getElementById("personnelModal").style.display="none";

}

function searchPersonnel(){

    let input = document.getElementById("searchPersonnel").value.toLowerCase();

    let rows = document.querySelectorAll("#personnelTable tbody tr");

    rows.forEach(function(row){

        row.style.display =
            row.textContent.toLowerCase().includes(input)
            ? ""
            : "none";

    });

}


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
){
    document.getElementById("personnelModalTitle").innerHTML = "Edit Personnel";

    document.getElementById("savePersonnelBtn").innerHTML = "Save Changes";

    document.getElementById("savePersonnelBtn").name = "update_personnel";

    document.getElementById("personnelId").value = id;

    document.getElementById("employee_id").value = employee_id;

    document.getElementById("fullname").value = fullname;

    document.getElementById("email").value = email;

    document.getElementById("contact_number").value = contact_number;

    document.getElementById("designation").value = designation;

    document.getElementById("position").value = position;

    document.getElementById("division").value = division;

    document.getElementById("employment_status").value = employment_status;

    document.getElementById("status").value = status;

    document.getElementById("personnelModal").style.display = "flex";
}

function archivePersonnel(id){

    if(confirm("Archive this personnel?")){

        window.location.href =
        "dashboard.php?page=personnel&archive=" + id;

    }

}

function restorePersonnel(id){

    if(confirm("Restore this personnel?")){

        window.location =
        "dashboard.php?page=personnel&restore=" + id;

    }

}

function viewPersonnel(
    employee_id,
    fullname,
    email,
    contact_number,
    designation,
    position,
    division,
    employment_status,
    status
){

    let html = `
        <table class="details-table">
            <tr><td><b>Employee ID</b></td><td>${employee_id}</td></tr>
            <tr><td><b>Name</b></td><td>${fullname}</td></tr>
            <tr><td><b>Email</b></td><td>${email}</td></tr>
            <tr><td><b>Contact</b></td><td>${contact_number}</td></tr>
            <tr><td><b>Designation</b></td><td>${designation}</td></tr>
            <tr><td><b>Position</b></td><td>${position}</td></tr>
            <tr><td><b>Division</b></td><td>${division}</td></tr>
            <tr><td><b>Employment</b></td><td>${employment_status}</td></tr>
            <tr><td><b>Status</b></td><td>${status}</td></tr>
        </table>
    `;

    document.getElementById("personnelDetailsBody").innerHTML = html;

    document.getElementById("personnelDetailsModal").style.display = "flex";
}


function closePersonnelDetailsModal(){

    document.getElementById("personnelDetailsModal").style.display="none";

}


function showPersonnelTab(tabId, button) {

    // Hide all tab contents
    document.querySelectorAll(".personnel-content").forEach(function(tab){
        tab.style.display = "none";
    });

    // Remove active tab
    document.querySelectorAll(".tab-btn").forEach(function(btn){
        btn.classList.remove("active");
    });

    // Show selected tab
    document.getElementById(tabId).style.display = "block";

    // Activate selected button
    button.classList.add("active");

    // Toggle Add buttons
    document.getElementById("addPersonnelBtn").style.display = "none";
    document.getElementById("addDesignationBtn").style.display = "none";

    if(tabId === "personnelList"){
        document.getElementById("addPersonnelBtn").style.display = "inline-block";
    }

    if(tabId === "designationList"){
        document.getElementById("addDesignationBtn").style.display = "inline-block";
    }

}

function searchArchive(){

    let input =
    document.getElementById("searchArchive")
    .value.toLowerCase();

    let rows =
    document.querySelectorAll("#archiveTable tbody tr");

    rows.forEach(function(row){

        row.style.display =
        row.innerText.toLowerCase().includes(input)
        ? ""
        : "none";

    });

}