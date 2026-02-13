// <?php
// $host = "localhost";
// $user = "root";
// $pass = "";
// $db   = "hotel_db";

// $conn = mysqli_connect($host, $user, $pass, $db);

// if (!$conn) {
//     die("Database connection failed: " . mysqli_connect_error());
// }
// ?>

<?php
// INFINITYFREE DATABASE CONFIGURATION
// REPLACE these values with YOUR actual InfinityFree MySQL details

$host = "sql311.infinityfree.com";  // CHANGE THIS - Get from InfinityFree cPanel
$user = "if0_41146710";             // Your MySQL username (same as FTP username)
$pass = "ShubhamX9008";      // CHANGE THIS - Set in InfinityFree MySQL
$db   = "if0_41146710_hotel_db";    // Format: username_database_name

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Optional: Set charset to avoid encoding issues
mysqli_set_charset($conn, "utf8");
?>
