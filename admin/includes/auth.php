<?php

include "../config.php";

if(
    $_SESSION['role'] != 'admin' &&
    $_SESSION['role'] != 'super_admin'
){
    header("Location: ../index.php");
    exit;
}

$currentPage = "dashboard";

$message = "";