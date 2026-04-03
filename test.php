<?php
require_once 'config/db.php';

echo "PDO Connected Successfully!";

// Test query
$result = $pdo->query("SELECT * FROM admin LIMIT 1");
$row = $result->fetch(PDO::FETCH_ASSOC);

echo "<pre>";
print_r($row);
echo "</pre>";
?>
