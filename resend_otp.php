<?php
session_start();
include "config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (!isset($_SESSION['email'])) {
    die("Session expired. Please login again.");
}

$email = $_SESSION['email'];

// Generate new OTP
$otp = rand(100000, 999999);
$expire = date("Y-m-d H:i:s", strtotime("+5 minutes"));

// Save OTP
$stmt = $conn->prepare("UPDATE users SET otp_code=?, otp_expire=? WHERE email=?");
$stmt->bind_param("sss", $otp, $expire, $email);
$stmt->execute();

// Send Email
$mail = new PHPMailer(true);

try {

    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'johnirishcruzragot@gmail.com';
    $mail->Password   = 'cfng yqei oivv uppj';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('johnirishcruzragot@gmail.com', 'DICT IMS');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Inventory System OTP Verification";

    $mail->Body = "
    <h2>Email Verification</h2>

    <p>Your new OTP code is:</p>

    <h1 style='letter-spacing:5px;'>$otp</h1>

    <p>This code expires in 5 minutes.</p>
    ";

    $mail->send();

    $_SESSION['success'] = "A new OTP has been sent to your email.";

} catch (Exception $e) {

    $_SESSION['error'] = "Unable to resend OTP.";

}

header("Location: verify.php");
exit();