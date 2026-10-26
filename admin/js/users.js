function openUserModal(){

    document.getElementById("userForm").reset();
    document.getElementById("userModalTitle").innerHTML = "Add User";
    document.getElementById("saveUserBtn").innerHTML = "Create User";
    document.getElementById("saveUserBtn").name = "add_user";
    document.getElementById("user_id").value = "";
    document.getElementById("statusGroup").style.display = "none";
    document.getElementById("passwordLabel").innerHTML = "Password";
    document.getElementById("passwordNote").innerHTML =
        "Required when creating a user.";
    document.getElementById("user_password").required = true;
    document.getElementById("userModal").style.display = "flex";
}

function closeUserModal(){
    document.getElementById("userModal").style.display = "none";
}

function editUser(id, fullname, email, division, role, status){

    document.getElementById("userModalTitle").innerHTML = "Edit User";
    document.getElementById("saveUserBtn").innerHTML = "Save Changes";
    document.getElementById("saveUserBtn").name = "update_user";
    document.getElementById("user_id").value = id;
    document.getElementById("user_fullname").value = fullname;
    document.getElementById("user_email").value = email;
    document.getElementById("user_division").value = division;
    document.getElementById("user_role").value = role;
    document.getElementById("user_status").value = status;
    document.getElementById("statusGroup").style.display = "block";
    document.getElementById("passwordLabel").innerHTML = "Reset Password";
    document.getElementById("passwordNote").innerHTML =
        "Leave blank if you don't want to change the password.";
    document.getElementById("user_password").required = false;
    document.getElementById("user_password").value = "";
    document.getElementById("userModal").style.display = "flex";
}

function searchUsers() {

    const keyword = document.getElementById("searchUser").value.toLowerCase();
    const rows = document.querySelectorAll("#usersTable tbody tr");

    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(keyword)
            ? ""
            : "none";
    });

}

function cancelEdit(){
    window.location.href = "dashboard.php?page=users";
}

function openDeleteUserModal(id, fullname){
    document.getElementById("delete_user_id").value = id;
    document.getElementById("deleteUserName").innerText = fullname;
    document.getElementById("deleteUserModal").style.display = "flex";
}

function closeDeleteUserModal(){
    document.getElementById("deleteUserModal").style.display = "none";
}

document.addEventListener("click", function(e){
    const modal = document.getElementById("deleteUserModal");
    if (modal && e.target === modal) {
        closeDeleteUserModal();
    }
});