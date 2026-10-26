<?php

// ======================================================
// BORROWING V2
// STEP 1
// SAVE / UPDATE BORROW REQUEST
// ======================================================

if (
    $_SERVER['REQUEST_METHOD'] == 'POST'
    &&
    (
        isset($_POST['save_borrowing'])
        ||
        isset($_POST['article_id'])
    )
) {

    $borrowID = (int)($_POST['borrow_id'] ?? 0);

    $articleID = (int)$_POST['article_id']; 
    $borrowerID = (int)$_POST['borrower_id'];

    $quantity = max(1, (int)$_POST['quantity']);

    $purpose = trim($_POST['purpose']);
    $remarks = trim($_POST['remarks']);

    $borrowDate = $_POST['borrow_date'];

    $expectedReturn = !empty($_POST['expected_return'])
        ? $_POST['expected_return']
        : NULL;

    $permanentRelease = $_POST['permanent_release'] ?? "No";



    // ============================================
    // VALIDATE PERSONNEL
    // ============================================

    $stmt = $conn->prepare("
        SELECT id
        FROM personnel
        WHERE id=?
        LIMIT 1
    ");

    $stmt->bind_param("i",$borrowerID);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows==0){

        $_SESSION['error']="Borrower not found.";

        header("Location: dashboard.php?page=validation");
        exit();

    }

    $stmt->close();



    // ============================================
    // VALIDATE DATE
    // ============================================

    if(
        $permanentRelease=="No"
        &&
        !empty($expectedReturn)
        &&
        $expectedReturn < $borrowDate
    ){

        $_SESSION['error']="Expected return date is invalid.";

        header("Location: dashboard.php?page=validation");
        exit();

    }



    // ============================================
    // NEW REQUEST
    // ============================================

    if($borrowID==0){

        $status="Pending Review";

        $workflowStep=1;

        $stmt=$conn->prepare("

            INSERT INTO borrow_requests(

                borrower_id,
                purpose,
                remarks,
                borrow_date,
                expected_return,
                permanent_release,
                status,
                workflow_step

            )

            VALUES(

                ?,?,?,?,?,?,?,?

            )

        ");

        $stmt->bind_param(

            "issssssi",

            $borrowerID,
            $purpose,
            $remarks,
            $borrowDate,
            $expectedReturn,
            $permanentRelease,
            $status,
            $workflowStep

        );

        $stmt->execute();

        $requestID=$conn->insert_id;

        $stmt->close();



        // Generate Request Number

        $requestNo="BR-".date("Y")."-".str_pad($requestID,6,"0",STR_PAD_LEFT);

        $stmt=$conn->prepare("

            UPDATE borrow_requests

            SET request_no=?

            WHERE id=?

        ");

        $stmt->bind_param(

            "si",

            $requestNo,
            $requestID

        );

        $stmt->execute();

        $stmt->close();



        // Save Requested Item

        $stmt=$conn->prepare("

            INSERT INTO borrow_request_items(

                request_id,
                article_id,
                quantity

            )

            VALUES(

                ?,?,?

            )

        ");

        $stmt->bind_param(

            "iii",

            $requestID,
            $articleID,
            $quantity

        );

        $stmt->execute();

        $stmt->close();



        // Save Log

        $action="Request Submitted";

        $log="Borrow request submitted.";

        $stmt=$conn->prepare("

            INSERT INTO borrowing_logs(

                request_id,
                user_id,
                action,
                remarks

            )

            VALUES(

                ?,?,?,?

            )

        ");

        $stmt->bind_param(

            "iiss",

            $requestID,
            $_SESSION['user_id'],
            $action,
            $log

        );

        $stmt->execute();

        $stmt->close();



        $_SESSION['success']="Borrow Request Successfully Submitted.";

    }



    // ============================================
    // UPDATE REQUEST
    // ============================================

    else{

        $stmt=$conn->prepare("

            UPDATE borrow_requests

            SET

                borrower_id=?,
                purpose=?,
                remarks=?,
                borrow_date=?,
                expected_return=?,
                permanent_release=?

            WHERE id=?

        ");

        $stmt->bind_param(

            "isssssi",

            $borrowerID,
            $purpose,
            $remarks,
            $borrowDate,
            $expectedReturn,
            $permanentRelease,
            $borrowID

        );

        $stmt->execute();

        $stmt->close();



        $stmt=$conn->prepare("

            UPDATE borrow_request_items

            SET

                article_id=?,
                quantity=?

            WHERE request_id=?

        ");

        $stmt->bind_param(

            "iii",

            $articleID,
            $quantity,
            $borrowID

        );

        $stmt->execute();

        $stmt->close();



        $_SESSION['success']="Borrow Request Updated.";

    }



    header("Location: dashboard.php?page=validation");
    exit();

}

// ======================================================
// BORROWING V2
// STEP 2
// PROCESS BORROWING WORKFLOW
// ======================================================

if ($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['process_request'])){

    $requestID=(int)$_POST['request_id'];

    $remarks=trim($_POST['remarks'] ?? "");

    $stmt=$conn->prepare("

        SELECT *

        FROM borrow_requests

        WHERE id=?

        LIMIT 1

    ");

    $stmt->bind_param("i",$requestID);

    $stmt->execute();

    $request=$stmt->get_result()->fetch_assoc();

    $stmt->close();

    if(!$request){

        $_SESSION['error']="Borrow request not found.";

        header("Location: dashboard.php?page=validation");

        exit();

    }

    $currentStatus=$request['status'];

    $workflowStep=(int)$request['workflow_step'];

    $permanentRelease=$request['permanent_release'];


// =====================================
// REJECT BORROW REQUEST
// =====================================

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reject_request'])) {

    $requestID = (int)$_POST['request_id'];

    $remarks = trim($_POST['remarks']);

    $stmt = $conn->prepare("
        UPDATE borrow_requests
        SET status='Rejected'
        WHERE id=?
    ");

    $stmt->bind_param("i",$requestID);
    $stmt->execute();
    $stmt->close();

    // Save approval history

    $stmt = $conn->prepare("
        INSERT INTO borrow_approvals
        (
            request_id,
            approver_id,
            role,
            approval_step,
            decision,
            remarks,
            approved_at
        )
        VALUES
        (
            ?, ?, ?, ?, 'Rejected', ?, NOW()
        )
    ");

    $step = "Rejected";

    $stmt->bind_param(

        "iisss",

        $requestID,
        $_SESSION['user_id'],
        $_SESSION['role'],
        $step,
        $remarks

    );

    $stmt->execute();

    $stmt->close();

    $_SESSION['success']="Borrow Request Rejected.";

    header("Location: dashboard.php?page=validation");

    exit();

}


// =====================================
// NEXT WORKFLOW
// =====================================

$decision = $_POST['decision'] ?? "Approved";

if ($decision == "Rejected") {

    $nextStatus = "Rejected";
    $nextStep   = $workflowStep;

} else {

    switch($workflowStep){

        case 1:
            $nextStatus = "Custodian Verification";
            $nextStep   = 2;
            break;

        case 2:
            $nextStatus = "Supply Officer Approval";
            $nextStep   = 3;
            break;

        case 3:
            $nextStatus = "Admin Approval";
            $nextStep   = 4;
            break;

        case 4:
            $nextStatus = "Ready for Release";
            $nextStep   = 5;
            break;

        case 5:
            $nextStatus = "Borrowed";
            $nextStep   = 6;
            break;

        case 6:

            if($permanentRelease=="Yes"){

                $nextStatus = "Completed";
                $nextStep   = 9;

            }else{

                $nextStatus = "Return Requested";
                $nextStep   = 7;

            }

            break;

        case 7:
            $nextStatus = "Inspection";
            $nextStep   = 8;
            break;

        case 8:
            $nextStatus = "Completed";
            $nextStep   = 9;
            break;

        default:
            $nextStatus = $currentStatus;
            $nextStep   = $workflowStep;
    }
}

    // =====================================
    // UPDATE REQUEST
    // =====================================

    $stmt=$conn->prepare("

        UPDATE borrow_requests

        SET

            status=?,

            workflow_step=?

        WHERE id=?

    ");

    $stmt->bind_param(

        "sii",

        $nextStatus,

        $nextStep,

        $requestID

    );

    $stmt->execute();

    $stmt->close();



    // =====================================
    // SAVE APPROVAL INFORMATION
    // =====================================

    switch($nextStep){

        case 2:

            $conn->query("

                UPDATE borrow_requests

                SET

                    supply_officer_id=".$_SESSION['user_id'].",

                    supply_approved_at=NOW()

                WHERE id=".$requestID."

            ");

            break;



        case 3:

            $conn->query("

                UPDATE borrow_requests

                SET

                    custodian_id=".$_SESSION['user_id'].",

                    custodian_approved_at=NOW()

                WHERE id=".$requestID."

            ");

            break;



        case 4:

            $conn->query("

                UPDATE borrow_requests

                SET

                    admin_id=".$_SESSION['user_id'].",

                    admin_approved_at=NOW()

                WHERE id=".$requestID."

            ");

            break;



        case 6:

            $conn->query("

                UPDATE borrow_requests

                SET

                    released_by=".$_SESSION['user_id'].",

                    released_at=NOW()

                WHERE id=".$requestID."

            ");

            break;



        case 9:

            $conn->query("

                UPDATE borrow_requests

                SET

                    inspected_by=".$_SESSION['user_id'].",

                    inspected_at=NOW()

                WHERE id=".$requestID."

            ");

            break;



        case 10:

            $conn->query("

                UPDATE borrow_requests

                SET

                    completed_at=NOW()

                WHERE id=".$requestID."

            ");

            break;

    }



    // =====================================
    // INVENTORY STATUS
    // =====================================

    if($nextStatus=="Borrowed"){

        $conn->query("

            UPDATE inventory

            SET equipment_status='Borrowed'

            WHERE article_id IN(

                SELECT article_id

                FROM borrow_request_items

                WHERE request_id=".$requestID."

            )

        ");

    }



    if($nextStatus=="Completed"){

        if($permanentRelease=="No"){

            $conn->query("

                UPDATE inventory

                SET equipment_status='Available'

                WHERE article_id IN(

                    SELECT article_id

                    FROM borrow_request_items

                    WHERE request_id=".$requestID."

                )

            ");

        }

    }



    // =====================================
    // LOGS
    // =====================================

    $action="Workflow";

    $log="Status changed from ".$currentStatus." to ".$nextStatus;

    $stmt=$conn->prepare("

        INSERT INTO borrowing_logs(

            request_id,

            user_id,

            action,

            remarks

        )

        VALUES(

            ?,?,?,?

        )

    ");

    $stmt->bind_param(

        "iiss",

        $requestID,

        $_SESSION['user_id'],

        $action,

        $log

    );

    $stmt->execute();

    $stmt->close();



    $_SESSION['success']="Borrow Request Updated.";

    header("Location: dashboard.php?page=validation");
    exit();

}

// =====================================
// SAVE APPROVAL HISTORY
// =====================================

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['process_request'])) {

    $approvalStep = "";

    switch($nextStatus){

        case "Supply Officer":
            $approvalStep = "Pending Review";
            break;

        case "Custodian":
            $approvalStep = "Supply Officer";
            break;

        case "Admin":
            $approvalStep = "Custodian";
            break;

        case "Ready for Release":
            $approvalStep = "Admin";
            break;

        case "Released":
            $approvalStep = "Release";
            break;

        case "Inspection":
            $approvalStep = "Return";
            break;

        case "Completed":
            $approvalStep = "Inspection";
            break;
    }

    $stmt = $conn->prepare("
        INSERT INTO borrow_approvals(
            request_id,
            approver_id,
            role,
            approval_step,
            decision,
            remarks,
            approved_at
        )
        VALUES(?,?,?,?,?,?,NOW())
    ");

    $decision = "Approved";

    $stmt->bind_param(
        "iissss",
        $requestID,
        $_SESSION['user_id'],
        $_SESSION['role'],
        $approvalStep,
        $decision,
        $remarks
    );

    $stmt->execute();
    $stmt->close();
}

// ==========================================
// SAVE BORROWING HISTORY
// ==========================================

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['process_request'])) {

    $stmt = $conn->prepare("
    INSERT INTO borrowing_history
    (
        borrowing_id,
        from_status,
        to_status,
        action,
        remarks,
        acted_by,
        acted_role
    )
    VALUES
    (
        ?, ?, ?, ?, ?, ?, ?
    )
    ");

    $fromStatus = "";
    $toStatus   = $status;

    $action = "Borrow Request Submitted";

    $historyRemarks =
        "Borrow request {$request_no} submitted by employee.";

    $actedBy = $_SESSION['user_id'];

    $actedRole = $_SESSION['role'];

    $stmt->bind_param(
        "issssis",
        $requestID,
        $fromStatus,
        $toStatus,
        $action,
        $historyRemarks,
        $actedBy,
        $actedRole
    );

    $stmt->execute();

    $stmt->close();
}