<?php

/**
 * ==========================================================
 * DICT IMS VERSION 2.0
 * Borrow Request Number Generator
 * Format:
 * BR-2026-000001
 * ==========================================================
 */

function generateRequestNumber(mysqli $conn): string
{
    $year = date("Y");

    $stmt = $conn->prepare("
        SELECT id
        FROM borrow_requests
        ORDER BY id DESC
        LIMIT 1
    ");

    if (!$stmt) {
        return "BR-{$year}-000001";
    }

    $stmt->execute();

    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        $nextID = (int)$row['id'] + 1;

    } else {

        $nextID = 1;

    }

    $stmt->close();

    return sprintf(
        "BR-%s-%06d",
        $year,
        $nextID
    );
}