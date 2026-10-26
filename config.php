<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "inventory_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

/*
|--------------------------------------------------------------------------
| Safe Query Function
|--------------------------------------------------------------------------
| Define only once for the entire project.
| Do NOT redefine it in queries.php or borrowing.php.
|--------------------------------------------------------------------------
*/

if (!function_exists('safeQuery')) {

    function safeQuery($conn, $sql)
    {
        $result = $conn->query($sql);

        if (!$result) {
            die(
                "<h3>Database Error</h3>" .
                "<b>Error:</b> " . htmlspecialchars($conn->error) .
                "<br><br><b>SQL:</b><br><pre>" .
                htmlspecialchars($sql) .
                "</pre>"
            );
        }

        return $result;
    }

}

?>