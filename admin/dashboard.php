<?php
include "../config.php";
include "includes/auth.php";

$currentPage = $_GET['page'] ?? 'dashboard';

include "includes/users.php";
include "includes/personnel.php";
include "includes/designations.php";
include "includes/articles.php";
include "includes/inventory.php";
include "includes/queries.php";
include "includes/borrowing.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>DICT Inventory Management System</title>

<link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<!-- Core -->
<link rel="stylesheet" href="css/base.css">
<link rel="stylesheet" href="css/layout.css">
<link rel="stylesheet" href="css/sidebar.css">
<link rel="stylesheet" href="css/topbar.css">

<!-- Shared Components -->
<link rel="stylesheet" href="css/buttons.css">
<link rel="stylesheet" href="css/cards.css">
<link rel="stylesheet" href="css/forms.css">
<link rel="stylesheet" href="css/table.css">
<link rel="stylesheet" href="css/modal.css">
<link rel="stylesheet" href="css/utilities.css">

<!-- Pages -->
<link rel="stylesheet" href="css/dashboard.css">
<link rel="stylesheet" href="css/users.css">
<link rel="stylesheet" href="css/personnel.css">
<link rel="stylesheet" href="css/articles.css">
<link rel="stylesheet" href="css/inventory.css">
<link rel="stylesheet" href="css/borrowing.css">
<link rel="stylesheet" href="css/gallery.css">
<link rel="stylesheet" href="css/reports.css">
<link rel="stylesheet" href="css/settings.css">

</head>

<body>

    <!-- SIDEBAR -->

    <?php include 'includes/sidebar.php'; ?>

    <!-- MAIN -->
<div class="main"> 

<div class="topbar">

<div class="breadcrumb">

    <span>DICT IMS</span>

    <span>/</span>

    <strong>Operations Desk</strong>

</div>

<div class="top-icons">

<button>🔍</button>

<button>🔔</button>

<div class="date">

<?= date("M d, Y"); ?>

</div>

<div class="profile">

<?= strtoupper(substr($_SESSION['fullname'],0,1)); ?>

</div>

</div>

</div>

<!-- overview -->
<div id="dashboard" class="page" style="<?= $currentPage === 'dashboard' ? '' : 'display:none;'; ?>">

    <div class="dashboard-header">

        <div class="dashboard-title">

            <span class="dashboard-label">
                OPERATIONS DESK
            </span>

<?php
date_default_timezone_set("Asia/Manila");

$hour = date("G"); // 0-23

if ($hour < 12) {
    $greeting = "Good Morning";
} elseif ($hour < 18) {
    $greeting = "Good Afternoon";
} else {
    $greeting = "Good Evening";
}
?>

<h1>
    <?= $greeting; ?>,
    <?= htmlspecialchars($_SESSION['fullname']); ?>.
</h1>

            <p>
                Welcome back.
            Here's an overview of today's inventory operations,
            borrowing requests, equipment status, and system activity.
            </p>

        </div>

        <button class="report-btn">
            📄 View Reports
        </button>

    </div>

    <!-- Statistics -->
    <div class="stats-grid">

        <div class="stat-card">

            <div class="stat-icon blue">
                📦
            </div>

            <h5>Total Equipment</h5>

                <h2><?= $totalEquipment; ?></h2>

                <small>Equipment registered</small>

        </div>

        <div class="stat-card">

            <div class="stat-icon green">
                ✔
            </div>

            <h5>Available Now</h5>

<h2><?= $availableEquipment; ?></h2>

<small>Ready for borrowing</small>

        </div>

        <div class="stat-card">

            <div class="stat-icon yellow">
                ↗
            </div>

            <h5>Out on Loan</h5>

<h2><?= $borrowedEquipment; ?></h2>

<small>Currently borrowed</small>

        </div>

        <div class="stat-card">

            <div class="stat-icon red">
                🔧
            </div>

            <h5>Under Repair</h5>

<h2><?= $repairEquipment; ?></h2>

<small>Needs maintenance</small>

        </div>

    </div>

    <div class="stats-grid">

    <div class="stat-card">

        <div class="stat-icon purple">
            💰
        </div>

        <h5>Inventory Value</h5>

        <h2>₱<?= number_format($inventoryValue,2); ?></h2>

        <small>Total asset value</small>

    </div>

    <div class="stat-card">

        <div class="stat-icon blue">
            👥
        </div>

        <h5>Personnel</h5>

        <h2><?= $totalPersonnel; ?></h2>

        <small>Registered personnel</small>

    </div>

    <div class="stat-card">

        <div class="stat-icon orange">
            📋
        </div>

        <h5>Borrow Requests</h5>

        <h2><?= $totalBorrowing; ?></h2>

        <small>Total requests</small>

    </div>

    <div class="stat-card">

        <div class="stat-icon green">
            📚
        </div>

        <h5>Articles</h5>

        <h2><?= $totalArticles; ?></h2>

        <small>Equipment categories</small>

    </div>

</div>

    <!-- Existing Dashboard -->
    <div class="dashboard-grid">

<!-- Borrowing Activity -->
<div class="card large">

    <h3>Borrowing Activity</h3>

    <div class="borrow-summary">

        <?php foreach($statusList as $status){ ?>

        <div class="summary-item <?= strtolower(str_replace(' ','-',$status)); ?>">

            <div class="summary-title">
                <?= $status; ?>
            </div>

            <div class="summary-number">
                <?= $statusCount[$status]; ?>
            </div>

        </div>

        <?php } ?>

    </div>

</div>

<!-- Equipment by Category -->
<div class="card">

    <h3>Equipment by Category</h3>

    <div class="category-scroll">

    <?php

    $category = $conn->query("
    SELECT
        articles.article_name,
        COUNT(inventory.id) AS total
    FROM articles
    LEFT JOIN inventory
        ON articles.id = inventory.article_id
    GROUP BY articles.id
    ORDER BY total DESC
    ");

    while($row = $category->fetch_assoc()){

        $percent = ($totalEquipment > 0)
            ? round(($row['total'] / $totalEquipment) * 100)
            : 0;

    ?>

        <div class="category-item">

            <div class="category-header">

                <span class="category-name">
                    <?= htmlspecialchars($row['article_name']); ?>
                </span>

                <span class="category-count">
                    <?= $row['total']; ?>
                </span>

            </div>

            <div class="progress">

                <div
                    class="progress-bar"
                    style="width:<?= $percent; ?>%;">
                </div>

            </div>

            <small><?= $percent; ?>% of inventory</small>

        </div>

    <?php } ?>

    </div>

</div>

        <!-- Activity -->
        <div class="card large">

            <h3>Recent Activity</h3>

<ul class="activity">

<?php

$recent = $conn->query("
SELECT
    br.status,
    p.fullname,
    a.article_name
FROM borrow_requests br

LEFT JOIN personnel p
    ON br.borrower_id = p.id

LEFT JOIN borrow_request_items bri
    ON br.id = bri.request_id

LEFT JOIN articles a
    ON bri.article_id = a.id

ORDER BY br.id DESC
LIMIT 5
");

while($row=$recent->fetch_assoc()){

?>

<li>

<strong><?= htmlspecialchars($row['fullname'] ?? 'Unknown User'); ?></strong>

<?= strtolower($row['status'] ?? ''); ?>

<strong><?= htmlspecialchars($row['article_name'] ?? 'Unknown Article'); ?></strong>

</li>

<?php } ?>

</ul>

        </div>

        <!-- Quick Actions -->
        <div class="card">

            <h3>Quick Actions</h3>

<button class="action-btn"
onclick="showPage('inventory',document.querySelectorAll('.menu')[3])">

📦 Add Equipment

</button>

<button class="action-btn"
onclick="showPage('validation',document.querySelectorAll('.menu')[5])">

📋 Borrowing

</button>

<button class="action-btn"
onclick="showPage('reports',document.querySelectorAll('.menu')[6])">

📄 Reports

</button>

        </div>

    </div>

</div>

    <!-- Users -->
<div id="users" class="page" style="<?= $currentPage === 'users' ? 'display:block;' : 'display:none;'; ?>">

<div class="container">
    <!-- HEADER -->
<button
type="button"
class="back-btn"
onclick="showPage('dashboard', document.querySelectorAll('.menu')[0])">

← Dashboard

</button>

<h2>
    <span>Manage Users</span>
</h2>


        <!-- MESSAGE -->


<?php

if ($message != "") {
    echo $message;
}

?>



<div class="users-tools">

    <input
        type="text"
        id="searchUser"
        placeholder="🔍 Search users..."
        onkeyup="searchUsers()">

<button type="button" class="add-user-btn" onclick="openUserModal()">
    + Add User
</button>

</div>

    <!-- USERS TABLE -->

<table id="usersTable"> 

    <thead>

        <tr>

            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Division / PMO</th>
            <th>Role</th>
            <th>Created</th>
            <th>Last Login</th>
            <th>Status</th>
            <th>Action</th>

        </tr>

    </thead>

    <tbody>

    <?php while($row = $result->fetch_assoc()){ ?>

        <tr>

            <td>
                <?= (int)$row['id']; ?>
            </td>


            <td>
                <?= htmlspecialchars(
                    $row['fullname']
                ); ?>
            </td>


            <td>
                <?= htmlspecialchars(
                    $row['email']
                ); ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $row['division']
                ) ?>
            </td>


            <td>
                <?= htmlspecialchars(
                    $row['role']
                ); ?>
            </td>


            <td>
                <?= htmlspecialchars(
                    $row['created_at']
                ); ?>
            </td>


            <td>

                <?= $row['last_login']
                    ? htmlspecialchars(
                        $row['last_login']
                    )
                    : 'Never';
                ?>

            </td>


            <td>

<?php

$status = strtolower($row['status']);

if($status=="active"){
    echo '<span style="color:green;font-weight:600;">🟢 Active</span>';
}
elseif($status=="inactive"){
    echo '<span style="color:#ff9800;font-weight:600;">🟡 Inactive</span>';
}
else{
    echo '<span style="color:red;font-weight:600;">🔴 Blocked</span>';
}

?>
            </td>


            <td>

<button
type="button"
class="edit"
onclick="editUser(
<?= $row['id']; ?>,
'<?= htmlspecialchars($row['fullname'], ENT_QUOTES); ?>',
'<?= htmlspecialchars($row['email'], ENT_QUOTES); ?>',
'<?= htmlspecialchars($row['division'], ENT_QUOTES); ?>',
'<?= $row['role']; ?>',
'<?= $row['status']; ?>'
)">
Edit
</button>


                <span class="action-separator">
                    |
                </span>


                <?php if(
                    $row['id'] != $_SESSION['user_id']
                ){ ?>

<button type="button" class="delete"
    onclick="openDeleteUserModal(<?= (int)$row['id']; ?>, '<?= htmlspecialchars($row['fullname'], ENT_QUOTES); ?>')">
    Delete
</button>

                <?php } else { ?>

                    <span style="color:#999;">
                        Current User
                    </span>

                <?php } ?>

            </td>

        </tr>

    <?php } ?>

    </tbody>

</table>

</div> 

</div> 

<!-- DASHBOARD CARDS -->

    <!-- Personnel -->
<div id="personnel" class="page" style="<?= $currentPage === 'personnel' ? '' : 'display:none;'; ?>">

    <!-- Header -->
<div class="personnel-header">

    <div>
        <span class="section-label">PEOPLE DIRECTORY</span>
        <h2>OPDS Personnel</h2>
        <p>Manage all DICT personnel records.</p>
    </div>

    <div class="header-actions">

        <button
            id="addPersonnelBtn"
            class="add-personnel-btn"
            onclick="openPersonnelModal()">
            + Add Personnel
        </button>

        <button
            id="addDesignationBtn"
            class="add-personnel-btn"
            onclick="openDesignationModal()"
            style="display:none;">
            + Add Designation
        </button>

    </div>

</div>

    <!-- Tabs -->
    <div class="personnel-tabs">

        <button class="tab-btn active"
                onclick="showPersonnelTab('personnelList', this)">
            👤 Personnel
        </button>

        <button class="tab-btn"
                onclick="showPersonnelTab('designationList', this)">
            🏷 Designations
        </button>

        <button class="tab-btn"
                onclick="showPersonnelTab('archiveList', this)">
            📁 Archive
        </button>

    </div>

    <!-- ================= PERSONNEL ================= -->

    <div id="personnelList" class="personnel-content">

        <div class="personnel-tools">

            <input
                type="text"
                id="searchPersonnel"
                placeholder="🔍 Search personnel..."
                onkeyup="searchPersonnel()">

        </div>

        <table class="personnel-table" id="personnelTable">

        <thead>

            <tr>

                <th>ID</th>
                <th>Employee ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Position</th>
                <th>Division</th>
                <th>Status</th>
                <th>Action</th>

            </tr>

        </thead>

<tbody>

<?php while($row = $personnel->fetch_assoc()){ ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['employee_id']); ?></td>

<td><?= htmlspecialchars($row['fullname']); ?></td>

<td><?= htmlspecialchars($row['email']); ?></td>

<td><?= htmlspecialchars($row['position']); ?></td>

<td><?= htmlspecialchars($row['division']); ?></td>

<td>

<span class="status <?= strtolower($row['employment_status']); ?>">
<?= htmlspecialchars($row['employment_status']); ?>
</span>

</td>

<td>

<button
class="view-btn"
onclick="viewPersonnel(
'<?= htmlspecialchars($row['employee_id'], ENT_QUOTES); ?>',
'<?= htmlspecialchars($row['fullname'], ENT_QUOTES); ?>',
'<?= htmlspecialchars($row['email'], ENT_QUOTES); ?>',
'<?= htmlspecialchars($row['contact_number'], ENT_QUOTES); ?>',
'<?= htmlspecialchars($row['designation'], ENT_QUOTES); ?>',
'<?= htmlspecialchars($row['position'], ENT_QUOTES); ?>',
'<?= htmlspecialchars($row['division'], ENT_QUOTES); ?>',
'<?= htmlspecialchars($row['employment_status'], ENT_QUOTES); ?>',
'<?= htmlspecialchars($row['status'], ENT_QUOTES); ?>'
)">
👁 View
</button>

                <span class="action-separator">
                    |
                </span>

<button
class="edit"
onclick="editPersonnel(
<?= $row['id']; ?>,
'<?= htmlspecialchars($row['employee_id'], ENT_QUOTES); ?>',
'<?= htmlspecialchars($row['fullname'], ENT_QUOTES); ?>',
'<?= htmlspecialchars($row['email'], ENT_QUOTES); ?>',
'<?= htmlspecialchars($row['contact_number'], ENT_QUOTES); ?>',
'<?= htmlspecialchars($row['designation'], ENT_QUOTES); ?>',
'<?= htmlspecialchars($row['position'], ENT_QUOTES); ?>',
'<?= htmlspecialchars($row['division'], ENT_QUOTES); ?>',
'<?= htmlspecialchars($row['employment_status'], ENT_QUOTES); ?>',
'<?= htmlspecialchars($row['status'], ENT_QUOTES); ?>'
)">
    ✏ Edit
</button>

                <span class="action-separator">
                    |
                </span>
                
<button
class="delete-btn"
onclick="archivePersonnel(<?= $row['id']; ?>)">
📁 Archive
</button>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>  

    <!-- ================= DESIGNATION ================= -->

<div id="designationList"
     class="personnel-content"
     style="display:none;">

    <div class="container">

        <div class="personnel-tools">

            <input
                type="text"
                id="searchDesignation"
                placeholder="🔍 Search designation..."
                onkeyup="searchDesignation()">

        </div>

        <table class="personnel-table" id="designationTable">

            <thead>

                <tr>

                    <th>ID</th>
                    <th>Class ID</th>
                    <th>Designation Name</th>
                    <th>Level</th>
                    <th>Action</th>

                </tr>

            </thead>

            <tbody>

            <?php while($row = $designation->fetch_assoc()){ ?>

                <tr>

                    <td><?= $row['id']; ?></td>

                    <td><?= htmlspecialchars($row['class_id']); ?></td>

                    <td><?= htmlspecialchars($row['class_name']); ?></td>

                    <td><?= htmlspecialchars($row['class_level']); ?></td>

                    <td>

                        <button
                            class="edit-btn"
                            onclick="editDesignation(
                                <?= $row['id']; ?>,
                                '<?= htmlspecialchars($row['class_id'], ENT_QUOTES); ?>',
                                '<?= htmlspecialchars($row['class_name'], ENT_QUOTES); ?>',
                                <?= (int)$row['class_level']; ?>
                            )">
                            ✏ Edit
                        </button>

                    </td>

                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

    <!-- ================= ARCHIVE ================= -->

<div id="archiveList"
     class="personnel-content"
     style="display:none;">

<div class="container">

<div class="personnel-tools">

<input
type="text"
id="searchArchive"
placeholder="🔍 Search archived personnel..."
onkeyup="searchArchive()">

</div>

<table class="personnel-table"
id="archiveTable">

<thead>

<tr>

<th>Name</th>
<th>Email</th>
<th>Position / Designation</th>
<th>Division / PMO</th>
<th>Employment Status</th>
<th>Actions</th>

</tr>

</thead>

<tbody>

<?php while($row=$archive->fetch_assoc()){ ?>

<tr>

<td><?= htmlspecialchars($row['fullname']); ?></td>

<td><?= htmlspecialchars($row['email']); ?></td>

<td>

<?= htmlspecialchars($row['position']); ?>

<?php if(!empty($row['designation'])){ ?>

<br>

<small><?= htmlspecialchars($row['designation']); ?></small>

<?php } ?>

</td>

<td><?= htmlspecialchars($row['division']); ?></td>

<td><?= htmlspecialchars($row['employment_status']); ?></td>

<td>

<button
class="restore-btn"
onclick="restorePersonnel(<?= $row['id']; ?>)">
↻ Restore
</button>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

    <!-- Article -->
<div id="article" class="page" style="<?= $currentPage === 'article' ? '' : 'display:none;'; ?>">

    <div class="article-header">

    <div>
        <span class="section-label">ARTICLE MANAGEMENT</span>
        <h2>Inventory Articles</h2>
        <p>Manage all equipment articles used in the inventory system.</p>
    </div>

    <button
        class="add-equipment-btn"
        onclick="openArticleModal()">

        + Add Article

    </button>

</div>

    <div class="article-grid">

        <!-- RIGHT : ARTICLE TABLE -->
        <div class="article-card">

            <div class="card-header">

                <h3>📋 All Articles</h3>

                <input
                    type="text"
                    id="searchArticle"
                    placeholder="Search..."
                    onkeyup="searchArticle()">

            </div>

            <table class="article-table" id="articleTable">

<thead>

<tr>

    <th>ID</th>

    <th>Article Code</th>

    <th>Article Name</th>

    <th>Category</th>

    <th>Quantity</th>

    <th>Available</th>

    <th>Description</th>

    <th width="180">Actions</th>

</tr>

</thead>

<tbody>

<?php while($row = $articles->fetch_assoc()){ ?>

<tr>

    <td>
        <?= $row['id']; ?>
    </td>

    <td>
        <?= htmlspecialchars($row['article_code'] ?? "-"); ?>
    </td>

    <td>

        <strong>

            <?= htmlspecialchars($row['article_name']); ?>

        </strong>

    </td>

    <td>

        <?= htmlspecialchars($row['category']); ?>

    </td>

    <td>

        <span class="badge badge-blue">

            <?= (int)$row['quantity']; ?>

        </span>

    </td>

    <td>

<?php

$available = isset($row['available_quantity'])
    ? (int)$row['available_quantity']
    : (int)$row['quantity'];

?>

<?php if($available > 0){ ?>

<span class="badge badge-green">

    <?= $available; ?>

</span>

<?php }else{ ?>

<span class="badge badge-red">

    Out of Stock

</span>

<?php } ?>

    </td>

    <td>

        <?= nl2br(htmlspecialchars($row['description'])); ?>

    </td>

    <td>

        <button

            class="edit-btn"

            onclick="editArticle(

                <?= $row['id']; ?>,

                '<?= htmlspecialchars($row['article_code'] ?? '', ENT_QUOTES); ?>',

                '<?= htmlspecialchars($row['article_name'], ENT_QUOTES); ?>',

                '<?= htmlspecialchars($row['category'], ENT_QUOTES); ?>',

                <?= (int)$row['quantity']; ?>,

                '<?= htmlspecialchars($row['description'], ENT_QUOTES); ?>'

            )">

            <i class="fa-solid fa-pen"></i>

            Edit

        </button>

        <button

            class="delete-btn"

            onclick="deleteArticle(<?= $row['id']; ?>)">

            <i class="fa-solid fa-trash"></i>

            Delete

        </button>

    </td>

</tr>

<?php } ?>

</tbody>

            </table>

        </div>

    </div>

</div>


<!-- Inventory -->
<div id="inventory" class="page" style="<?= $currentPage === 'inventory' ? '' : 'display:none;'; ?>">

<div class="inventory-header">

    <div>

        <span class="section-label">
            INVENTORY MANAGEMENT
        </span>

        <h2>Equipment Inventory</h2>

        <p>
            Manage all DICT equipment and assets.
        </p>

    </div>

    <div class="inventory-actions">

        <button
            class="import-btn"
            onclick="openImportCSVModal()">

            <i class="fa-solid fa-file-csv"></i>
            Import CSV

        </button>

        <button
            class="add-equipment-btn"
            onclick="openInventoryModal()">

            <i class="fa-solid fa-plus"></i>
            Add Equipment

        </button>

    </div>

    <div class="inventory-tools">
        <input
            type="text"
            id="searchInventory"
            placeholder="🔍 Search equipment..."
            onkeyup="searchInventory()">

        <select id="categoryFilter" onchange="filterInventoryCategory()">
            <option value="">All Categories</option>
            <option value="Appliances">Appliances</option>
            <option value="Communication Equipment">Communication Equipment</option>
            <option value="Computer Equipment">Computer Equipment</option>
            <option value="Furniture and Fixtures">Furniture and Fixtures</option>
            <option value="ICT Equipment">ICT Equipment</option>
            <option value="Networking Equipment">Networking Equipment</option>
            <option value="Office Equipment">Office Equipment</option>
            <option value="Power Equipment">Power Equipment</option>
            <option value="Printers and Scanners">Printers and Scanners</option>
            <option value="Tools and Instruments">Tools and Instruments</option>
        </select>
    </div>

<div class="inventory-table-wrapper">

<table id="inventoryTable" class="inventory-table">

    <thead>
        <tr>
            <th>Province</th>
            <th>Property No.</th>
            <th>Article</th>
            <th>Type of PPE</th>
            <th>Item Description</th>
            <th>Date Acquired</th>
            <th>Qty</th>
            <th>Unit of Measurement</th>
            <th>Unit Value</th>
            <th>Semi-Expendable</th>
            <th>Condition</th>
            <th>Remarks</th>
            <th>Accountable Officer</th>
            <th>Actions</th>
        </tr>
    </thead>

<tbody>

<?php while($row = $inventory->fetch_assoc()){ ?>

    <!-- Main Row -->
    <tr class="inventory-main-row">
        <td><?= htmlspecialchars($row['province']); ?></td>
        <td><?= htmlspecialchars($row['property_no']); ?></td>
        <td><?= htmlspecialchars($row['article_name']); ?></td>
        <td><?= htmlspecialchars($row['type_of_ppe']); ?></td>
        <td><?= htmlspecialchars($row['item_description']); ?></td>
        <td><?= htmlspecialchars($row['date_acquired']); ?></td>
        <td><?= htmlspecialchars($row['qty']); ?></td>
        <td><?= htmlspecialchars($row['unit_of_measure']); ?></td>
        <td><?= htmlspecialchars($row['unit_value']); ?></td>
        <td><?= htmlspecialchars($row['se_e']); ?></td>
        <td><?= htmlspecialchars($row['condition']); ?></td>
        <td><?= htmlspecialchars($row['remarks']); ?></td>
        <td><?= htmlspecialchars($row['accountable_officer_name']); ?></td>

<td class="action-column">

    <button
        type="button"
        class="toggle-btn"
        onclick="toggleInventoryDetails(<?= (int)$row['id']; ?>)"
        title="View Details">

        <i class="fa-solid fa-bars"></i>

    </button>

</td>

    </tr>

    <!-- Hidden Details Row -->
<tr
    id="details-row-<?= $row['id']; ?>"
    class="inventory-details-row"
    style="display:none;">

<td colspan="15">

<form method="POST" class="inventory-inline-form">

<div class="inventory-details">

<input
    type="hidden"
    name="id"
    value="<?= (int)$row['id']; ?>">

<div class="details-grid">

    <!-- ID -->
    <div class="detail-item">
        <label>ID</label>
        <span><?= (int)$row["id"]; ?></span>
    </div>

    <!-- ICS No -->
    <div class="detail-item">
        <label>ICS No.</label>

        <span class="view-mode">
            <?= htmlspecialchars($row["ics_no"]); ?>
        </span>

        <input
            class="edit-mode"
            type="text"
            name="ics_no"
            value="<?= htmlspecialchars($row["ics_no"]); ?>"
            style="display:none;">
    </div>

    <!-- Province -->
    <div class="detail-item">
        <label>Province</label>

        <span class="view-mode">
            <?= htmlspecialchars($row["province"]); ?>
        </span>

        <input
            class="edit-mode"
            type="text"
            name="province"
            value="<?= htmlspecialchars($row["province"]); ?>"
            style="display:none;">
    </div>

    <!-- Property No -->
    <div class="detail-item">
        <label>Property No.</label>

        <span class="view-mode">
            <?= htmlspecialchars($row["property_no"]); ?>
        </span>

        <input
            class="edit-mode"
            type="text"
            name="property_no"
            value="<?= htmlspecialchars($row["property_no"]); ?>"
            style="display:none;">
    </div>

    <!-- Year -->
    <div class="detail-item">
        <label>Year</label>

        <span class="view-mode">
            <?= htmlspecialchars($row["year"]); ?>
        </span>

        <input
            class="edit-mode"
            type="number"
            name="year"
            value="<?= htmlspecialchars($row["year"]); ?>"
            style="display:none;">
    </div>

    <!-- Month -->
    <div class="detail-item">
        <label>Month</label>

        <span class="view-mode">
            <?= htmlspecialchars($row["month"]); ?>
        </span>

        <input
            class="edit-mode"
            type="text"
            name="month"
            value="<?= htmlspecialchars($row["month"]); ?>"
            style="display:none;">
    </div>

    <!-- Object Code -->
    <div class="detail-item">
        <label>Object Code</label>

        <span class="view-mode">
            <?= htmlspecialchars($row["object_code"]); ?>
        </span>

        <input
            class="edit-mode"
            type="text"
            name="object_code"
            value="<?= htmlspecialchars($row["object_code"]); ?>"
            style="display:none;">
    </div>

    <!-- Series -->
    <div class="detail-item">
        <label>Series</label>

        <span class="view-mode">
            <?= htmlspecialchars($row["series"]); ?>
        </span>

        <input
            class="edit-mode"
            type="text"
            name="series"
            value="<?= htmlspecialchars($row["series"]); ?>"
            style="display:none;">
    </div>

    <!-- Location Code -->
    <div class="detail-item">
        <label>Location Code</label>

        <span class="view-mode">
            <?= htmlspecialchars($row["location_code"]); ?>
        </span>

        <input
            class="edit-mode"
            type="text"
            name="location_code"
            value="<?= htmlspecialchars($row["location_code"]); ?>"
            style="display:none;">
    </div>

    <!-- Article -->
    <div class="detail-item">
        <label>Article</label>
        <span><?= htmlspecialchars($row["article_name"]); ?></span>
    </div>

    <!-- Article ID -->
    <div class="detail-item">
        <label>Article ID</label>
        <span><?= (int)$row["article_id"]; ?></span>

        <input
            type="hidden"
            name="article_id"
            value="<?= (int)$row['article_id']; ?>">
    </div>

    <!-- Type of PPE -->
    <div class="detail-item">
        <label>Type of PPE</label>

        <span class="view-mode">
            <?= htmlspecialchars($row["type_of_ppe"]); ?>
        </span>

        <input
            class="edit-mode"
            type="text"
            name="type_of_ppe"
            value="<?= htmlspecialchars($row["type_of_ppe"]); ?>"
            style="display:none;">
    </div>

    <!-- Item Description -->
    <div class="detail-item">
        <label>Item Description</label>

        <span class="view-mode">
            <?= htmlspecialchars($row["item_description"]); ?>
        </span>

        <input
            class="edit-mode"
            type="text"
            name="item_description"
            value="<?= htmlspecialchars($row["item_description"]); ?>"
            style="display:none;">
    </div>

    <!-- Serial Number -->
    <div class="detail-item">
        <label>Serial Number</label>

        <span class="view-mode">
            <?= htmlspecialchars($row["serial_number"]); ?>
        </span>

        <input
            class="edit-mode"
            type="text"
            name="serial_number"
            value="<?= htmlspecialchars($row["serial_number"]); ?>"
            style="display:none;">
    </div>

    <!-- Date Acquired -->
    <div class="detail-item">
        <label>Date Acquired</label>

        <span class="view-mode">
            <?= htmlspecialchars($row["date_acquired"]); ?>
        </span>

        <input
            class="edit-mode"
            type="date"
            name="date_acquired"
            value="<?= htmlspecialchars($row["date_acquired"]); ?>"
            style="display:none;">
    </div>

    <!-- Quantity -->
    <div class="detail-item">
        <label>Quantity</label>

        <span class="view-mode">
            <?= htmlspecialchars($row["qty"]); ?>
        </span>

        <input
            class="edit-mode"
            type="number"
            name="qty"
            value="<?= htmlspecialchars($row["qty"]); ?>"
            style="display:none;">
    </div>

    <!-- Unit of Measure -->
    <div class="detail-item">
        <label>Unit of Measure</label>

        <span class="view-mode">
            <?= htmlspecialchars($row["unit_of_measure"]); ?>
        </span>

        <input
            class="edit-mode"
            type="text"
            name="unit_of_measure"
            value="<?= htmlspecialchars($row["unit_of_measure"]); ?>"
            style="display:none;">
    </div>

    <!-- Unit Value -->
    <div class="detail-item">
        <label>Unit Value</label>

        <span class="view-mode">
            <?= htmlspecialchars($row["unit_value"]); ?>
        </span>

        <input
            class="edit-mode"
            type="number"
            step="0.01"
            name="unit_value"
            value="<?= htmlspecialchars($row["unit_value"]); ?>"
            style="display:none;">
    </div>

    <!-- SE / E -->
    <div class="detail-item">
        <label>SE / E</label>

        <span class="view-mode">
            <?= htmlspecialchars($row["se_e"]); ?>
        </span>

        <input
            class="edit-mode"
            type="text"
            name="se_e"
            value="<?= htmlspecialchars($row["se_e"]); ?>"
            style="display:none;">
    </div>

    <!-- Condition -->
    <div class="detail-item">
        <label>Condition</label>

        <span class="view-mode">
            <?= htmlspecialchars($row["condition"]); ?>
        </span>

        <input
            class="edit-mode"
            type="text"
            name="condition"
            value="<?= htmlspecialchars($row["condition"]); ?>"
            style="display:none;">
    </div>

    <!-- Remarks -->
    <div class="detail-item">
        <label>Remarks</label>

        <span class="view-mode">
            <?= htmlspecialchars($row["remarks"]); ?>
        </span>

        <textarea
            class="edit-mode"
            name="remarks"
            style="display:none;"><?= htmlspecialchars($row["remarks"]); ?></textarea>
    </div>

    <!-- Accountable Officer -->
    <div class="detail-item">
        <label>Accountable Officer</label>

        <span class="view-mode">
            <?= !empty($row["accountable_officer_name"])
                ? htmlspecialchars($row["accountable_officer_name"])
                : "Unassigned"; ?>
        </span>

            <input
            type="hidden"
            name="accountable_officer_id"
            value="<?= (int)$row["accountable_officer_id"]; ?>">
        </div>

</div>

<div class="details-actions">

    <button
        type="button"
        class="edit-btn"
        onclick="editInventoryInline(this)">

        <i class="fa-solid fa-pen"></i>
        Edit

    </button>

    <button
        type="button"
        class="qr-btn"
        onclick='openQRModal(
            <?= (int)$row["id"] ?>,
            <?= json_encode($row["ics_no"]) ?>,
            <?= json_encode($row["property_no"]) ?>,
            <?= json_encode($row["article_name"]) ?>,
            <?= json_encode($row["item_description"]) ?>,
            <?= json_encode($row["serial_number"]) ?>,
            <?= json_encode($row["accountable_officer_name"]) ?>
        )'>

        <i class="fa-solid fa-qrcode"></i>
        QR Code

    </button>

    <button
        type="submit"
        class="save-btn"
        name="update_inventory"
        style="display:none;">

        <i class="fa-solid fa-floppy-disk"></i>
        Save

    </button>

    <button
        type="button"
        class="cancel-btn"
        onclick="cancelInventoryInline(this)"
        style="display:none;">

        <i class="fa-solid fa-xmark"></i>
        Cancel

    </button>

    <button
        type="button"
        class="delete-btn"
        onclick="deleteInventory(<?= (int)$row['id']; ?>)">

        <i class="fa-solid fa-trash"></i>
        Delete

    </button>

</div>

</div>

</form>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<!-- Gallery -->
<div id="gallery" class="page" style="display:none;">

    <div class="gallery-header">

        <div>

            <span class="section-label">
                EQUIPMENT GALLERY
            </span>

            <h2>Equipment Gallery</h2>

            <p>
                Browse all registered DICT equipment with photos.
            </p>

        </div>

    </div>


    <div class="gallery-toolbar">

        <input
            type="text"
            id="gallerySearch"
            placeholder="🔍 Search equipment..."
            onkeyup="searchGallery()">

    </div>


    <div class="gallery-grid">

<?php

$gallery = $conn->query("
SELECT
inventory.*,
articles.article_name
FROM inventory

LEFT JOIN articles
ON inventory.article_id = articles.id

ORDER BY inventory.id DESC
");

while($item = $gallery->fetch_assoc()){

?>

<div class="gallery-card">

    <div class="gallery-image">

<?php

if(!empty($item['photo'])){

?>

<img
src="../uploads/<?= htmlspecialchars($item['photo']); ?>"
alt="Equipment">

<?php

}else{

?>

<img
src="../images/no-image.png"
alt="No Image">

<?php } ?>

    </div>

    <div class="gallery-body">

        <h3>

<?= htmlspecialchars($item['article_name']); ?>

        </h3>

        <p>

<strong>Property No:</strong>

<?= htmlspecialchars($item['property_no']); ?>

        </p>

        <p>

<strong>Brand:</strong>

<?= htmlspecialchars($item['brand']); ?>

        </p>

        <p>

<strong>Model:</strong>

<?= htmlspecialchars($item['model']); ?>

        </p>

        <p>

<strong>Location:</strong>

<?= htmlspecialchars($item['location']); ?>

        </p>

        <span class="status <?= strtolower(str_replace(' ','-',$item['equipment_status'])); ?>">

<?= $item['equipment_status']; ?>

        </span>

    </div>

</div>

<?php } ?>

    </div>

</div>

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

<?php include 'systemusers.php'; ?>
<?php include 'personnel.php'; ?>
<?php include 'articles.php'; ?>
<?php include 'inventory.php'; ?>
<?php include 'borrowing.php'; ?>
<?php include 'report.php'; ?>
<?php include 'setting.php'; ?>

<!-- modals -->
<?php include 'modals/adduser.php'; ?>
<?php include 'modals/addpersonnel.php'; ?>
<?php include 'modals/addarticle.php'; ?>
<?php include 'modals/addinventory.php'; ?>
<?php include 'modals/borrowing.php'; ?>
<?php include 'modals/importCSVModal.php'; ?>

<!-- FUNCTIONS -->

<script src="js/sidebar.js"></script>
<script src="js/dashboard.js"></script>

<script src="js/users.js"></script>
<script src="js/personnel.js"></script>
<script src="js/designation.js"></script>
<script src="js/article.js"></script>
<script src="js/inventory.js"></script>
<script src="js/borrowing.js"></script>

<script src="js/main.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

</body>

</html>