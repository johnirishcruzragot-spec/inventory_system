<!-- borrowing -->

<div id="validation"
     class="page"
     style="<?= $currentPage=='validation' ? '' : 'display:none'; ?>">

<div class="borrow-dashboard">

<!-- =========================================================
     HEADER
========================================================= -->

<div class="borrow-header">

    <div class="borrow-header-left">

        <span class="section-label">

            DICT IMS 2.0

        </span>

        <h2>

            Equipment Borrowing Management System
        </h2>

        <p>

            Manage digital borrowing requests, approvals, releases,
            returns and inspections.

        </p>

    </div>

    <div class="borrow-header-right">

        <button
            class="add-equipment-btn"
            onclick="openBorrowModal()">

            <i class="fa-solid fa-plus"></i>

            New Borrow Request

        </button>

    </div>

</div>

<!-- =========================================================
     PENDING TASKS
========================================================= -->

<div class="borrow-alert">

<i class="fa-solid fa-bell"></i>

<div class="alert-content">

<h4>

Workflow Summary

</h4>

<p>

<strong>

<?= ($statusCount['Pending Review'] ?? 0); ?>

</strong>

request(s) waiting for review,

<strong>

<?= ($statusCount['Custodian Verification'] ?? 0); ?>

</strong>

waiting for Custodian,

<strong>

<?= ($statusCount['Supply Officer Approval'] ?? 0); ?>

</strong>

waiting for Supply Officer,

<strong>

<?= ($statusCount['Admin Approval'] ?? 0); ?>

</strong>

waiting for Admin.

</p>

</div>

</div>

<!-- =========================================================
     DASHBOARD CARDS
========================================================= -->

<div class="borrow-cards">

<!-- Pending Review -->

<div
class="borrow-card pending"
onclick="filterBorrowStatus('Pending Review')">

<div class="card-icon">

<i class="fa-solid fa-file-circle-plus"></i>

</div>

<div class="card-info">

<h3>

<?= $statusCount['Pending Review'] ?? 0; ?>

</h3>

<p>

Pending Review

</p>

</div>

</div>

<!-- Custodian -->

<div
class="borrow-card custodian"
onclick="filterBorrowStatus('Custodian Verification')">

<div class="card-icon">

<i class="fa-solid fa-box-archive"></i>

</div>

<div class="card-info">

<h3>

Custodian

<span class="tab-count">

<?= $statusCount['Custodian Verification'] ?? 0; ?>

</span>

</h3>

<p>

Custodian Verification

</p>

</div>

</div>

<!-- Supply Officer -->

<div
class="borrow-card supply"
onclick="filterBorrowStatus('Supply Officer Approval')">

<div class="card-icon">

<i class="fa-solid fa-clipboard-check"></i>

</div>

<div class="card-info">

<h3>

<?= $statusCount['Supply Officer Approval'] ?? 0; ?>

</h3>

<p>

Supply Officer

</p>

</div>

</div>

<!-- Admin -->

<div
class="borrow-card admin"
onclick="filterBorrowStatus('Admin Approval')">

<div class="card-icon">

<i class="fa-solid fa-user-shield"></i>

</div>

<div class="card-info">

<h3>

<?= $statusCount['Admin Approval'] ?? 0; ?>

</h3>

<p>

Admin Approval

</p>

</div>

</div>

<!-- Ready -->

<div
class="borrow-card release"
onclick="filterBorrowStatus('Ready for Release')">

<div class="card-icon">

<i class="fa-solid fa-truck-ramp-box"></i>

</div>

<div class="card-info">

<h3>

<?= $statusCount['Ready for Release'] ?? 0; ?>

</h3>

<p>

Ready for Release

</p>

</div>

</div>

<!-- Borrowed -->

<div
class="borrow-card borrowed"
onclick="filterBorrowStatus('Borrowed')">

<div class="card-icon">

<i class="fa-solid fa-handshake"></i>

</div>

<div class="card-info">

<h3>

<?= $statusCount['Borrowed'] ?? 0; ?>

</h3>

<p>

Borrowed

</p>

</div>

</div>

<!-- Return -->

<div
class="borrow-card return"
onclick="filterBorrowStatus('Return Requested')">

<div class="card-icon">

<i class="fa-solid fa-arrow-rotate-left"></i>

</div>

<div class="card-info">

<h3>

<?= $statusCount['Return Requested'] ?? 0; ?>

</h3>

<p>

Return Requested

</p>

</div>

</div>

<!-- Inspection -->

<div
class="borrow-card inspection"
onclick="filterBorrowStatus('Inspection')">

<div class="card-icon">

<i class="fa-solid fa-magnifying-glass"></i>

</div>

<div class="card-info">

<h3>

<?= $statusCount['Inspection'] ?? 0; ?>

</h3>

<p>

Inspection

</p>

</div>

</div>

<!-- Completed -->

<div
class="borrow-card completed"
onclick="filterBorrowStatus('Completed')">

<div class="card-icon">

<i class="fa-solid fa-circle-check"></i>

</div>

<div class="card-info">

<h3>

<?= $statusCount['Completed'] ?? 0; ?>

</h3>

<p>

Completed

</p>

</div>

</div>

</div>

<!-- =========================================================
     SEARCH & FILTER TOOLBAR
========================================================= -->

<div class="borrow-toolbar">

    <div class="borrow-search">

        <i class="fa-solid fa-magnifying-glass"></i>

        <input
            type="text"
            id="searchBorrow"
            placeholder="Search Request No., Employee, Division, Equipment, ICS No. or Serial No..."
            onkeyup="searchBorrow()">

    </div>

    <div class="borrow-toolbar-actions">

<select id="borrowStatusFilter" onchange="filterBorrowAdvanced()">

<option value="">All Status</option>

<option value="Pending Review">Pending Review</option>

<option value="Custodian Verification">Custodian Verification</option>

<option value="Supply Officer Approval">Supply Officer Approval</option>

<option value="Admin Approval">Admin Approval</option>

<option value="Ready for Release">Ready for Release</option>

<option value="Borrowed">Borrowed</option>

<option value="Return Requested">Return Requested</option>

<option value="Inspection">Inspection</option>

<option value="Completed">Completed</option>

<option value="Rejected">Rejected</option>

</select>

<button
class="refresh-btn"
onclick="window.location.reload();">

<i class="fa-solid fa-rotate-right"></i>

Refresh

</button>

        <button
            class="filter-btn"
            onclick="openBorrowFilter()">

            <i class="fa-solid fa-filter"></i>

            Advanced Filter

        </button>

    </div>

</div>

<!-- =========================================================
     QUICK WORKFLOW TABS
========================================================= -->

<div class="borrow-tabs">

<button
class="borrow-tab active"
onclick="filterBorrowStatus('',this)">

<i class="fa-solid fa-table-cells-large"></i>

All

</button>

<button
class="borrow-tab"
onclick="filterBorrowStatus('Pending Review',this)">

<i class="fa-solid fa-clock"></i>

Review

<span class="tab-count">

<?= $statusCount['Pending Review'] ?? 0; ?>

</span>

</button>

<button
class="borrow-tab"
onclick="filterBorrowStatus('Custodian Verification',this)">

<i class="fa-solid fa-box-archive"></i>

Custodian

</button>

<button
class="borrow-tab"
onclick="filterBorrowStatus('Supply Officer Approval',this)">

<i class="fa-solid fa-clipboard-check"></i>

Supply

</button>

<button
class="borrow-tab"
onclick="filterBorrowStatus('Admin Approval',this)">

<i class="fa-solid fa-user-shield"></i>

Admin

</button>

<button
class="borrow-tab"
onclick="filterBorrowStatus('Ready for Release',this)">

<i class="fa-solid fa-truck-ramp-box"></i>

Release

</button>

<button
class="borrow-tab"
onclick="filterBorrowStatus('Borrowed',this)">

<i class="fa-solid fa-handshake"></i>

Borrowed

<span class="tab-count">

<?= $statusCount['Borrowed'] ?? 0; ?>

</span>

</button>

<button
class="borrow-tab"
onclick="filterBorrowStatus('Return Requested',this)">

<i class="fa-solid fa-arrow-rotate-left"></i>

Return

</button>

<button
class="borrow-tab"
onclick="filterBorrowStatus('Inspection',this)">

<i class="fa-solid fa-magnifying-glass"></i>

Inspection

</button>

<button
class="borrow-tab"
onclick="filterBorrowStatus('Completed',this)">

<i class="fa-solid fa-circle-check"></i>

Completed

</button>

<button
class="borrow-tab rejected"
onclick="filterBorrowStatus('Rejected',this)">

<i class="fa-solid fa-circle-xmark"></i>

Rejected

</button>

</div>

<!-- =========================================================
     BORROW REQUESTS
========================================================= -->

<div class="borrow-table-wrapper">

<div class="borrow-table-header">

<div>

<h3>Borrow Request Queue</h3>

<p>

Showing

<strong>

<?= $borrowing->num_rows; ?>

</strong>

request(s)

</p>

</div>

</div>

<div class="borrow-overdue"
     onclick="filterBorrowOverdue()">

    <i class="fa-solid fa-triangle-exclamation"></i>

    <span>

        <?= $overdueCount ?? 0; ?>

        Overdue Equipment

    </span>

</div>

<div class="workflow-legend">

<span>

<i class="fa-solid fa-circle"
style="color:#16a34a"></i>

Completed

</span>

<span>

<i class="fa-solid fa-circle"
style="color:#f59e0b"></i>

Current Step

</span>

<span>

<i class="fa-solid fa-circle"
style="color:#d1d5db"></i>

Pending

</span>

</div>

    <!-- Borrowing Table Starts Here -->

<!-- =========================================================
     BORROW REQUEST QUEUE
========================================================= -->

<table
    class="borrow-table"
    id="borrowTable">

<thead>

<tr>

    <th>Request</th>

    <th>Borrower</th>

    <th>Equipment</th>

    <th>Borrow Schedule</th>

    <th>Current Stage</th>

    <th>Workflow Progress</th>

    <th>Action</th>

</tr>

</thead>

<tbody>

<?php if ($borrowing && $borrowing->num_rows > 0) { ?>

<?php while ($row = $borrowing->fetch_assoc()) { ?>

<?php

$requestId = (int)($row['id'] ?? 0);

$requestNo = $row['request_no'] ?? '-';

$fullname = $row['fullname'] ?? '-';

$division = $row['division'] ?? '-';

$articleName = $row['article_name'] ?? '-';

$propertyNo = $row['property_no'] ?? '-';

$serialNumber = $row['serial_number'] ?? '-';

$quantity = (int)($row['quantity'] ?? 1);

$borrowDate = $row['borrow_date'] ?? null;

$expectedReturn = $row['expected_return'] ?? null;

$status = $row['status'] ?? 'Pending Review';

$purpose = $row['purpose'] ?? '';

$statusClass = strtolower(
    str_replace(
        [' ', '/'],
        ['-', '-'],
        $status
    )
);

?>

<tr
    class="borrow-row"
    data-status="<?= htmlspecialchars($status); ?>"
    data-request="<?= htmlspecialchars($requestNo); ?>"
    data-employee="<?= htmlspecialchars($fullname); ?>"
    data-division="<?= htmlspecialchars($division); ?>"
    data-equipment="<?= htmlspecialchars($articleName); ?>"
    data-property="<?= htmlspecialchars($propertyNo); ?>"
    data-serial="<?= htmlspecialchars($serialNumber); ?>">

    <!-- =====================================================
         REQUEST
    ====================================================== -->

    <td class="request-column">

        <div class="request-info">

            <strong class="request-number">

                <?= htmlspecialchars($requestNo); ?>

            </strong>

            <span class="request-id">

                ID #<?= $requestId; ?>

            </span>

        </div>

    </td>


    <!-- =====================================================
         BORROWER
    ====================================================== -->

    <td class="borrower-column">

        <div class="borrower-info">

            <div class="borrower-avatar">

                <i class="fa-solid fa-user"></i>

            </div>

            <div>

                <strong>

                    <?= htmlspecialchars($fullname); ?>

                </strong>

                <small>

                    <i class="fa-solid fa-building"></i>

                    <?= htmlspecialchars($division); ?>

                </small>

            </div>

        </div>

    </td>


    <!-- =====================================================
         EQUIPMENT
    ====================================================== -->

    <td class="equipment-column">

        <div class="equipment-info">

            <strong class="equipment-name">

                <?= htmlspecialchars($articleName); ?>

            </strong>

            <div class="equipment-meta">

                <?php if (!empty($propertyNo) && $propertyNo !== '-') { ?>

                    <span>

                        <i class="fa-solid fa-tag"></i>

                        <?= htmlspecialchars($propertyNo); ?>

                    </span>

                <?php } ?>

                <?php if (!empty($serialNumber) && $serialNumber !== '-') { ?>

                    <span>

                        <i class="fa-solid fa-barcode"></i>

                        <?= htmlspecialchars($serialNumber); ?>

                    </span>

                <?php } ?>

            </div>

            <small class="quantity-text">

                Qty:
                <strong>

                    <?= $quantity; ?>

                </strong>

            </small>

        </div>

    </td>


    <!-- =====================================================
         BORROW SCHEDULE
    ====================================================== -->

    <td class="schedule-column">

        <div class="borrow-schedule">

            <div class="schedule-item">

                <span class="schedule-label">

                    Borrow

                </span>

                <strong>

                    <?php if (!empty($borrowDate)) { ?>

                        <?= date(
                            "M d, Y",
                            strtotime($borrowDate)
                        ); ?>

                    <?php } else { ?>

                        -

                    <?php } ?>

                </strong>

            </div>

            <div class="schedule-arrow">

                <i class="fa-solid fa-arrow-down"></i>

            </div>

            <div class="schedule-item">

                <span class="schedule-label">

                    Expected Return

                </span>

                <?php if (!empty($expectedReturn)) { ?>

                    <strong>

                        <?= date(
                            "M d, Y",
                            strtotime($expectedReturn)
                        ); ?>

                    </strong>

                <?php } else { ?>

                    <span class="no-return">

                        No Return Required

                    </span>

                <?php } ?>

            </div>

        </div>

    </td>


    <!-- =====================================================
         CURRENT STAGE
    ====================================================== -->

    <td class="status-column">

        <span
            class="status-badge <?= htmlspecialchars($statusClass); ?>">

            <?php

            switch ($status) {

                case 'Pending Review':

                    echo '<i class="fa-solid fa-clock"></i>';

                    break;

                case 'Custodian Verification':

                    echo '<i class="fa-solid fa-box-archive"></i>';

                    break;

                case 'Supply Officer Approval':

                    echo '<i class="fa-solid fa-clipboard-check"></i>';

                    break;

                case 'Admin Approval':

                    echo '<i class="fa-solid fa-user-shield"></i>';

                    break;

                case 'Ready for Release':

                    echo '<i class="fa-solid fa-truck-ramp-box"></i>';

                    break;

                case 'Borrowed':

                    echo '<i class="fa-solid fa-handshake"></i>';

                    break;

                case 'Return Requested':

                    echo '<i class="fa-solid fa-arrow-rotate-left"></i>';

                    break;

                case 'Inspection':

                    echo '<i class="fa-solid fa-magnifying-glass"></i>';

                    break;

                case 'Completed':

                    echo '<i class="fa-solid fa-circle-check"></i>';

                    break;

                case 'Rejected':

                    echo '<i class="fa-solid fa-circle-xmark"></i>';

                    break;

                default:

                    echo '<i class="fa-solid fa-circle-info"></i>';

            }

            ?>

            <?= htmlspecialchars($status); ?>

        </span>

    </td>


<!-- =====================================================
     WORKFLOW PROGRESS
===================================================== -->

<td class="workflow-column">

<?php

$workflow = [

    "Pending Review",

    "Custodian Verification",

    "Supply Officer Approval",

    "Admin Approval",

    "Ready for Release",

    "Borrowed",

    "Return Requested",

    "Inspection",

    "Completed"

];

$currentStep = array_search($status, $workflow);

if ($currentStep === false) {

    $currentStep = 0;

}

?>

<div class="workflow-progress">

<?php foreach ($workflow as $index => $step) { ?>

<?php

$icon = "fa-circle";

switch ($step) {

    case "Pending Review":

        $icon = "fa-clock";

        break;

    case "Custodian Verification":

        $icon = "fa-box-archive";

        break;

    case "Supply Officer Approval":

        $icon = "fa-clipboard-check";

        break;

    case "Admin Approval":

        $icon = "fa-user-shield";

        break;

    case "Ready for Release":

        $icon = "fa-truck-ramp-box";

        break;

    case "Borrowed":

        $icon = "fa-handshake";

        break;

    case "Return Requested":

        $icon = "fa-arrow-rotate-left";

        break;

    case "Inspection":

        $icon = "fa-magnifying-glass";

        break;

    case "Completed":

        $icon = "fa-circle-check";

        break;

}

$stepClass = "";

if ($index < $currentStep) {

    $stepClass = "completed";

} elseif ($index == $currentStep) {

    $stepClass = "current";

} else {

    $stepClass = "pending";

}

?>

<div class="workflow-step <?= $stepClass; ?>">

<div class="workflow-icon">

<i class="fa-solid <?= $icon; ?>"></i>

</div>

<div class="workflow-label">

<?= $step; ?>

</div>

</div>

<?php if ($index < count($workflow)-1) { ?>

<div class="workflow-line <?= ($index < $currentStep) ? 'completed' : ''; ?>">

</div>

<?php } ?>

<?php } ?>

</div>

</td>>


<!-- =====================================================
     ACTION BUTTONS
===================================================== -->

<td class="action-cell">

<?php

$role = $_SESSION['role'];
$canProcess = false;
$buttonText = "Process";

switch($status){

    case "Pending Review":
        $canProcess = in_array($role, ["encoder","admin"]);
        $buttonText = "Review";
        break;

    case "Custodian Verification":
        $canProcess = ($role == "custodian");
        $buttonText = "Verify";
        break;

    case "Supply Officer Approval":
        $canProcess = ($role == "supply_officer");
        $buttonText = "Approve";
        break;

    case "Admin Approval":
        $canProcess = ($role == "admin");
        $buttonText = "Final Approval";
        break;

    case "Ready for Release":
        $canProcess = ($role == "custodian");
        $buttonText = "Release";
        break;

    case "Borrowed":
        $canProcess = ($role == "custodian");
        $buttonText = "Receive Return";
        break;

    case "Return Requested":
        $canProcess = ($role == "custodian");
        $buttonText = "Inspect";
        break;

    case "Inspection":
        $canProcess = ($role == "custodian");
        $buttonText = "Complete";
        break;

    case "Completed":
        ?>

        <button
            class="action-btn completed"
            onclick="viewBorrowRequest(<?= $requestId; ?>)">

            View

        </button>

        <?php

        break;

    default:

        ?>

        <button class="action-btn">

            View

        </button>

        <?php

        break;

}

if($status != "Completed"){

    if($canProcess){

        ?>

        <button
            class="action-btn review"
            onclick="openProcessModal(<?= $requestId; ?>,'<?= $status; ?>')">

            <?= $buttonText; ?>

        </button>

        <?php

    }else{

        ?>

        <button
            class="action-btn disabled"
            disabled>

            Waiting

        </button>

        <?php

    }

    if($status=="Rejected"){

?>

<button
class="action-btn rejected"
disabled>

Rejected

</button>

<?php

}

}

?>

</td>

</tr>

<?php } // End while ?>

<?php } else { ?>

<tr>

    <td
        colspan="7"
        class="empty-borrow-row">

        <div class="borrow-empty-state">

            <i class="fa-solid fa-box-open"></i>

            <h4>

                No Borrow Requests

            </h4>

            <p>

                There are currently no borrowing requests available.

            </p>

            <button
                type="button"
                class="add-equipment-btn"
                onclick="openBorrowModal()">

                <i class="fa-solid fa-plus"></i>

                New Borrow Request

            </button>

        </div>

    </td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>