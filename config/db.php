<?php
try {
    $host = getenv('MYSQLHOST');
    $port = getenv('MYSQLPORT');
    $dbname = getenv('MYSQLDATABASE');
    $user = getenv('MYSQLUSER');
    $pass = getenv('MYSQLPASSWORD');
    
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Your database connection is now $pdo instead of $conn
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
