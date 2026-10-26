<?php
include "../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$message = "";


/* =========================================================
   ADD USER
========================================================= */

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {

    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $role     = $_POST['role'];

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();

    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {

        $message = "
            <div class='error'>
                Email already exists!
            </div>
        ";

    } else {

        $hashed_password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $stmt = $conn->prepare("
            INSERT INTO users
            (fullname, email, password, role)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssss",
            $fullname,
            $email,
            $hashed_password,
            $role
        );

        if ($stmt->execute()) {

            $message = "
                <div class='success'>
                    User added successfully!
                </div>
            ";

        } else {

            $message = "
                <div class='error'>
                    Failed to add user.
                </div>
            ";
        }

        $stmt->close();
    }

    $check->close();
}


/* =========================================================
   DELETE USER
========================================================= */

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {

    $id = (int) $_GET['delete'];

    // Prevent deleting current admin
    if ($id != $_SESSION['user_id']) {

        $stmt = $conn->prepare(
            "DELETE FROM users WHERE id = ?"
        );

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}


/* =========================================================
   UPDATE USER
========================================================= */

if (
    $_SERVER["REQUEST_METHOD"] == "POST"
    && isset($_POST['update_user'])
) {

    $id       = (int) $_POST['id'];
    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $role     = $_POST['role'];
    $status   = $_POST['status'];
    $password = $_POST['password'];

    /*
     * If password is blank:
     * keep the existing password.
     *
     * If password is entered:
     * hash the new password and update it.
     */

    if (!empty($password)) {

        $hashed_password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $stmt = $conn->prepare("
            UPDATE users
            SET
                fullname = ?,
                email = ?,
                password = ?,
                role = ?,
                status = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "sssssi",
            $fullname,
            $email,
            $hashed_password,
            $role,
            $status,
            $id
        );

    } else {

        $stmt = $conn->prepare("
            UPDATE users
            SET
                fullname = ?,
                email = ?,
                role = ?,
                status = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "ssssi",
            $fullname,
            $email,
            $role,
            $status,
            $id
        );
    }

    if ($stmt->execute()) {

        $stmt->close();

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();

    } else {

        $message = "
            <div class='error'>
                Failed to update user.
            </div>
        ";

        $stmt->close();
    }
}

/* =========================================================
   GET USER FOR EDIT
========================================================= */

$edit_user = null;

if (
    isset($_GET['edit'])
    && is_numeric($_GET['edit'])
) {

    $id = (int) $_GET['edit'];

    $stmt = $conn->prepare("
        SELECT
            id,
            fullname,
            email,
            role,
            status
        FROM users
        WHERE id = ?
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $edit_result = $stmt->get_result();

    if ($edit_result->num_rows > 0) {
        $edit_user = $edit_result->fetch_assoc();
    }

    $stmt->close();
}


/* =========================================================
   GET ALL USERS
========================================================= */

$result = $conn->query("
    SELECT
        id,
        fullname,
        email,
        role,
        created_at,
        last_login,
        status
    FROM users
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<head>

<link rel="stylesheet" href="css/users.css">

<title>Manage Users</title>



</head>


<body>

<div class="container">

<!-- =====================================================
     HEADER
===================================================== -->
<h2>
    <a href="dashboard.php" class="back-btn">
        ← Back
    </a>

    <span>Manage Users</span>
</h2>

<!-- =====================================================
     MESSAGE
===================================================== -->

<?php

if ($message != "") {
    echo $message;
}

?>


<!-- =====================================================
     FORM BOX
===================================================== -->

<div class="form-box">


<?php if ($edit_user) { ?>

    <!-- =================================================
         EDIT USER
    ================================================== -->

    <h3>
        Edit User #<?= (int)$edit_user['id']; ?>
    </h3>


    <form method="POST">

        <input
            type="hidden"
            name="id"
            value="<?= (int)$edit_user['id']; ?>"
        >


        <div class="form-row">

            <div class="form-group">

                <label>
                    Full Name
                </label>

                <input
                    type="text"
                    name="fullname"
                    value="<?= htmlspecialchars(
                        $edit_user['fullname']
                    ); ?>"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="<?= htmlspecialchars(
                        $edit_user['email']
                    ); ?>"
                    required
                >

            </div>

        </div>


<div class="form-row">

    <div class="form-group">

        <label>
            New Password
        </label>

        <input
            type="password"
            name="password"
            placeholder="Leave blank to keep current password"
        >

    </div>


    <div class="form-group">

        <label>
            Role
        </label>

        <select name="role">

            <option
                value="admin"
                <?= $edit_user['role'] == 'admin'
                    ? 'selected'
                    : ''; ?>>
                Admin
            </option>

            <option
                value="custodian"
                <?= $edit_user['role'] == 'custodian'
                    ? 'selected'
                    : ''; ?>>
                Custodian
            </option>

            <option
                value="encoder"
                <?= $edit_user['role'] == 'encoder'
                    ? 'selected'
                    : ''; ?>>
                Encoder
            </option>

            <option
                value="supply_officer"
                <?= $edit_user['role'] == 'supply_officer'
                    ? 'selected'
                    : ''; ?>>
                Supply Officer
            </option>

        </select>

    </div>

</div>


            <div class="form-group">

                <label>
                    Status
                </label>

                <select name="status">

                    <option
                        value="Active"
                        <?= $edit_user['status'] == 'Active'
                            ? 'selected'
                            : ''; ?>>
                        Active
                    </option>

                    <option
                        value="Offline"
                        <?= $edit_user['status'] == 'Offline'
                            ? 'selected'
                            : ''; ?>>
                        Offline
                    </option>

                </select>

            </div>

        </div>


        <button
            type="submit"
            name="update_user"
            class="update-btn">
            Save Changes
        </button>


        <a
            href="<?= htmlspecialchars(
                $_SERVER['PHP_SELF']
            ); ?>"
            class="cancel-btn">
            Cancel
        </a>

    </form>


<?php } else { ?>


    <!-- =================================================
         SEARCH + ADD USER
    ================================================== -->

    <div class="user-tools">

        <div class="search-box">

            <input
                type="text"
                id="searchUser"
                placeholder="🔍 Search user..."
                onkeyup="searchUsers()"
            >

        </div>


        <button
            type="button"
            class="add-user-btn"
            onclick="showAddUser()">

            + Add User

        </button>

    </div>


    <!-- =================================================
         ADD USER FORM
    ================================================== -->

    <div
        id="addUserForm"
        style="display:none; margin-top:20px;">

        <h3>
            Add New User
        </h3>


        <form method="POST">

            <div class="form-row">

                <div class="form-group">

                    <label>
                        Full Name
                    </label>

                    <input
                        type="text"
                        name="fullname"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        required
                    >

                </div>

            </div>


            <div class="form-row">

                <div class="form-group">

                    <label>
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Role
                    </label>

                    <select name="role">

                        <option value="admin">
                            Admin
                        </option>

                        <option value="custodian">
                            Custodian
                        </option>

                        <option value="encoder">
                            Encoder
                        </option>

                        <option value="supply_officer">
                            Supply Officer
                        </option>

                    </select>

                </div>

            </div>


            <button
                type="submit"
                name="add_user"
                class="create-btn">

                Create User

            </button>


            <button
                type="button"
                class="cancel-btn"
                onclick="hideAddUser()">

                Cancel

            </button>

        </form>

    </div>


<?php } ?>


</div>


<!-- =====================================================
     USERS TABLE
===================================================== -->

<table id="usersTable">

    <thead>

        <tr>

            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created</th>
            <th>Last Login</th>
            <th>Status</th>
            <th>Action</th>

        </tr>

    </thead>


    <tbody>

    <?php while($row = $result->fetch_assoc()){ ?>

        <tr>

            <td>
                <?= (int)$row['id']; ?>
            </td>


            <td>
                <?= htmlspecialchars(
                    $row['fullname']
                ); ?>
            </td>


            <td>
                <?= htmlspecialchars(
                    $row['email']
                ); ?>
            </td>


            <td>
                <?= htmlspecialchars(
                    $row['role']
                ); ?>
            </td>


            <td>
                <?= htmlspecialchars(
                    $row['created_at']
                ); ?>
            </td>


            <td>

                <?= $row['last_login']
                    ? htmlspecialchars(
                        $row['last_login']
                    )
                    : 'Never';
                ?>

            </td>


            <td>

                <?php if(
                    $row['status'] == "Active"
                ){ ?>

                    <span
                        style="
                        color:green;
                        font-weight:bold;
                        ">
                        🟢 Active
                    </span>

                <?php } else { ?>

                    <span
                        style="
                        color:red;
                        font-weight:bold;
                        ">
                        🔴 Offline
                    </span>

                <?php } ?>

            </td>


            <td>

                <a
                    class="edit"
                    href="?edit=<?= (int)$row['id']; ?>">

                    Edit

                </a>


                <span class="action-separator">
                    |
                </span>


                <?php if(
                    $row['id'] != $_SESSION['user_id']
                ){ ?>

                    <a
                        class="delete"
                        href="?delete=<?= (int)$row['id']; ?>"
                        onclick="
                        return confirm(
                        'Are you sure you want to delete this user?'
                        );
                        ">

                        Delete

                    </a>

                <?php } else { ?>

                    <span style="color:#999;">
                        Current User
                    </span>

                <?php } ?>

            </td>

        </tr>

    <?php } ?>

    </tbody>

</table>


</div>


<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script>

function showAddUser(){

    document.getElementById("addUserForm").style.display = "block";

}


function hideAddUser(){

    document.getElementById("addUserForm").style.display = "none";

}


function searchUsers(){

    let search = document
        .getElementById("searchUser")
        .value
        .toLowerCase()
        .trim();


    let rows = document.querySelectorAll(
        "#usersTable tbody tr"
    );


    rows.forEach(function(row){

        let text = row.innerText.toLowerCase();


        if(text.includes(search)){

            row.style.display = "";

        }else{

            row.style.display = "none";

        }

    });

}

</script>


</body>

</html>
