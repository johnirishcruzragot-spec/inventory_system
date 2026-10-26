<?php

function getNextApprover($conn,$status){

    switch($status){

        case "Pending Review":

            $role="supply_officer";

            break;

        case "Supply Officer":

            $role="custodian";

            break;

        case "Custodian":

            $role="admin";

            break;

        default:

            return false;

    }

    $stmt=$conn->prepare("

        SELECT

            id,
            fullname,
            email

        FROM users

        WHERE

            role=?

        AND

            status='active'

        LIMIT 1

    ");

    $stmt->bind_param("s",$role);

    $stmt->execute();

    $result=$stmt->get_result();

    $stmt->close();

    if($result->num_rows==0){

        return false;

    }

    return $result->fetch_assoc();

}