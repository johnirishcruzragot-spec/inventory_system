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

    $_SESSION['error'] = "Invalid request.";

    header("Location: dashboard.php?page=validation");
    exit();

}

if (
    !isset($_POST['inventory']) ||
    !is_array($_POST['inventory']) ||
    count($_POST['inventory']) == 0
) {

    $_SESSION['error'] = "Please select returned equipment.";

    header("Location: return_equipment.php?id=".$requestID);
    exit();

}

$returnedItems = array_map('intval', $_POST['inventory']);

$conditions = $_POST['condition'] ?? [];

$remarks = trim($_POST['remarks'] ?? '');

$conn->begin_transaction();

try {

    foreach ($returnedItems as $inventoryID) {

        $condition = $conditions[$inventoryID] ?? "Serviceable";

        // ==========================================
        // UPDATE BORROWED ITEM
        // ==========================================

        $stmt = $conn->prepare("
        UPDATE borrowed_items
        SET
            returned_at = NOW(),
            returned_by = ?,
            condition_after = ?,
            remarks = ?
        WHERE
            request_id = ?
        AND
            inventory_id = ?
        AND
            returned_at IS NULL
        ");

        if (!$stmt) {
            throw new Exception($conn->error);
        }

        $stmt->bind_param(
            "issii",
            $userID,
            $condition,
            $remarks,
            $requestID,
            $inventoryID
        );

        $stmt->execute();

        $stmt->close();

        // ==========================================
        // SEND ITEM TO INSPECTION
        // ==========================================

        $equipmentStatus = "Inspection";

        $stmt = $conn->prepare("
        UPDATE inventory
        SET equipment_status = ?
        WHERE id = ?
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

    }

    // ==========================================
    // UPDATE REQUEST STATUS
    // ==========================================

    $status = "Inspection";

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
        $status,
        $requestID
    );

    $stmt->execute();

    $stmt->close();

    // ==========================================
    // BORROW LOG
    // ==========================================

    $action = "Equipment Returned";

    $logRemarks = "Equipment returned and awaiting inspection.";

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

    $title = "Equipment Returned";

    $message = "Borrow request is awaiting inspection.";

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

    $_SESSION['success'] = "Equipment returned successfully and sent for inspection.";

} catch (Exception $e) {

    $conn->rollback();

    $_SESSION['error'] = $e->getMessage();

}

header("Location: dashboard.php?page=validation");
exit();