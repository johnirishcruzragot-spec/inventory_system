<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config.php";

require "PHPMailer/src/PHPMailer.php";
require "PHPMailer/src/SMTP.php";
require "PHPMailer/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if (!isset($_SESSION['email'])) {
    die("Session expired. Please login again.");
}


$email = $_SESSION['email'];


// Get OTP
$stmt = $conn->prepare(
    "SELECT otp_code FROM users WHERE email=?"
);

$stmt->bind_param("s",$email);
$stmt->execute();

$result = $stmt->get_result();


if($result->num_rows == 0){
    die("User not found.");
}


$data = $result->fetch_assoc();

$otp = $data['otp_code'];



$mail = new PHPMailer(true);


try{


    $mail->isSMTP();

    $mail->Host = "smtp.gmail.com";

    $mail->SMTPAuth = true;


    // YOUR SYSTEM GMAIL
    $mail->Username = "johnirishcruzragot@gmail.com";

    // GOOGLE APP PASSWORD (NO SPACES)
    $mail->Password = "cfng yqei oivv uppj";


    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    $mail->Port = 587;



    // Sender
    $mail->setFrom(
        "johnirishragot28@gmail.com",
        "Inventory System"
    );


    // User registered email from database
    $mail->addAddress($email);



$mail->isHTML(true);

$mail->Subject = "DICT Inventory Management System - Verification Code";

$mail->Body = '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>

<body style="
margin:0;
padding:40px 0;
background:#f4f6f9;
font-family:Arial,Helvetica,sans-serif;
">

<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td align="center">

<table width="600" cellpadding="0" cellspacing="0"
style="
background:#ffffff;
border:1px solid #dddddd;
border-radius:10px;
overflow:hidden;
">

<tr>
<td
style="
background:#101820;
padding:25px;
text-align:center;
color:#ffffff;
">

<h2 style="margin:0;">
DICT Inventory Management System
</h2>

<p style="margin-top:8px;">
Asset Accountability System
</p>

</td>
</tr>

<tr>
<td style="padding:35px;">

<h3 style="margin-top:0;color:#101820;">
Hello,
</h3>

<p style="
font-size:15px;
line-height:1.7;
color:#555;
">
We received a request to sign in to your
<strong>DICT Inventory Management System</strong> account.
</p>

<p style="
font-size:15px;
line-height:1.7;
color:#555;
">
Use the verification code below to continue.
</p>

<div
style="
margin:30px auto;
width:230px;
padding:18px;
text-align:center;
background:#fff8f5;
border:2px dashed #e94804;
border-radius:8px;
">

<div
style="
font-size:38px;
font-weight:bold;
letter-spacing:10px;
color:#101820;
">
'.$otp.'
</div>

</div>

<p style="
font-size:15px;
color:#555;
">
This code will expire in
<strong>5 minutes</strong>.
</p>

<div
style="
background:#f8f9fa;
border-left:4px solid #e94804;
padding:15px;
margin-top:30px;
">

<strong>Security Notice</strong>

<p style="
margin:10px 0 0;
font-size:14px;
color:#666;
line-height:1.6;
">

If you did not request this verification code,
please ignore this email.
Your account will remain secure.

</p>

</div>

<hr style="
margin:35px 0;
border:none;
border-top:1px solid #eeeeee;
">

<p style="
font-size:12px;
text-align:center;
color:#888;
line-height:1.7;
">

Department of Information and Communications Technology<br>

Inventory Management System<br><br>

This is an automated email. Please do not reply.

</p>

</td>
</tr>

</table>

</td>
</tr>
</table>

</body>
</html>
';

$mail->AltBody =
"DICT Inventory Management System

Your verification code is:

".$otp."

This code expires in 5 minutes.

If you did not request this verification, ignore this email.";



    $mail->send();
    header("Location: verify.php");
    exit();



}catch(Exception $e){

    echo "Email Sending Failed:<br>";

    echo $mail->ErrorInfo;

}

?>