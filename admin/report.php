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