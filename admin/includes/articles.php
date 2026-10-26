<?php

/* ==========================================
   AUTO GENERATE ARTICLE CODE
========================================== */
function generateArticleCode($conn)
{
    $result = $conn->query("SELECT MAX(id) AS last_id FROM articles");
    $row = $result->fetch_assoc();

    $next = ((int)$row['last_id']) + 1;

    return "ART-" . str_pad($next, 4, "0", STR_PAD_LEFT);
}


/* ==========================================
   ADD ARTICLE
========================================== */

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_article'])) {

    $article_code = generateArticleCode($conn);
    $article_name = trim($_POST['article_name']);
    $category     = trim($_POST['category']);
    $quantity     = (int)$_POST['quantity'];
    $description  = trim($_POST['description']);

    if (empty($article_name) || $quantity <= 0) {

        $message = "<div class='error'>Please complete all required fields.</div>";

    } else {

        $check = $conn->prepare("
            SELECT id
            FROM articles
            WHERE article_name=?
        ");

        $check->bind_param("s", $article_name);
        $check->execute();

        if ($check->get_result()->num_rows > 0) {

            $message = "<div class='error'>Article already exists.</div>";

        } else {

            $available = $quantity;

            $stmt = $conn->prepare("
                INSERT INTO articles
                (
                    article_code,
                    article_name,
                    category,
                    quantity,
                    available_quantity,
                    description
                )
                VALUES (?,?,?,?,?,?)
            ");

            $stmt->bind_param(
                "sssiss",
                $article_code,
                $article_name,
                $category,
                $quantity,
                $available,
                $description
            );

            $stmt->execute();
            $stmt->close();

            header("Location: dashboard.php?page=article");
            exit();
        }

        $check->close();
    }
}


/* ==========================================
   UPDATE ARTICLE
========================================== */

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_article'])) {

    $id           = (int)$_POST['id'];
    $article_name = trim($_POST['article_name']);
    $category     = trim($_POST['category']);
    $quantity     = (int)$_POST['quantity'];
    $description  = trim($_POST['description']);

    if (empty($article_name) || $quantity <= 0) {

        $message = "<div class='error'>Please complete all required fields.</div>";

    } else {

        $check = $conn->prepare("
            SELECT id
            FROM articles
            WHERE article_name=?
            AND id<>?
        ");

        $check->bind_param("si", $article_name, $id);
        $check->execute();

        if ($check->get_result()->num_rows > 0) {

            $message = "<div class='error'>Article already exists.</div>";

        } else {

            $old = $conn->prepare("
                SELECT quantity, available_quantity
                FROM articles
                WHERE id=?
            ");

            $old->bind_param("i", $id);
            $old->execute();

            $oldData = $old->get_result()->fetch_assoc();

            $borrowed = $oldData['quantity'] - $oldData['available_quantity'];

            $available = $quantity - $borrowed;

            if ($available < 0) {
                $available = 0;
            }

            $stmt = $conn->prepare("
                UPDATE articles
                SET
                    article_name=?,
                    category=?,
                    quantity=?,
                    available_quantity=?,
                    description=?
                WHERE id=?
            ");

            $stmt->bind_param(
                "ssiisi",
                $article_name,
                $category,
                $quantity,
                $available,
                $description,
                $id
            );

            $stmt->execute();
            $stmt->close();

            header("Location: dashboard.php?page=article");
            exit();
        }

        $check->close();
    }
}


/* ==========================================
   DELETE ARTICLE
========================================== */

if (isset($_GET['delete_article']) && is_numeric($_GET['delete_article'])) {

    $id = (int)$_GET['delete_article'];

    $stmt = $conn->prepare("
        DELETE
        FROM articles
        WHERE id=?
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php?page=article");
    exit();
}


/* ==========================================
   ARTICLE LIST
========================================== */

$articles = $conn->query("
    SELECT *
    FROM articles
    ORDER BY article_name ASC
");