<?php

// ======================================
// USERS
// ======================================

$result = safeQuery($conn, "
SELECT *
FROM users
ORDER BY id DESC
");

// ======================================
// PERSONNEL
// ======================================

$personnel_result = safeQuery($conn, "
SELECT *
FROM personnel
ORDER BY id DESC
");

// ======================================
// DESIGNATIONS
// ======================================

$designation_result = safeQuery($conn, "
SELECT *
FROM designations
ORDER BY id DESC
");

// ======================================
// ARTICLES
// ======================================

$article_result = safeQuery($conn, "
SELECT *
FROM articles
ORDER BY id DESC
");

// ======================================
// INVENTORY
// ======================================

$inventory = safeQuery($conn,"
SELECT
    inventory.*,
    articles.article_name,
    articles.category AS article_category,
    personnel.fullname AS accountable_officer_name
FROM inventory
LEFT JOIN articles
    ON inventory.article_id = articles.id
LEFT JOIN personnel
    ON inventory.accountable_officer_id = personnel.id
ORDER BY inventory.id DESC
");

// ======================================
// INVENTORY STATUS
// ======================================

$statusQuery = safeQuery($conn,"
SELECT
    `condition`,
    COUNT(*) total
FROM inventory
GROUP BY `condition`
");

// ======================================
// BORROW REQUESTS
// ======================================

$borrowing = safeQuery($conn,"
SELECT

    br.id,
    br.request_no,
    br.borrow_date,
    br.expected_return,
    br.status,
    br.created_at,

    p.fullname,

    bri.quantity,

    a.article_name

FROM borrow_requests br

LEFT JOIN personnel p
    ON br.borrower_id = p.id

LEFT JOIN borrow_request_items bri
    ON br.id = bri.request_id

LEFT JOIN articles a
    ON bri.article_id = a.id

ORDER BY br.id DESC
");

// ======================================
// TOTAL BORROW REQUESTS
// ======================================

$totalBorrowing = safeQuery($conn, "
    SELECT COUNT(*) AS total
    FROM borrow_requests
")->fetch_assoc()['total'];

// ======================================
// STATUS COUNTS
// ======================================

$statusList = [
    "Pending",
    "Pending Review",
    "Custodian Approved",
    "Admin Approved",
    "Ready for Release",
    "Borrowed",
    "Return Requested",
    "Inspection",
    "Completed",
    "Rejected"
];

$statusCount = [];

foreach ($statusList as $status) {

    $stmt = $conn->prepare("
    SELECT COUNT(*) total
    FROM borrow_requests
    WHERE status=?
    ");

    $stmt->bind_param("s",$status);
    $stmt->execute();

    $statusCount[$status] =
        $stmt->get_result()->fetch_assoc()['total'];

    $stmt->close();
}

// ======================================
// INVENTORY TOTALS
// ======================================

$totalEquipment = safeQuery($conn,"
SELECT COUNT(*) total
FROM inventory
")->fetch_assoc()['total'];

$availableEquipment = safeQuery($conn,"
SELECT COUNT(*) total
FROM inventory
WHERE equipment_status='Available'
")->fetch_assoc()['total'];

$reservedEquipment = safeQuery($conn,"
SELECT COUNT(*) total
FROM inventory
WHERE equipment_status='Reserved'
")->fetch_assoc()['total'];

$borrowedEquipment = safeQuery($conn,"
SELECT COUNT(*) total
FROM inventory
WHERE equipment_status='Borrowed'
")->fetch_assoc()['total'];

$repairEquipment = safeQuery($conn,"
SELECT COUNT(*) total
FROM inventory
WHERE equipment_status='Repair'
")->fetch_assoc()['total'];

$lostEquipment = safeQuery($conn,"
SELECT COUNT(*) total
FROM inventory
WHERE equipment_status='Lost'
")->fetch_assoc()['total'];

$disposedEquipment = safeQuery($conn,"
SELECT COUNT(*) total
FROM inventory
WHERE equipment_status='Disposed'
")->fetch_assoc()['total'];

$serviceableEquipment = safeQuery($conn,"
SELECT COUNT(*) total
FROM inventory
WHERE `condition`='Serviceable'
")->fetch_assoc()['total'];

$unserviceableEquipment = safeQuery($conn,"
SELECT COUNT(*) total
FROM inventory
WHERE `condition`='Unserviceable'
")->fetch_assoc()['total'];

$missingEquipment = safeQuery($conn,"
SELECT COUNT(*) total
FROM inventory
WHERE `condition`='Missing'
")->fetch_assoc()['total'];

$inventoryValue = safeQuery($conn,"
SELECT IFNULL(SUM(unit_value),0) total
FROM inventory
")->fetch_assoc()['total'];

// ======================================
// BORROW REQUEST DASHBOARD COUNTS
// ======================================

$pendingBorrowing            = $statusCount['Pending'] ?? 0;
$pendingReviewBorrowing      = $statusCount['Pending Review'] ?? 0;
$custodianApprovedBorrowing  = $statusCount['Custodian Approved'] ?? 0;
$adminApprovedBorrowing      = $statusCount['Admin Approved'] ?? 0;
$readyForReleaseBorrowing    = $statusCount['Ready for Release'] ?? 0;
$borrowedBorrowing           = $statusCount['Borrowed'] ?? 0;
$returnRequestedBorrowing    = $statusCount['Return Requested'] ?? 0;
$inspectionBorrowing         = $statusCount['Inspection'] ?? 0;
$completedBorrowing          = $statusCount['Completed'] ?? 0;
$rejectedBorrowing           = $statusCount['Rejected'] ?? 0;

// ======================================
// USERS
// ======================================

$totalUsers = safeQuery($conn,"
SELECT COUNT(*) total
FROM users
")->fetch_assoc()['total'];

$superAdminCount = safeQuery($conn,"
SELECT COUNT(*) total
FROM users
WHERE role='super_admin'
")->fetch_assoc()['total'];

$adminCount = safeQuery($conn,"
SELECT COUNT(*) total
FROM users
WHERE role='admin'
")->fetch_assoc()['total'];

$supplyOfficerCount = safeQuery($conn,"
SELECT COUNT(*) total
FROM users
WHERE role='supply_officer'
")->fetch_assoc()['total'];

$custodianCount = safeQuery($conn,"
SELECT COUNT(*) total
FROM users
WHERE role='custodian'
")->fetch_assoc()['total'];

$encoderCount = safeQuery($conn,"
SELECT COUNT(*) total
FROM users
WHERE role='encoder'
")->fetch_assoc()['total'];

$viewerCount = safeQuery($conn,"
SELECT COUNT(*) total
FROM users
WHERE role='viewer'
")->fetch_assoc()['total'];

// ======================================
// TODAY / MONTH BORROWING
// ======================================

$todayBorrowing = safeQuery($conn,"
SELECT COUNT(*) total
FROM borrow_requests
WHERE DATE(created_at)=CURDATE()
")->fetch_assoc()['total'];

$thisMonthBorrowing = safeQuery($conn,"
SELECT COUNT(*) total
FROM borrow_requests
WHERE MONTH(created_at)=MONTH(CURDATE())
AND YEAR(created_at)=YEAR(CURDATE())
")->fetch_assoc()['total'];

$borrow_requests = $pendingReviewBorrowing;
$borrow_requests = $custodianApprovedBorrowing;
$borrow_requests = $adminApprovedBorrowing;

// ======================================
// OTHER TOTALS
// ======================================

$totalPersonnel = safeQuery($conn,"
SELECT COUNT(*) total
FROM personnel
WHERE archived='No'
")->fetch_assoc()['total'];

$totalArticles = safeQuery($conn,"
SELECT COUNT(*) total
FROM articles
")->fetch_assoc()['total'];

?>