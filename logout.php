z    <?php
include "config.php";

$id = $_SESSION['user_id'];

$stmt = $conn->prepare("
UPDATE users
SET status='Offline'
WHERE id=?
");

$stmt->bind_param("i",$id);
$stmt->execute();

session_destroy();

header("Location: index.php");
exit();
?>