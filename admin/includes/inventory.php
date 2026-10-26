
<?php

$inventory = $conn->query("
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


/* ==========================================
   ADD INVENTORY
========================================== */

if(isset($_POST['add_inventory'])){

    $photo = "";

    if(!empty($_FILES['photo']['name'])){

        $photo = time() . "_" . basename($_FILES['photo']['name']);

        move_uploaded_file(
            $_FILES['photo']['tmp_name'],
            "../uploads/" . $photo
        );

    }

    $personnel_id = !empty($_POST['personnel_id'])
        ? $_POST['personnel_id']
        : NULL;

$stmt = $conn->prepare("
    INSERT INTO inventory
    (
        ics_no,
        province,
        property_no,
        year,
        month,
        object_code,
        series,
        location_code,
        article_id,
        type_of_ppe,
        item_description,
        serial_number,
        date_acquired,
        qty,
        unit_of_measure,
        unit_value,
        se_e,
        `condition`,
        remarks,
        accountable_officer_id
    )
    VALUES
    (
        ?,?,?,?,?,?,?,?,?,?,
        ?,?,?,?,?,?,?,?,?,?
    )
");

$stmt->bind_param(
    "ssssssssissssisdsssi",
    $_POST['ics_no'],
    $_POST['province'],
    $_POST['property_no'],
    $_POST['year'],
    $_POST['month'],
    $_POST['object_code'],
    $_POST['series'],
    $_POST['location_code'],
    $_POST['article_id'],
    $_POST['type_of_ppe'],
    $_POST['item_description'],
    $_POST['serial_number'],
    $_POST['date_acquired'],
    $_POST['qty'],
    $_POST['unit_of_measure'],
    $_POST['unit_value'],
    $_POST['se_e'],
    $_POST['condition'],
    $_POST['remarks'],
    $_POST['accountable_officer_id']
);

    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php?page=inventory");
    exit();
}

/* ==========================================
   UPDATE INVENTORY
========================================== */

if (isset($_POST['update_inventory'])) {

    $id = (int)$_POST['id'];

    $stmt = $conn->prepare("
        UPDATE inventory
        SET
            ics_no=?,
            province=?,
            property_no=?,
            year=?,
            month=?,
            object_code=?,
            series=?,
            location_code=?,
            article_id=?,
            type_of_ppe=?,
            item_description=?,
            serial_number=?,
            date_acquired=?,
            qty=?,
            unit_of_measure=?,
            unit_value=?,
            se_e=?,
            `condition`=?,
            remarks=?,
            accountable_officer_id=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "ssssssssissssisdsssii",
        $_POST['ics_no'],
        $_POST['province'],
        $_POST['property_no'],
        $_POST['year'],
        $_POST['month'],
        $_POST['object_code'],
        $_POST['series'],
        $_POST['location_code'],
        $_POST['article_id'],
        $_POST['type_of_ppe'],
        $_POST['item_description'],
        $_POST['serial_number'],
        $_POST['date_acquired'],
        $_POST['qty'],
        $_POST['unit_of_measure'],
        $_POST['unit_value'],
        $_POST['se_e'],
        $_POST['condition'],
        $_POST['remarks'],
        $_POST['accountable_officer_id'],
        $id
    );

    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php?page=inventory");
    exit();
}

// svc

if(isset($_POST['import_inventory_csv'])){

    if(isset($_FILES['inventory_csv']) &&
       $_FILES['inventory_csv']['error'] == 0){

        $file = fopen($_FILES['inventory_csv']['tmp_name'], "r");

        // Skip header row
        fgetcsv($file);

        while(($data = fgetcsv($file, 1000, ",")) !== FALSE){

            $stmt = $conn->prepare("
                INSERT INTO inventory(
                    ics_no,
                    province,
                    property_no,
                    year,
                    month,
                    object_code,
                    series,
                    location_code,
                    article_id,
                    type_of_ppe,
                    item_description,
                    serial_number,
                    date_acquired,
                    qty,
                    unit_of_measure,
                    unit_value,
                    se_e,
                    `condition`,
                    remarks,
                    accountable_officer_id
                )
                VALUES(
                    ?,?,?,?,?,?,?,?,?,?,
                    ?,?,?,?,?,?,?,?,?,?
                )
            ");

            $stmt->bind_param(
                "ssssssssissssisdsssi",
                $data[0],
                $data[1],
                $data[2],
                $data[3],
                $data[4],
                $data[5],
                $data[6],
                $data[7],
                $data[8],
                $data[9],
                $data[10],
                $data[11],
                $data[12],
                $data[13],
                $data[14],
                $data[15],
                $data[16],
                $data[17],
                $data[18],
                $data[19]
            );

            $stmt->execute();

        }

        fclose($file);

        echo "<script>
            alert('CSV imported successfully.');
            window.location='dashboard.php?page=inventory';
        </script>";

    }

}

