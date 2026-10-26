<?php

/* ==========================================
   ADD USER
========================================== */

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {

    $fullname = trim($_POST['fullname']);
    $email    = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $division = trim($_POST['division']);
    $role     = trim($_POST['role']);

    if (empty($fullname)) {

        $message = "<div class='error'>Full name is required.</div>";

    } elseif (!$email) {

        $message = "<div class='error'>Invalid email address.</div>";

    } elseif (empty($password)) {

        $message = "<div class='error'>Password is required.</div>";

    } elseif (empty($division)) {

        $message = "<div class='error'>Division is required.</div>";

    } else {

        $check = $conn->prepare("
            SELECT id
            FROM users
            WHERE email=?
        ");

        $check->bind_param("s", $email);
        $check->execute();

        if ($check->get_result()->num_rows > 0) {

            $message = "<div class='error'>Email already exists.</div>";

        } else {

            $password = password_hash($password, PASSWORD_DEFAULT);

            $status = "active";

            $stmt = $conn->prepare("
                INSERT INTO users
                (
                    fullname,
                    email,
                    password,
                    division,
                    role,
                    status
                )
                VALUES (?,?,?,?,?,?)
            ");

            $stmt->bind_param(
                "ssssss",
                $fullname,
                $email,
                $password,
                $division,
                $role,
                $status
            );

            $stmt->execute();

            $stmt->close();

            header("Location: dashboard.php?page=users");
            exit();
        }

        $check->close();
    }
}


/* ==========================================
   UPDATE USER
========================================== */

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {

    $id       = (int)$_POST['id'];
    $fullname = trim($_POST['fullname']);
    $email    = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $division = trim($_POST['division']);
    $role     = trim($_POST['role']);
    $status   = trim($_POST['status']);
    $password = $_POST['password'];

    $check = $conn->prepare("
        SELECT id
        FROM users
        WHERE email=?
        AND id<>?
    ");

    $check->bind_param("si", $email, $id);
    $check->execute();

    if ($check->get_result()->num_rows > 0) {

        $message = "<div class='error'>Email already exists.</div>";

    } else {

        if (!empty($password)) {

            $password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("
                UPDATE users
                SET
                    fullname=?,
                    email=?,
                    password=?,
                    division=?,
                    role=?,
                    status=?
                WHERE id=?
            ");

            $stmt->bind_param(
                "ssssssi",
                $fullname,
                $email,
                $password,
                $division,
                $role,
                $status,
                $id
            );

        } else {

            $stmt = $conn->prepare("
                UPDATE users
                SET
                    fullname=?,
                    email=?,
                    division=?,
                    role=?,
                    status=?
                WHERE id=?
            ");

            $stmt->bind_param(
                "sssssi",
                $fullname,
                $email,
                $division,
                $role,
                $status,
                $id
            );
        }

        $stmt->execute();
        $stmt->close();

        header("Location: dashboard.php?page=users");
        exit();
    }

    $check->close();
}


/* ==========================================
   DELETE USER
========================================== */

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_user'])) {

    $id = (int)$_POST['delete_user_id'];

    if ($id != $_SESSION['user_id']) {

        $stmt = $conn->prepare("
            DELETE FROM users
            WHERE id=?
        ");

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: dashboard.php?page=users");
    exit();
}


/* ==========================================
   USER LIST
========================================== */

$result = $conn->query("
    SELECT
        id,
        fullname,
        email,
        division,
        role,
        created_at,
        last_login,
        status
    FROM users
    ORDER BY id DESC
");