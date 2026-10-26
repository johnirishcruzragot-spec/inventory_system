<?php

session_start();

include "../config.php";
include "includes/auth.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: dashboard.php?page=validation");
    exit();

}

$requestID = (int)($_GET['id'] ?? 0);

$userID = $_SESSION['user_id'];

if ($requestID <= 0) {

    $_SESSION['error'] = "Invalid borrowing request.";

    header("Location: dashboard.php?page=validation");
    exit();

}

if (
    !isset($_POST['status']) ||
    !is_array($_POST['status']) ||
    count($_POST['status']) == 0
) {

    $_SESSION['error'] = "No inspection data received.";

    header("Location: inspection.php?id=".$requestID);
    exit();

}

$statusList = $_POST['status'];

$remarksList = $_POST['remarks'] ?? [];

$conn->begin_transaction();

try {

    foreach ($statusList as $inventoryID => $equipmentStatus) {

        $inventoryID = (int)$inventoryID;

        $remarks = trim($remarksList[$inventoryID] ?? "");

        // ==========================================
        // UPDATE INVENTORY STATUS
        // ==========================================

        $stmt = $conn->prepare("
        UPDATE inventory
        SET
            equipment_status = ?
        WHERE
            id = ?
        ");

        if (!$stmt) {
            throw new Exception($conn->error);
        }

        $stmt->bind_param(
            "si",
            $equipmentStatus,
            $inventoryID
        );

        $stmt->execute();

        $stmt->close();

        // ==========================================
        // UPDATE BORROWED ITEM
        // ==========================================

        $stmt = $conn->prepare("
        UPDATE borrowed_items
        SET
            remarks = ?
        WHERE
            request_id = ?
        AND
            inventory_id = ?
        ");

        if (!$stmt) {
            throw new Exception($conn->error);
        }

        $stmt->bind_param(
            "sii",
            $remarks,
            $requestID,
            $inventoryID
        );

        $stmt->execute();

        $stmt->close();

    }

    // ==========================================
    // COMPLETE BORROW REQUEST
    // ==========================================

    $status = "Completed";

    $stmt = $conn->prepare("
    UPDATE borrow_requests
    SET
        status = ?
    WHERE
        id = ?
    ");

    if (!$stmt) {
        throw new Exception($conn->error);
    }

    $stmt->bind_param(
        "si",
        $status,
        $requestID
    );

    $stmt->execute();

    $stmt->close();

    // ==========================================
    // BORROW LOG
    // ==========================================

    $action = "Inspection Completed";

    $logRemarks = "Equipment inspection completed.";

    $stmt = $conn->prepare("
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

    if (!$stmt) {
        throw new Exception($conn->error);
    }

    $stmt->bind_param(
        "iiss",
        $requestID,
        $userID,
        $action,
        $logRemarks
    );

    $stmt->execute();

    $stmt->close();

    // ==========================================
    // NOTIFICATION
    // ==========================================

    $title = "Borrowing Completed";

    $message = "Borrow request has been completed successfully.";

    $stmt = $conn->prepare("
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

    if (!$stmt) {
        throw new Exception($conn->error);
    }

    $stmt->bind_param(
        "iiss",
        $userID,
        $requestID,
        $title,
        $message
    );

    $stmt->execute();

    $stmt->close();

    $conn->commit();

    $_SESSION['success'] = "Inspection completed successfully.";

} catch (Exception $e) {

    $conn->rollback();

    $_SESSION['error'] = $e->getMessage();

}

header("Location: dashboard.php?page=validation");
exit();