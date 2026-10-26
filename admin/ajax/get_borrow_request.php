<?php
include "../../config.php";
include "../includes/auth.php";

if (!isset($_GET['id'])) {
    exit("Invalid request.");
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("
SELECT
    b.*,
    p.fullname,
    p.position,
    p.division,
    i.property_no,
    i.serial_number,
    i.item_description,
    i.unit_value,
    a.article_name
FROM borrowing b

LEFT JOIN personnel p
ON b.personnel_id = p.id

LEFT JOIN inventory i
ON b.inventory_id = i.id

LEFT JOIN articles a
ON i.article_id = a.id

WHERE b.id=?
LIMIT 1
");

$stmt->bind_param("i",$id);
$stmt->execute();

$result = $stmt->get_result();

if($row = $result->fetch_assoc()){

    header("Content-Type: application/json");

    echo json_encode($row);

}