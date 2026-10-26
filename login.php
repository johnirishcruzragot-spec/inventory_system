<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config.php";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = $conn->prepare("SELECT * FROM users WHERE email=?");
    $sql->bind_param("s", $email);
    $sql->execute();

    $result = $sql->get_result();

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            $update = $conn->prepare("
                UPDATE users
                SET
                    last_login = NOW(),
                    otp_code = NULL,
                    otp_expire = NULL
                WHERE id = ?
            ");

            $update->bind_param("i", $user['id']);
            $update->execute();

            switch ($user['role']) {

                case "super_admin":
                    header("Location: super_admin/dashboard.php");
                    break;

                case "admin":
                    header("Location: admin/dashboard.php");
                    break;

                case "supply_officer":
                    header("Location: supply_officer/dashboard.php");
                    break;

                case "custodian":
                    header("Location: custodian/dashboard.php");
                    break;

                case "viewer":
                    header("Location: viewer/dashboard.php");
                    break;

                default:
                    header("Location: encoder/dashboard.php");
                    break;
            }

            exit();

        } else {

            echo "Wrong Password.";

        }

    } else {

        echo "Email not found.";

    }

}
?>