<?php
session_start();

include "../config.php";
include "includes/auth.php";

// ======================================================
// ALLOW ONLY CUSTODIAN
// ======================================================

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] != "custodian") {

    header("Location: ../login.php");
    exit();

}

$currentPage = $_GET['page'] ?? 'dashboard';

// ======================================================
// DASHBOARD STATISTICS
// ======================================================

// Total Equipment
$totalEquipment = $conn->query("
SELECT COUNT(*) AS total
FROM inventory
")->fetch_assoc()['total'] ?? 0;

// Available Equipment
$availableEquipment = $conn->query("
SELECT COUNT(*) AS total
FROM inventory
WHERE equipment_status='Available'
")->fetch_assoc()['total'] ?? 0;

// Borrowed Equipment
$borrowedEquipment = $conn->query("
SELECT COUNT(*) AS total
FROM inventory
WHERE equipment_status='Borrowed'
")->fetch_assoc()['total'] ?? 0;

// Under Repair
$repairEquipment = $conn->query("
SELECT COUNT(*) AS total
FROM inventory
WHERE `condition`='Under Repair'
")->fetch_assoc()['total'] ?? 0;

// Inventory Value
$inventoryValue = $conn->query("
SELECT IFNULL(SUM(unit_value * qty),0) AS total
FROM inventory
")->fetch_assoc()['total'] ?? 0;

// Personnel
$totalPersonnel = $conn->query("
SELECT COUNT(*) AS total
FROM personnel
WHERE archived='No'
")->fetch_assoc()['total'] ?? 0;

// Borrow Requests
$totalBorrowing = $conn->query("
SELECT COUNT(*) AS total
FROM borrow_requests
")->fetch_assoc()['total'] ?? 0;

// Articles
$totalArticles = $conn->query("
SELECT COUNT(*) AS total
FROM articles
")->fetch_assoc()['total'] ?? 0;


// ======================================================
// BORROWING ACTIVITY
// ======================================================

$statusList = [];

$result = $conn->query("
SELECT
    status,
    COUNT(*) AS total
FROM borrow_requests
GROUP BY status
");

while($row = $result->fetch_assoc()){

    $statusList[$row['status']] = $row['total'];

}


// ======================================================
// EQUIPMENT BY CATEGORY
// ======================================================

$categoryStats = $conn->query("
SELECT
    articles.article_name,
    COUNT(inventory.id) AS total
FROM articles
LEFT JOIN inventory
    ON inventory.article_id = articles.id
GROUP BY
    articles.id,
    articles.article_name
ORDER BY
    total DESC
");


// ======================================================
// LOAD MODULES
// ======================================================

include "includes/personnel.php";
include "includes/inventory.php";
include "includes/borrowing.php";
include "includes/reports.php";
include "includes/settings.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>DICT IMS - Custodian</title>

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


<div class="sidebar">

<div class="logo">

    <div class="logo-icon">

        <button 
            type="button" 
            class="hamburger" 
            onclick="toggleSidebar()" 
            aria-label="Toggle sidebar"> 
            ☰ 
        </button>

    </div>

    <div class="logo-text">
        <h2>DICT·IMS</h2>
        <p>Asset Accountability</p>
    </div>

</div>

<div class="workspace">
    <small>Current Workspace</small>
    <h4>Custodian Workspace</h4>
</div>

    <ul>

        <li>
            <a href="dashboard.php?page=dashboard" class="menu">
            <span class="icon">📊</span>
            <span class="text">Overview</span>
            </a>
        </li>

        <li>
            <a href="dashboard.php?page=personnel" class="menu">
            <span class="icon">🧑</span>
            <span class="text">Personnel</span>
            </a>
        </li>

        <li>
            <a href="dashboard.php?page=inventory" class="menu">
            <span class="icon">📦</span>
            <span class="text">Inventory</span>
            </a>
        </li>

        <li>
            <a href="dashboard.php?page=validation" class="menu">
            <span class="icon">✔</span>
            <span class="text">Borrowing</span>
            </a>
        </li>

        <li>
            <a href="dashboard.php?page=reports" class="menu">
            <span class="icon">📈</span>
            <span class="text">Reports</span>
            </a>
        </li>

    </ul>

    <div class="sidebar-bottom">

        <a href="dashboard.php?page=settings" class="menu">
            <span class="icon">⚙</span>
            <span class="text">Settings</span>
        </a>

        <a href="../logout.php" class="logout">
            <span class="icon">🚪</span>
            <span class="text">Logout</span>
        </a>

    </div>

</div>


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
 

<!-- Reports -->
<div id="reports" class="page" style="<?= $currentPage === 'reports' ? '' : 'display:none;'; ?>">

<div class="reports-header">

    <div>
        <span class="section-label">REPORTS & ANALYTICS</span>
        <h2>System Reports</h2>
        <p>Overview of inventory, borrowing, personnel, and users.</p>
    </div>

    <button class="add-equipment-btn" onclick="window.print()">
        🖨 Print Report
    </button>

</div>

<div class="report-cards">

<div class="report-card">
    <div style="font-size:40px;">📦</div>
    <h3><?= $totalEquipment; ?></h3>
    <p>Total Equipment</p>
</div>

<div class="report-card">
    <div style="font-size:40px;">📚</div>
    <h3><?= $article_result->num_rows; ?></h3>
    <p>Total Articles</p>
</div>

<div class="report-card">
    <div style="font-size:40px;">👥</div>
    <h3><?= $personnel_result->num_rows; ?></h3>
    <p>Total Personnel</p>
</div>

<div class="report-card">
    <div style="font-size:40px;">👤</div>
    <h3><?= $result->num_rows; ?></h3>
    <p>System Users</p>
</div>

<div class="report-card">
    <div style="font-size:40px;">📚</div>
    <h3><?= $borrowing->num_rows; ?></h3>
    <p>Borrowing Records</p>
</div>

<div class="report-card">

    <div style="font-size:40px;">💰</div>

    <h3>

        ₱<?= number_format($inventoryValue,2); ?>

    </h3>

    <p>Total Inventory Value</p>

</div>

</div>


<div class="report-section">

<div class="report-cards">

<div class="report-card">
<h3><?= $availableEquipment; ?></h3>
<p>Available</p>
</div>

<div class="report-card">
<h3><?= $borrowedEquipment; ?></h3>
<p>Borrowed</p>
</div>

<div class="report-card">
<h3><?= $repairEquipment; ?></h3>
<p>Under Repair</p>
</div>

<div class="report-card">
<h3><?= $lostEquipment; ?></h3>
<p>Lost</p>
</div>

</div>

<h3>Inventory Status</h3>

<table class="report-table">

<thead>

<tr>

<th>Status</th>
<th>Total</th>

</tr>

</thead>

<tbody>

<?php

$status = $conn->query("
SELECT
    `condition`,
    COUNT(*) AS total
FROM inventory
GROUP BY `condition`
");

while ($row = $status->fetch_assoc()) {

?>

<tr>

    <td><?= htmlspecialchars($row['condition'] ?? ''); ?></td>

    <td><?= $row['total']; ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>


<div class="report-section">

<h3>Borrowing Status</h3>

<table class="report-table">

<thead>

<tr>

<th>Status</th>
<th>Total</th>

</tr>

</thead>

<tbody>

<?php foreach($statusCount as $status=>$count){ ?>

<tr>

<td><?= $status; ?></td>

<td><?= $count; ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>


<div class="report-section">

<h3>Latest Borrowing</h3>

<table class="report-table">

<thead>

<tr>

<th>Borrow ID</th>
<th>Equipment</th>
<th>Borrower</th>
<th>Status</th>
<th>Borrow Date</th>

</tr>

</thead>

<tbody>

<?php

$recentBorrow = $conn->query("
SELECT
    br.id,
    br.request_no,
    br.status,
    br.borrow_date,
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
LIMIT 10
");

while($row=$recentBorrow->fetch_assoc()){

?>

<tr>

<td>
<?= htmlspecialchars($row['request_no']); ?>
</td>

<td><?= htmlspecialchars($row['article_name'] ?? 'N/A'); ?></td>

<td><?= htmlspecialchars($row['fullname']); ?></td>

<td>
<span class="status <?= strtolower($row['status']); ?>">
<?= $row['status']; ?>
</span>
</td>

<td><?= date("M d, Y",strtotime($row['borrow_date'])); ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

<!-- Settings -->
<div id="settings" class="page" style="<?= $currentPage === 'settings' ? '' : 'display:none;'; ?>">

    <div class="settings-header">

        <div>
            <span class="section-label">
                SYSTEM SETTINGS
            </span>

            <h2>Settings</h2>

            <p>
                Configure the DICT Inventory Management System.
            </p>
        </div>

    </div>


    <div class="settings-grid">

        <!-- System Information -->
        <div class="settings-card">

            <h3>🏢 System Information</h3>

            <div class="form-group">
                <label>Office Name</label>
                <input
                    type="text"
                    value="Department of Information and Communications Technology"
                    readonly>
            </div>

            <div class="form-group">
                <label>Office Code</label>
                <input
                    type="text"
                    value="DICT"
                    readonly>
            </div>

            <div class="form-group">
                <label>System Version</label>
                <input
                    type="text"
                    value="1.0.0"
                    readonly>
            </div>

        </div>


        <!-- Account -->
        <div class="settings-card">

            <h3>👤 Administrator</h3>

            <div class="form-group">
                <label>Full Name</label>
                <input
                    type="text"
                    value="<?= htmlspecialchars($_SESSION['fullname']); ?>"
                    readonly>
            </div>

            <div class="form-group">
                <label>Role</label>
                <input
                    type="text"
                    value="<?= ucfirst($_SESSION['role']); ?>"
                    readonly>
            </div>

            <button class="save-btn">
                Change Password 
            </button>

        </div>


        <!-- Inventory -->
        <div class="settings-card">

            <h3>📦 Inventory Settings</h3>

            <label>

                <input type="checkbox" checked>

                Enable QR Code Generation

            </label>

            <label>

                <input type="checkbox" checked>

                Auto Update Equipment Status

            </label>

            <label>

                <input type="checkbox">

                Archive Disposed Equipment

            </label>

        </div>


        <!-- Borrowing -->
        <div class="settings-card">

            <h3>📋 Borrowing Settings</h3>

            <label>

                <input type="checkbox" checked>

                Require Approval Before Borrowing

            </label>

            <label>

                <input type="checkbox" checked>

                Enable Return Validation

            </label>

            <label>

                <input type="checkbox">

                Email Notifications

            </label>

        </div>

                <!-- Database -->
<div class="form-group">
    <label>Database</label>
    <input type="text" value="<?= $dbname; ?>" readonly>
</div>

<div class="form-group">
    <label>Server</label>
    <input type="text" value="localhost" readonly>
</div>

<button class="action-btn">
    💾 Backup Database
</button>


        <!-- Maintenance -->
        <div class="settings-card">

            <h3>⚙ Maintenance</h3>

<button class="action-btn">
    🗑 Clear Cache
</button>

<button class="action-btn">
    📄 Export Logs
</button>

<button class="logout-btn action-btn">
    🚪 Logout
</button>

        </div>

    </div>

</div>

</div>

<!-- modals -->

<!-- Add User Modal -->
<div class="modal" id="userModal">

    <div class="modal-content">

        <div class="modal-header">

            <h3 id="userModalTitle">Add User</h3>

            <span class="close"
                onclick="closeUserModal()">
                &times;
            </span>

        </div>

        <form method="POST" id="userForm">

            <div class="modal-grid">

                <div class="form-group">

                    <input
                    type="hidden"
                    name="id" 
                    id="user_id">   

                    <label>Full Name</label>

                    <input
                    type="text"
                    name="fullname"
                    id="user_fullname">

                </div>

                <div class="form-group">

                    <label>Email</label>

                    <input
                    type="email"
                    name="email"
                    id="user_email">

                </div>

                <div class="form-group">

                    <label id="passwordLabel">
                        Password
                    </label>

                    <small id="passwordNote">
                    Required when creating a user.
                    </small>

                    <input
                        type="password"
                        name="password"
                        id="user_password"
                        required>

                </div>

                <div class="form-group">

                <label>Division / PMO</label>

                    <select name="division" id="user_division" required>

                    <option value="">-- Select Division --</option>

                    <option value="Administrative and Finance Division">Administrative and Finance Division</option>

                    <option value="Technical Operations Division">Technical Operations Division</option>

                    </select>

                </div>

                <div class="form-group">

                    <label>Role</label>

                    <select name="role" id="user_role">

                        <option value="admin">Admin</option>
                        <option value="custodian">Custodian</option>
                        <option value="encoder">Encoder</option>
                        <option value="supply_officer">Supply Officer</option>
                        <option value="viewer">Viewer</option>

                    </select>

                </div>

            </div>
            
<div class="form-group" id="statusGroup" style="display:none;">

    <label>Status</label>

    <select name="status" id="user_status">

        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
        <option value="blocked">Blocked</option>

    </select>

</div>

            <div class="modal-buttons">

                <button
                    type="button"
                    class="cancel-btn"
                    onclick="closeUserModal()">

                    Cancel

                </button>

<button
    type="submit"
    id="saveUserBtn"
    name="add_user"
    class="save-btn">

    Create User

</button>

            </div>

        </form>

    </div>

</div>


<!-- Personnel Modal -->
<div class="modal" id="personnelModal">

    <div class="modal-content">

        <div class="modal-header">
            <h3 id="personnelModalTitle">Add Personnel</h3>

            <span class="close" onclick="closePersonnelModal()">
                &times;
            </span>
        </div>

        <form method="POST" id="personnelForm">

            <input type="hidden" name="id" id="personnelId">

            <div class="modal-grid">

                <div class="form-group">
                    <label>Employee ID</label>
                    <input type="text" name="employee_id" id="employee_id" required>
                </div>

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="fullname" id="fullname" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email">
                </div>

                <div class="form-group">
                    <label>Contact Number</label>

                    <input type="text" name="contact_number" id="contact_number"></div>

                <div class="form-group">
                    <label>Position/Designation</label>
                    <input type="text" name="position" id="position">
                </div>

                <div class="form-group">
                <label>Designation</label>
                    <input type="text" name="designation" id="designation">
                </div>

                <div class="form-group">

                <label>Division / PMO</label>

                    <select name="division" id="division" required>

                    <option value="">-- Select Division --</option>

                    <option value="Administrative and Finance Division">Administrative and Finance Division</option>

                    <option value="Technical Operations Division">Technical Operations Division</option>

                    </select>

                </div>

<div class="form-group">
<label>Status</label>

<select name="status" id="status">
    <option value="Active">Active</option>
    <option value="Inactive">Inactive</option>
    <option value="Blocked">Blocked</option>

</select>
</div>

<div class="form-group">
<label>Employment Status</label>

<select name="employment_status" id="employment_status">
    <option value="Permanent">Permanent</option>
    <option value="Contractual">Contractual</option>
    <option value="COS">COS</option>
    <option value="JO">JO</option>
</select>
</div>

</div>

<div class="modal-buttons">

    <button type="button"
            class="cancel-btn"
            onclick="closePersonnelModal()">
        Cancel
    </button>

    <button type="submit"
            id="savePersonnelBtn"
            name="add_personnel"
            class="save-btn">
        Save Personnel
    </button>

</div>

</form>

</div>

</div>

<!-- Personnel Details Modal -->
<div class="modal" id="personnelDetailsModal">

    <div class="modal-content personnel-details-modal">

        <div class="personnel-details-header">

            <button
                type="button"
                class="back-btn"
                onclick="closePersonnelDetailsModal()">
                ← Back
            </button>

            <h2>Personnel Information</h2>

            <button
                type="button"
                class="add-equipment-btn">
                + Assign Equipment
            </button>

        </div>

        <div
            class="personnel-details-body"
            id="personnelDetailsBody">

        </div>

    </div>

</div>  


<div id="designationModal" class="modal">

    <div class="modal-content">

        <div class="modal-header">

            <h3 id="designationModalTitle">
                Add Designation
            </h3>

            <span
                class="close"
                onclick="closeDesignationModal()">
                &times;
            </span>

        </div>

        <form method="POST" id="designationForm">

            <input
                type="hidden"
                name="id"
                id="designation_id">

            <div class="modal-grid">

                <div class="form-group">

                    <label>Designation ID</label>

                    <input
                        type="text"
                        name="class_id"
                        id="designation_class_id"
                        required>

                </div>

                <div class="form-group">

                    <label>Designation Name</label>

                    <input
                        type="text"
                        name="class_name"
                        id="designation_class_name"
                        required>

                </div>

                <div class="form-group">

                    <label>Designation Level</label>

                    <input
                        type="number"
                        name="class_level"
                        id="designation_class_level"
                        required>

                </div>

            </div>

            <div class="modal-buttons">

                <button
                    type="button"
                    class="cancel-btn"
                    onclick="closeDesignationModal()">

                    Cancel

                </button>

                <button
                    type="submit"
                    id="saveDesignationBtn"
                    name="add_designation"
                    class="save-btn">

                    Save

                </button>

            </div>

        </form>

    </div>

</div>

<!-- ==========================================================
     ARTICLE MODAL
========================================================== -->

<div class="modal" id="articleModal">

    <div class="modal-content article-modal">

        <div class="modal-header">

            <div>

                <h3 id="articleModalTitle">

                    Add Equipment Article

                </h3>

                <small>

                    Register a new equipment type for the inventory system.

                </small>

            </div>

            <span
                class="close"
                onclick="closeArticleModal()">

                &times;

            </span>

        </div>

        <form
            method="POST"
            id="articleForm">

            <input
                type="hidden"
                name="id"
                id="article_id">

            <div class="modal-grid">

                <!-- Article Name -->

                <div class="form-group">

                    <label>

                        Article Name
                        <span style="color:#e94804">*</span>

                    </label>

                    <input
                        type="text"
                        name="article_name"
                        id="article_name"
                        placeholder="Example: Laptop"
                        required>

                </div>

                <!-- Category -->

                <div class="form-group">

                    <label>

                        Category
                        <span style="color:#e94804">*</span>

                    </label>

                    <select
                        name="category"
                        id="category"
                        required>

                        <option value="">

                            -- Select Category --

                        </option>

                        <option value="ICT Equipment">ICT Equipment</option>
                        <option value="Computer Equipment">Computer Equipment</option>
                        <option value="Networking Equipment">Networking Equipment</option>
                        <option value="Printers and Scanners">Printers and Scanners</option>
                        <option value="Communication Equipment">Communication Equipment</option>
                        <option value="Office Equipment">Office Equipment</option>
                        <option value="Power Equipment">Power Equipment</option>
                        <option value="Furniture and Fixtures">Furniture and Fixtures</option>
                        <option value="Appliances">Appliances</option>
                        <option value="Tools and Instruments">Tools and Instruments</option>

                    </select>

                </div>

                <!-- Estimated Quantity -->

                <div class="form-group">

                    <label>

                        Estimated Quantity

                    </label>

                    <input
                        type="number"
                        name="quantity"
                        id="article_quantity"
                        min="0"
                        value="0"
                        placeholder="0">

                    <small>

                        Initial quantity for planning purposes.

                    </small>

                </div>

                <!-- Status -->

                <div class="form-group">

                    <label>

                        Status

                    </label>

                    <select
                        name="status"
                        id="article_status">

                        <option value="Active" selected>

                            Active

                        </option>

                        <option value="Inactive">

                            Inactive

                        </option>

                    </select>

                </div>

                <!-- Description -->

                <div
                    class="form-group"
                    style="grid-column:1 / -1;">

                    <label>

                        Description

                    </label>

                    <textarea
                        name="description"
                        id="description"
                        rows="5"
                        placeholder="Enter additional information about this equipment type..."></textarea>

                </div>

            </div>

            <div class="modal-buttons">

                <button
                    type="button"
                    class="cancel-btn"
                    onclick="closeArticleModal()">

                    Cancel

                </button>

                <button
                    type="submit"
                    id="saveArticleBtn"
                    name="add_article"
                    class="save-btn">

                    <i class="fa-solid fa-floppy-disk"></i>

                    Save Article

                </button>

            </div>

        </form>

    </div>

</div>


<!-- Add Inventory Modal -->
<div class="modal" id="addInventoryModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="addInventoryModalTitle">Add Equipment</h3>
            <span class="close" onclick="closeInventoryModal()">&times;</span>
        </div>

        <form method="POST" enctype="multipart/form-data" id="inventoryForm">

        <div class="modal-page active" id="inventoryPage1">

    <div class="modal-grid">

        <div class="form-group">
            <label>ICS No.</label>
            <input type="text" name="ics_no" id="inventory_ics_no">
        </div>

        <div class="form-group">
            <label>Province</label>
            <input type="text" name="province" id="inventory_province">
        </div>

        <div class="form-group">
            <label>Property Number</label>
            <input type="text" name="property_no" id="inventory_property_no" required>
        </div>

        <div class="form-group">
            <label>Year</label>
            <input type="text" name="year" id="inventory_year">
        </div>

        <div class="form-group">
            <label>Month</label>
            <input type="text" name="month" id="inventory_month">
        </div>

        <div class="form-group">
            <label>Object Code</label>
            <input type="text" name="object_code" id="inventory_object_code">
        </div>

        <div class="form-group">
            <label>Series</label>
            <input type="text" name="series" id="inventory_series">
        </div>

        <div class="form-group">
            <label>Location Code</label>
            <input type="text" name="location_code" id="inventory_location_code">
        </div>

    </div>

</div>

<div class="modal-page" id="inventoryPage2">

    <div class="modal-grid">

        <div class="form-group">

            <label>Article</label>

            <select name="article_id" id="inventory_article_id" required>

                <option value="">Select Article</option>

                <?php
                $articles = $conn->query("SELECT id, article_name FROM articles ORDER BY article_name");
                while($a = $articles->fetch_assoc()){
                ?>

                <option value="<?= $a['id']; ?>">
                    <?= htmlspecialchars($a['article_name']); ?>
                </option>

                <?php } ?>

            </select>

        </div>

        <div class="form-group">
            <label>Type of PPE</label>
            <input type="text" name="type_of_ppe" id="inventory_type_of_ppe">
        </div>

        <div class="form-group" style="grid-column:1/-1;">
            <label>Item Description</label>
            <textarea name="item_description" id="inventory_item_description" rows="3"></textarea>
        </div>

        <div class="form-group">
            <label>Serial Number</label>
            <input type="text" name="serial_number" id="inventory_serial_number">
        </div>

        <div class="form-group">
            <label>Date Acquired</label>
            <input type="date" name="date_acquired" id="inventory_date_acquired">
        </div>

        <div class="form-group">
            <label>Qty</label>
            <input type="number" name="qty" id="inventory_qty" min="1">
        </div>

        <div class="form-group">
            <label>Unit of Measure</label>
            <input type="text" name="unit_of_measure" id="inventory_unit_of_measure">
        </div>

        <div class="form-group">
            <label>Unit Value</label>
            <input type="number" step="0.01" name="unit_value" id="inventory_unit_value">
        </div>

    </div>

</div>

<div class="modal-page" id="inventoryPage3">

    <div class="modal-grid">

        <div class="form-group">
            <label>SE/E</label>
            <input type="text" name="se_e" id="inventory_se_e">
        </div>

        <div class="form-group">
            <label>Condition</label>

            <select name="condition" id="inventory_condition">

                <option value="">Select Condition</option>

                <option value="Serviceable">Serviceable</option>
                <option value="Unserviceable">Unserviceable</option>
                <option value="Missing">Missing</option>

            </select>

        </div>

        <div class="form-group" style="grid-column:1/-1;">
            <label>Remarks</label>
            <textarea name="remarks" id="inventory_remarks" rows="3"></textarea>
        </div>

        <div class="form-group" style="grid-column:1/-1;">

            <label>Accountable Officer</label>

            <select name="accountable_officer_id" id="inventory_accountable_officer_id">

                <option value="">Unassigned</option>

                <?php
                $personnelList = $conn->query("SELECT id, fullname FROM personnel WHERE archived='No' ORDER BY fullname");
                while($p = $personnelList->fetch_assoc()){
                ?>

                <option value="<?= $p['id']; ?>">
                    <?= htmlspecialchars($p['fullname']); ?>
                </option>

                <?php } ?>

            </select>

        </div>

    </div>

</form>

</div>

<div class="modal-buttons">

    <button
        type="button"
        class="cancel-btn"
        onclick="closeInventoryModal()">

        Cancel

    </button>

    <button
        type="button"
        class="prev-btn"
        id="prevInventoryBtn"
        onclick="previousInventoryPage()"
        style="display:none;">

        Previous

    </button>

    <button
        type="button"
        class="next-btn"
        id="nextInventoryBtn"
        onclick="nextInventoryPage()">

        Next

    </button>

    <button
        type="submit"
        class="save-btn"
        id="addInventorySaveBtn"
        name="add_inventory"
        style="display:none;">

        Save Equipment

    </button>

</div>
        </form>
    </div>
</div>

<!-- borrowing modals -->

<div class="modal" id="borrowModal">

    <div class="modal-content borrow-modal">

        <div class="modal-header">

            <h3 id="borrowTitle">

                New Borrowing Request

            </h3>

            <span
                class="close"
                onclick="closeBorrowModal()">

                &times;

            </span>

        </div>

        <form
            id="borrowForm"
            method="POST"
            action="dashboard.php?page=validation">

            <input
                type="hidden"
                name="borrow_id"
                id="borrow_id"
                value="0">  

            <div class="modal-grid">

                <!-- Equipment -->

                <div class="form-group">

                    <label>Equipment <span style="color:#e94804">*</span></label>

                    <select
                        name="article_id"
                        id="borrow_article"
                        required>

                        <option value="">
                            -- Select Equipment --
                        </option>

<?php

$equipment = $conn->query("

SELECT
    id,
    article_name
FROM articles
ORDER BY article_name ASC

");

while($item = $equipment->fetch_assoc()){ ?>

<option value="<?= (int)$item['id']; ?>">

<?= htmlspecialchars($item['article_name']); ?>

</option>

<?php } ?>

                    </select>

                </div>

                <!-- Borrower -->

                <div class="form-group">

                    <label>Borrower <span style="color:#e94804">*</span></label>

                    <select
                        name="borrower_id"
                        id="borrow_borrower"
                        required>

                        <option value="">
                            -- Select Borrower --
                        </option>

<?php

$list = $conn->query("

SELECT
    id,
    fullname

FROM personnel

WHERE archived='No'

ORDER BY fullname

");

while($p = $list->fetch_assoc()){

?>

<option value="<?= (int)$p['id']; ?>">

<?= htmlspecialchars($p['fullname']); ?>

</option>

<?php } ?>

                    </select>

                </div>

                <!-- Purpose -->

                <div
                    class="form-group"
                    style="grid-column:1 / span 2;">

                    <label>Purpose <span style="color:#e94804">*</span></label>

                    <textarea
                        name="purpose"
                        id="borrow_purpose"
                        rows="4"
                        required
                        placeholder="State the purpose of borrowing..."></textarea>

                </div>

                <!-- Borrow Date -->

                <div class="form-group">

                    <label>Borrow Date</label>

                    <input
                        type="date"
                        name="borrow_date"
                        id="borrow_date"
                        value="<?= date('Y-m-d'); ?>"
                        required>

                </div>

                <!-- Expected Return -->

                <div class="form-group">

                    <label>Expected Return</label>

                    <input
                        type="date"
                        name="expected_return"
                        id="expected_return">

                </div>

                <!-- Quantity -->

                <div class="form-group">

                    <label>Quantity</label>

                    <input
                        type="number"
                        name="quantity"
                        id="borrow_quantity"
                        value="1"
                        min="1"
                        required>

                </div>

                <!-- Permanent Release -->

                <div class="form-group">

                    <label>Permanent Release</label>

                    <select
                        name="permanent_release"
                        id="permanent_release">

                        <option value="No" selected>

                            No

                        </option>

                        <option value="Yes">

                            Yes

                        </option>

                    </select>

                </div>

                <!-- Remarks -->

                <div
                    class="form-group"
                    style="grid-column:1 / span 2;">

                    <label>Remarks</label>

                    <textarea
                        name="remarks"
                        id="borrow_remarks"
                        rows="3"
                        placeholder="Optional remarks..."></textarea>

                </div>

                <!-- Status -->

                <div class="form-group">

                    <label>Current Status</label>

                    <input
                        type="text"
                        id="borrow_status_display"
                        value="Pending"
                        readonly>

                    <input
                        type="hidden"
                        name="status"
                        id="borrow_status"
                        value="Pending">

                </div>

            </div>

            <div class="modal-buttons">

                <button
                    type="button"
                    class="cancel-btn"
                    onclick="closeBorrowModal()">

                    Cancel

                </button>

<input type="hidden" name="test_post" value="123">

                <button
                    type="submit"
                    name="save_borrowing"
                    class="save-btn">

                    <i class="fa-solid fa-floppy-disk"></i>

                    Save Borrowing

                </button>

            </div>

        </form>

    </div>

</div>
 
<!-- ==========================================
     PROCESS BORROW REQUEST MODAL
========================================== -->

<div class="modal" id="processModal">

    <div class="modal-content process-modal">

        <div class="modal-header">

            <h3 id="processTitle">
                Process Borrow Request
            </h3>

            <span class="close" onclick="closeProcessModal()">
                &times;
            </span>

        </div>

        <form
            id="processForm"
            method="POST"
            action="dashboard.php?page=validation">

            <!-- Request ID -->
            <input
                type="hidden"
                name="request_id"
                id="process_request_id">

            <!-- Approve / Reject -->
            <input
                type="hidden"
                name="decision"
                id="decision"
                value="Approved">

            <!-- Current Status -->
            <div class="form-group">

                <label>Current Status</label>

                <input
                    type="text"
                    id="process_current_status"
                    readonly>

            </div>

            <!-- Next Action -->
            <div class="form-group">

                <label>Next Action</label>

                <input
                    type="text"
                    id="process_next_action"
                    readonly>

            </div>

            <!-- Remarks -->
            <div class="form-group">

                <label>Remarks</label>

                <textarea
                    name="remarks"
                    rows="4"
                    placeholder="Enter remarks..."></textarea>

            </div>

            <div class="modal-buttons">

                <button
                    type="button"
                    class="cancel-btn"
                    onclick="closeProcessModal()">

                    Cancel

                </button>

                <button
                    type="submit"
                    name="process_request"
                    class="delete-btn"
                    onclick="document.getElementById('decision').value='Rejected';">

                    <i class="fa-solid fa-circle-xmark"></i>

                    Reject

                </button>

                <button
                    type="submit"
                    name="process_request"
                    class="save-btn"
                    onclick="document.getElementById('decision').value='Approved';">

                    <i class="fa-solid fa-circle-check"></i>

                    Approve

                </button>

            </div>

        </form>

    </div>

</div>


<!-- importCSVModal -->

<div class="modal" id="importCSVModal">

    <div class="modal-content" style="max-width:550px;">

        <div class="modal-header">

            <h3>Import Inventory CSV</h3>

            <span
                class="close"
                onclick="closeImportCSVModal()">
                &times;
            </span>

        </div>

        <form
            method="POST"
            enctype="multipart/form-data">

            <div class="modal-body">

                <div class="form-group">

                    <label>Select CSV File</label>

                    <input
                        type="file"
                        name="inventory_csv"
                        accept=".csv"
                        required>

                </div>

                <small>

                    Accepted format:
                    .csv (Comma Separated Values)

                </small>

            </div>

            <div class="modal-buttons">

                <button
                    type="button"
                    class="cancel-btn"
                    onclick="closeImportCSVModal()">

                    Cancel

                </button>

                <button
                    type="submit"
                    name="import_inventory_csv"
                    class="save-btn">

                    Import CSV

                </button>

            </div>

        </form>

    </div>

</div>

<!-- QR modal -->

<div id="qrModal" class="modal">

    <div class="modal-content qr-modal">

        <span class="close" onclick="closeQRModal()">&times;</span>

        <h2>
            <i class="fa-solid fa-qrcode"></i>
            Inventory QR Code
        </h2>

        <div id="qrcode"></div>

        <div class="qr-info">

            <p><strong>ICS No:</strong> <span id="qrICS"></span></p>

            <p><strong>Property No:</strong> <span id="qrProperty"></span></p>

            <p><strong>Article:</strong> <span id="qrArticle"></span></p>

            <p><strong>Description:</strong> <span id="qrDescription"></span></p>

            <p><strong>Serial No:</strong> <span id="qrSerial"></span></p>

            <p><strong>Accountable Officer:</strong> <span id="qrOfficer"></span></p>

        </div>

        <button onclick="printQRCode()" class="print-btn">

            <i class="fa-solid fa-print"></i>

            Print QR

        </button>

    </div>

</div>

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







<!-- SIDEBAR -->
<!-- MAIN -->
<!-- overview -->
<!-- Users -->
<!-- Personnel -->
<!-- Article -->
<!-- Inventory -->
<!-- Gallery -->
<!-- Borrowing -->
<!-- Reports -->
<!-- Settings -->

<!-- Add User Modal -->
<!-- Personnel Modal -->
<!-- Personnel Details Modal -->
<!-- ARTICLE MODAL -->
<!-- add inventory Modal -->
<!-- Edit Inventory Modal -->
<!-- borrowing modals -->
<!-- Delete User Modal -->
<!-- importCSVModal -->