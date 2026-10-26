<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "../config.php";

require_once "../PHPMailer/src/Exception.php";
require_once "../PHPMailer/src/PHPMailer.php";
require_once "../PHPMailer/src/SMTP.php";


function sendBorrowEmail(

    $toEmail,
    $toName,
    $subject,
    $body

){

    $mail = new PHPMailer(true);

    try{

        $mail->isSMTP();

        $mail->Host="smtp.gmail.com";

        $mail->SMTPAuth=true;

        $mail->Username="YOUR_GMAIL@gmail.com";

        $mail->Password="YOUR_APP_PASSWORD";

        $mail->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port=587;



        $mail->setFrom(

            "YOUR_GMAIL@gmail.com",

            "DICT Inventory Management System"

        );



        $mail->addAddress(

            $toEmail,

            $toName

        );



        $mail->isHTML(true);

        $mail->Subject=$subject;

        $mail->Body=$body;



        return $mail->send();

    }

    catch(Exception $e){

        return false;

    }

}