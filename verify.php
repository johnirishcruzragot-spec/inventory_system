<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config.php";


if(!isset($_SESSION['email'])){
    die("Session expired. Please login again.");
}



if(isset($_POST['verify'])){


    $email = $_SESSION['email'];
    $code = trim($_POST['code']);


    $sql = $conn->prepare("
        SELECT * FROM users
        WHERE email=?
        AND otp_code=?
    ");


    $sql->bind_param(
        "ss",
        $email,
        $code
    );


    $sql->execute();


    $result = $sql->get_result();



    if($result->num_rows > 0){


        $user = $result->fetch_assoc();


        if(strtotime($user['otp_expire']) < time()){

            echo "<script>alert('OTP expired.');</script>";
            exit();

        }



        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];



        $update = $conn->prepare("
            UPDATE users
            SET
            status='Active',
            last_login=NOW(),
            otp_code=NULL,
            otp_expire=NULL
            WHERE id=?
        ");


        $update->bind_param(
            "i",
            $user['id']
        );


        $update->execute();



switch($role){

    case "super_admin":
        header("Location: super_admin/dashboard.php");
        exit();

    case "admin":
        header("Location: admin/dashboard.php");
        exit();

    case "supply_officer":
        header("Location: supply_officer/dashboard.php");
        exit();

    case "custodian":
        header("Location: custodian/dashboard.php");
        exit();

    default:
        header("Location: encoder/dashboard.php");
        exit();

}


        exit();


    }else{

        $error = "Invalid OTP code.";

    }

}

?>

<!DOCTYPE html>
<html>
<head>

<title>Email Verification</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
}


body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background: #d6deff;
    background: linear-gradient(188deg, rgba(214, 222, 255, 1) 0%, rgba(255, 255, 255, 1) 51%, rgba(217, 251, 255, 1) 100%);
}



.container{

    width:400px;
    background:white;
    padding:40px;
    border-radius:20px;
    box-shadow:0 20px 50px rgba(0,0,0,0.25);
    text-align:center;

}



.icon{

    width:80px;
    height:80px;
    background:#007bff;
    color:white;
    border-radius:50%;
    margin:0 auto 20px;

    display:flex;
    justify-content:center;
    align-items:center;

    font-size:35px;

}



h2{

    color:#0056b3;
    margin-bottom:15px;

}



p{

    color:#666;
    margin-bottom:25px;
    font-size:15px;

}



input{

    width:100%;
    padding:15px;

    border:1px solid #ccc;
    border-radius:10px;

    font-size:18px;
    text-align:center;

    letter-spacing:8px;

    outline:none;

}



input:focus{

    border-color:#007bff;
    box-shadow:0 0 10px rgba(0,123,255,.3);

}



button{

    margin-top:25px;

    width:100%;
    padding:15px;

    background:#007bff;

    color:white;

    border:none;

    border-radius:10px;

    font-size:17px;

    cursor:pointer;

    transition:.3s;

}



button:hover{

    background:#0056b3;

}



.error{

    background:#ffdddd;
    color:#d00000;

    padding:10px;

    border-radius:8px;

    margin-bottom:20px;

}


.note{

    margin-top:20px;

    font-size:13px;

    color:#888;

}

.resend{
    display:block;
    margin-top:15px;
    color:#007bff;
    text-decoration:none;
    font-size:14px;
    font-weight:bold;
}

.resend:hover{
    color:#0056b3;
    text-decoration:underline;
}

.success{
    background:#d4edda;
    color:#155724;
    padding:10px;
    border-radius:8px;
    margin-bottom:20px;
}

</style>

</head>


<body>


<div class="container">


<div class="icon">
✉
</div>


<h2>Email Verification</h2>


<p>
Enter the 6-digit code sent to your email.
</p>


<?php

if(isset($_SESSION['success'])){
    echo "<div class='success'>".$_SESSION['success']."</div>";
    unset($_SESSION['success']);
}

if(isset($_SESSION['error'])){
    echo "<div class='error'>".$_SESSION['error']."</div>";
    unset($_SESSION['error']);
}

if(isset($error)){
    echo "<div class='error'>$error</div>";
}

?>


<form method="POST">


<input
type="text"
name="code"
maxlength="6"
placeholder="000000"
required>


<button type="submit" name="verify">
    Verify OTP
</button>

<a href="resend_otp.php" class="resend">
    Didn't receive the code? Resend OTP
</a>


</form>


<div class="note">

OTP expires after 5 minutes.

</div>


</div>


</body>
</html>