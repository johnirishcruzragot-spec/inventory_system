<?php

$designation = $conn->query("
SELECT *
FROM designations
ORDER BY class_name ASC
");

if(isset($_POST['add_designation'])){

    $class_id = trim($_POST['class_id']);
    $class_name = trim($_POST['class_name']);
    $class_level = (int)$_POST['class_level'];

    $stmt = $conn->prepare("
        INSERT INTO designations
        (class_id,class_name,class_level)
        VALUES(?,?,?)
    ");

    $stmt->bind_param(
        "ssi",
        $class_id,
        $class_name,
        $class_level
    );

    $stmt->execute();

    header("Location: dashboard.php?page=personnel");
    exit();

}

//    UPDATE DESIGNATION


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_designation'])) {

    $id = (int)$_POST['id'];
    $class_id = trim($_POST['class_id']);
    $class_name = trim($_POST['class_name']);
    $class_level = (int)$_POST['class_level'];

    $stmt = $conn->prepare("
        UPDATE designations
        SET
            class_id=?,
            class_name=?,
            class_level=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "ssii",
        $class_id,
        $class_name,
        $class_level,
        $id
    );

    $stmt->execute();

    $stmt->close();

    header("Location: dashboard.php?page=personnel");
    exit();
}

$archive = $conn->query("
SELECT *
FROM personnel
WHERE archived='Yes'
ORDER BY fullname ASC
");

if(isset($_GET['archive']) && is_numeric($_GET['archive'])){

    $id = (int)$_GET['archive'];

    $stmt = $conn->prepare("
        UPDATE personnel
        SET archived='Yes'
        WHERE id=?
    ");

    $stmt->bind_param("i",$id);
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php?page=personnel");
    exit();
}

if(isset($_GET['restore']) && is_numeric($_GET['restore'])){

    $id=(int)$_GET['restore'];

    $stmt=$conn->prepare("
        UPDATE personnel
        SET archived='No'
        WHERE id=?
    ");

    $stmt->bind_param("i",$id);
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php?page=personnel");
    exit();
}