<?php
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?></h2>

<p>Email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>

<p>Role: <?php echo htmlspecialchars($_SESSION['role']); ?></p>

<a href="logout.php">Logout</a>

</body>
</html>