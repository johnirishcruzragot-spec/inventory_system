function openDesignationModal(){

    document.getElementById("designationForm").reset();

    document.getElementById("designationModalTitle").innerHTML =
    "Add Designation";

    document.getElementById("saveDesignationBtn").innerHTML =
    "Save";

    document.getElementById("saveDesignationBtn").name =
    "add_designation";

    document.getElementById("designation_id").value="";

    document.getElementById("designationModal").style.display="flex";

}

function closeDesignationModal(){

    document.getElementById("designationModal").style.display="none";

}

function searchDesignation(){

    let input=document.getElementById("searchDesignation").value.toLowerCase();

    let rows=document.querySelectorAll("#designationTable tbody tr");

    rows.forEach(function(row){

        row.style.display=row.innerText.toLowerCase().includes(input)
        ? ""
        : "none";

    });

}

function editDesignation(id, class_id, class_name, class_level){

    document.getElementById("designationModalTitle").innerHTML =
    "Editing " + class_name;

    document.getElementById("saveDesignationBtn").innerHTML =
    "Update";

    document.getElementById("saveDesignationBtn").name =
    "update_designation";

    document.getElementById("designation_id").value = id;

    document.getElementById("designation_class_id").value = class_id;

    document.getElementById("designation_class_name").value = class_name;

    document.getElementById("designation_class_level").value = class_level;

    document.getElementById("designationModal").style.display = "flex";

}
