<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "Email already exists.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fullname, $email, $password, $role);

        if ($stmt->execute()) {
            echo "User created successfully.";
        } else {
            echo "Registration failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
</head>

<body>

<h2>Create Account</h2>

<form method="POST">

<label>Full Name</label><br>
<input type="text" name="fullname" required><br><br>

<label>Email</label><br>
<input type="email" name="email" required><br><br>

<label>Password</label><br>
<input type="password" name="password" required><br><br>

<label>Role</label><br>

<select name="role">
    <option value="admin">Admin</option>
    <option value="custodian">Custodian</option>
    <option value="encoder">Encoder</option>
    <option value="supply_officer">Supply Officer</option>
</select>

<br><br>

<button type="submit">Create User</button>

</form>

<br>

<br><br>

<a href="index.php">Already have an account? Login</a>

</body>
</html>