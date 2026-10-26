<?php

session_start();

include "../config.php";
include "includes/auth.php";

if (!isset($_GET['id'])) {

    $_SESSION['error'] = "Invalid request.";

    header("Location: dashboard.php?page=validation");
    exit();

}

$requestID = (int)$_GET['id'];

// ==========================================
// LOAD REQUEST
// ==========================================

$stmt = $conn->prepare("
SELECT
    br.request_no,
    br.status,
    p.fullname
FROM borrow_requests br

INNER JOIN personnel p
    ON br.borrower_id = p.id

WHERE br.id = ?
");

if (!$stmt) {
    die($conn->error);
}

$stmt->bind_param("i", $requestID);

$stmt->execute();

$request = $stmt->get_result()->fetch_assoc();

$stmt->close();

if (!$request) {

    $_SESSION['error'] = "Borrow request not found.";

    header("Location: dashboard.php?page=validation");
    exit();

}

// ==========================================
// LOAD RETURNED ITEMS
// ==========================================

$stmt = $conn->prepare("
SELECT

    bi.inventory_id,

    i.ics_no,

    i.property_no,

    i.serial_number,

    i.item_description,

    bi.condition_after,

    bi.remarks

FROM borrowed_items bi

INNER JOIN inventory i
    ON bi.inventory_id = i.id

WHERE

    bi.request_id = ?

AND

    bi.returned_at IS NOT NULL

ORDER BY i.property_no
");

if (!$stmt) {
    die($conn->error);
}

$stmt->bind_param("i", $requestID);

$stmt->execute();

$list = $stmt->get_result();

$stmt->close();

?>

<h2>Equipment Inspection</h2>

<table class="release-info">

<tr>

<th>Request No.</th>

<td><?= htmlspecialchars($request['request_no']) ?></td>

</tr>

<tr>

<th>Borrower</th>

<td><?= htmlspecialchars($request['fullname']) ?></td>

</tr>

<tr>

<th>Status</th>

<td><?= htmlspecialchars($request['status']) ?></td>

</tr>

</table>

<form
method="POST"
action="save_inspection.php?id=<?= $requestID ?>">

<table class="release-table">

<tr>

<th>ICS</th>

<th>Property No.</th>

<th>Serial No.</th>

<th>Description</th>

<th>Returned Condition</th>

<th>Final Status</th>

<th>Inspector Remarks</th>

</tr>

<?php while($row = $list->fetch_assoc()){ ?>

<tr>

<td>

<?= htmlspecialchars($row['ics_no']) ?>

</td>

<td>

<?= htmlspecialchars($row['property_no']) ?>

</td>

<td>

<?= htmlspecialchars($row['serial_number']) ?>

</td>

<td>

<?= htmlspecialchars($row['item_description']) ?>

</td>

<td>

<?= htmlspecialchars($row['condition_after']) ?>

</td>

<td>

<select
name="status[<?= $row['inventory_id']; ?>]">

<option
value="Available"
<?= $row['condition_after']=="Serviceable" ? "selected" : ""; ?>>

Available

</option>

<option
value="Repair"
<?= $row['condition_after']=="For Repair" ? "selected" : ""; ?>>

Repair

</option>

<option
value="Missing"
<?= $row['condition_after']=="Missing" ? "selected" : ""; ?>>

Missing

</option>

<option
value="Lost"
<?= $row['condition_after']=="Lost" ? "selected" : ""; ?>>

Lost

</option>

</select>

</td>

<td>

<input
type="text"
name="remarks[<?= $row['inventory_id']; ?>]"
value="<?= htmlspecialchars($row['remarks']); ?>"
style="width:100%;">

</td>

</tr>

<?php } ?>

</table>

<br>

<button
type="submit"
class="action-btn process">

<i class="fa-solid fa-circle-check"></i>

Complete Inspection

</button>

</form>