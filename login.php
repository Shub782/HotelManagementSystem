<?php
session_start();
include "config/db.php";

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid login credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | Hotel Management</title>
    <link rel="stylesheet" href="assets/css/style.css?v=6">
</head>
<body class="login-body">

    <div class="login-container">
        <h2>Admin Login</h2>

        <?php 
        if(isset($error)) {
            echo "<p style='color:red;'>$error</p>";
        }
        ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

    </div>

</body>
</html>
