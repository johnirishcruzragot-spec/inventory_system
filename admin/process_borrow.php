<?php

session_start();

include "../config.php";
include "includes/auth.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['request_id'])) {

    $_SESSION['error'] = "Invalid borrowing request.";

    header("Location: dashboard.php?page=validation");
    exit();

}

$requestID = (int)$_POST['request_id'];
$userID    = (int)$_SESSION['user_id'];

$conn->begin_transaction();

try {

    // ==========================================
    // LOAD REQUEST
    // ==========================================

    $stmt = $conn->prepare("
        SELECT
            br.id,
            br.request_no,
            br.status,
            bri.article_id,
            bri.quantity
        FROM borrow_requests br
        INNER JOIN borrow_request_items bri
            ON bri.request_id = br.id
        WHERE br.id = ?
        LIMIT 1
    ");

    if (!$stmt) {
        throw new Exception($conn->error);
    }

    $stmt->bind_param("i", $requestID);
    $stmt->execute();

    $result = $stmt->get_result();

    if (!$row = $result->fetch_assoc()) {
        throw new Exception("Borrow request not found.");
    }

    $stmt->close();

    $requestNo    = $row['request_no'];
    $currentStatus = $row['status'];
    $articleID     = (int)$row['article_id'];
    $quantity      = (int)$row['quantity'];

    // ==========================================
    // NEXT WORKFLOW STATUS
    // ==========================================

    switch ($currentStatus) {

        case "Pending":
            $nextStatus = "Pending Review";
            $action = "Pending Review";
            $remarks = "Borrow request reviewed.";
        break;

        case "Pending Review":
            $nextStatus = "Custodian Approved";
            $action = "Custodian Approval";
            $remarks = "Approved by Custodian.";
        break;

        case "Custodian Approved":
            $nextStatus = "Admin Approved";
            $action = "Admin Approval";
            $remarks = "Approved by Administrator.";
        break;

        case "Admin Approved":
            $nextStatus = "Ready for Release";
            $action = "Ready for Release";
            $remarks = "Equipment ready for release.";
        break;

        case "Ready for Release":
            $nextStatus = "Released";
            $action = "Equipment Released";
            $remarks = "Equipment released to borrower.";
        break;

        case "Released":
            $nextStatus = "Return Requested";
            $action = "Return Requested";
            $remarks = "Borrower requested return.";
        break;

        case "Return Requested":
            $nextStatus = "Inspection";
            $action = "Inspection";
            $remarks = "Equipment under inspection.";
        break;

        case "Inspection":
            $nextStatus = "Completed";
            $action = "Completed";
            $remarks = "Borrowing completed.";
        break;

        default:
            throw new Exception("This request can no longer be processed.");

    }

    // ==========================================
    // UPDATE REQUEST STATUS
    // ==========================================

    $stmt = $conn->prepare("
        UPDATE borrow_requests
        SET status = ?
        WHERE id = ?
    ");

    if (!$stmt) {
        throw new Exception($conn->error);
    }

    $stmt->bind_param(
        "si",
        $nextStatus,
        $requestID
    );

    $stmt->execute();
    $stmt->close();

    // ==========================================
    // SAVE BORROW LOG
    // ==========================================

    $log = $conn->prepare("
        INSERT INTO borrowing_logs
        (
            request_id,
            user_id,
            action,
            remarks
        )
        VALUES
        (
            ?, ?, ?, ?
        )
    ");

    if (!$log) {
        throw new Exception($conn->error);
    }

    $log->bind_param(
        "iiss",
        $requestID,
        $userID,
        $action,
        $remarks
    );

    $log->execute();
    $log->close();

    // ==========================================
    // CREATE NOTIFICATION
    // ==========================================

    $title = "Borrow Request Updated";

    $message = "{$requestNo} moved to {$nextStatus}.";

    $notify = $conn->prepare("
        INSERT INTO borrow_notifications
        (
            user_id,
            request_id,
            title,
            message
        )
        VALUES
        (
            ?, ?, ?, ?
        )
    ");

    if ($notify) {

        $notify->bind_param(
            "iiss",
            $userID,
            $requestID,
            $title,
            $message
        );

        $notify->execute();
        $notify->close();

    }

    $conn->commit();

    $_SESSION['success'] = "Borrow request moved to {$nextStatus}.";

} catch (Exception $e) {

    $conn->rollback();

    $_SESSION['error'] = $e->getMessage();

}

header("Location: dashboard.php?page=validation");
exit();