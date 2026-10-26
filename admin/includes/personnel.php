<?php

/* ==========================================
   ADD PERSONNEL
========================================== */

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_personnel'])) {

    $employee_id = trim($_POST['employee_id']);
    $fullname = trim($_POST['fullname']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $contact_number = trim($_POST['contact_number']);
    $designation = trim($_POST['designation']);
    $position = trim($_POST['position']);
    $division = trim($_POST['division']);
    $employment_status = trim($_POST['employment_status']);
    $status = trim($_POST['status']);

    if (empty($employee_id)) {

        $message = "<div class='error'>Employee ID is required.</div>";

    } elseif (empty($fullname)) {

        $message = "<div class='error'>Full name is required.</div>";

    } elseif (!$email) {

        $message = "<div class='error'>Invalid email address.</div>";

    } elseif (empty($division)) {

        $message = "<div class='error'>Division is required.</div>";

    } else {

        // Check duplicate Employee ID
        $check = $conn->prepare("
            SELECT id
            FROM personnel
            WHERE employee_id=?
        ");

        $check->bind_param("s", $employee_id);
        $check->execute();

        if ($check->get_result()->num_rows > 0) {

            $message = "<div class='error'>Employee ID already exists.</div>";

        } else {

            $stmt = $conn->prepare("
                INSERT INTO personnel
                (
                    employee_id,
                    fullname,
                    email,
                    contact_number,
                    designation,
                    position,
                    division,
                    employment_status,
                    status
                )
                VALUES (?,?,?,?,?,?,?,?,?)
            ");

            $stmt->bind_param(
                "sssssssss",
                $employee_id,
                $fullname,
                $email,
                $contact_number,
                $designation,
                $position,
                $division,
                $employment_status,
                $status
            );

            $stmt->execute();
            $stmt->close();

            header("Location: dashboard.php?page=personnel");
            exit();
        }

        $check->close();
    }
}


/* ==========================================
   UPDATE PERSONNEL
========================================== */

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_personnel'])) {

    $id = (int)$_POST['id'];

    $employee_id = trim($_POST['employee_id']);
    $fullname = trim($_POST['fullname']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $contact_number = trim($_POST['contact_number']);
    $designation = trim($_POST['designation']);
    $position = trim($_POST['position']);
    $division = trim($_POST['division']);
    $employment_status = trim($_POST['employment_status']);
    $status = trim($_POST['status']);

    if (empty($employee_id)) {

        $message = "<div class='error'>Employee ID is required.</div>";

    } elseif (empty($fullname)) {

        $message = "<div class='error'>Full name is required.</div>";

    } elseif (!$email) {

        $message = "<div class='error'>Invalid email address.</div>";

    } elseif (empty($division)) {

        $message = "<div class='error'>Division is required.</div>";

    } else {

        // Check duplicate Employee ID
        $check = $conn->prepare("
            SELECT id
            FROM personnel
            WHERE employee_id=?
            AND id<>?
        ");

        $check->bind_param("si", $employee_id, $id);
        $check->execute();

        if ($check->get_result()->num_rows > 0) {

            $message = "<div class='error'>Employee ID already exists.</div>";

        } else {

            $stmt = $conn->prepare("
                UPDATE personnel
                SET
                    employee_id=?,
                    fullname=?,
                    email=?,
                    contact_number=?,
                    designation=?,
                    position=?,
                    division=?,
                    employment_status=?,
                    status=?
                WHERE id=?
            ");

            $stmt->bind_param(
                "sssssssssi",
                $employee_id,
                $fullname,
                $email,
                $contact_number,
                $designation,
                $position,
                $division,
                $employment_status,
                $status,
                $id
            );

            $stmt->execute();
            $stmt->close();

            header("Location: dashboard.php?page=personnel");
            exit();
        }

        $check->close();
    }
}


/* ==========================================
   PERSONNEL LIST
========================================== */

$personnel = $conn->query("
    SELECT *
    FROM personnel
    WHERE archived='No'
    ORDER BY fullname ASC
");