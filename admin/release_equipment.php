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
// LOAD BORROW REQUEST
// ==========================================

$stmt = $conn->prepare("
SELECT
    br.id,
    br.request_no,
    br.status,
    br.borrow_date,
    br.expected_return,
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
// ONLY RELEASED REQUESTS CAN BE RETURNED
// ==========================================

if ($request['status'] !== "Released") {

    $_SESSION['error'] = "Only released equipment can be returned.";

    header("Location: dashboard.php?page=validation");
    exit();

}

// ==========================================
// LOAD BORROWED ITEMS
// ==========================================

$items = $conn->prepare("
SELECT

    bi.id,
    bi.inventory_id,

    i.ics_no,
    i.property_no,
    i.serial_number,
    i.item_description

FROM borrowed_items bi

INNER JOIN inventory i
    ON bi.inventory_id = i.id

WHERE
    bi.request_id = ?
AND
    bi.returned_at IS NULL

ORDER BY
    i.property_no
");

if (!$items) {
    die($conn->error);
}

$items->bind_param("i", $requestID);

$items->execute();

$list = $items->get_result();

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<title>Return Equipment</title>

</head>

<body>

<h2>Return Equipment</h2>

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
<th>Borrow Date</th>
<td><?= date("M d, Y", strtotime($request['borrow_date'])) ?></td>
</tr>

<tr>
<th>Expected Return</th>
<td>

<?php if (!empty($request['expected_return'])) { ?>

<?= date("M d, Y", strtotime($request['expected_return'])) ?>

<?php } else { ?>

N/A

<?php } ?>

</td>
</tr>

</table>

<form
method="POST"
action="save_return.php?id=<?= $requestID ?>">

<table class="release-table">

<tr>

<th></th>

<th>ICS No.</th>

<th>Property No.</th>

<th>Serial No.</th>

<th>Description</th>

<th>Condition After Return</th>

</tr>

<?php if ($list->num_rows > 0) { ?>

<?php while ($item = $list->fetch_assoc()) { ?>

<tr>

<td>

<input
type="checkbox"
name="inventory[]"
value="<?= (int)$item['inventory_id'] ?>"
checked>

</td>

<td>

<?= htmlspecialchars($item['ics_no']) ?>

</td>

<td>

<?= htmlspecialchars($item['property_no']) ?>

</td>

<td>

<?= htmlspecialchars($item['serial_number']) ?>

</td>

<td>

<?= htmlspecialchars($item['item_description']) ?>

</td>

<td>

<select
name="condition[<?= (int)$item['inventory_id'] ?>]"
required>

<option value="Serviceable">

Serviceable

</option>

<option value="For Repair">

For Repair

</option>

<option value="Missing">

Missing

</option>

<option value="Lost">

Lost

</option>

</select>

</td>

</tr>

<?php } ?>

<?php } else { ?>

<tr>

<td colspan="6" style="text-align:center; color:#888;">

No borrowed equipment found for this request.

</td>

</tr>

<?php } ?>

</table>

<br>

<textarea
name="remarks"
rows="4"
style="width:100%;"
placeholder="Return remarks..."></textarea>

<br><br>

<button
type="submit"
class="action-btn process">

<i class="fa-solid fa-arrow-rotate-left"></i>

Return Equipment

</button>

</form>

</body>

</html>

<?php

$items->close();

?>