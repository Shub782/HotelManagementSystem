<?php
$host = "sql201.infinityfree.com";
$user = "if0_41566926";
$pass = "YOUR_VPANEL_PASSWORD";  // Your InfinityFree login password
$db   = "if0_41566926_hotel_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
