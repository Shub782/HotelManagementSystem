<?php
include "config/db.php";

echo "<h2>Force Fix Room Status</h2>";
echo "<style>
    body {font-family: Arial; padding: 20px; background: #f5f5f5;}
    .success {color: green; font-weight: bold;}
    .error {color: red; font-weight: bold;}
</style>";

// First, let's see current status
echo "<h3>Current Status (BEFORE):</h3>";
$current_query = "SELECT id, room_number, room_type, status FROM rooms";
$current_result = mysqli_query($conn, $current_query);

echo "<table border='1' cellpadding='10' style='background:white;'>";
echo "<tr><th>ID</th><th>Room No</th><th>Type</th><th>Status</th></tr>";

while($row = mysqli_fetch_assoc($current_result)) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['room_number'] . "</td>";
    echo "<td>" . $row['room_type'] . "</td>";
    echo "<td>" . $row['status'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// Now force update with exact value
echo "<h3>Updating status...</h3>";
$update_query = "UPDATE rooms SET status = 'Available'";
if(mysqli_query($conn, $update_query)) {
    echo "<p class='success'>✓ Update query executed successfully!</p>";
    echo "<p>Rows affected: " . mysqli_affected_rows($conn) . "</p>";
} else {
    echo "<p class='error'>✗ Update failed: " . mysqli_error($conn) . "</p>";
}

// Check AFTER update
echo "<h3>Status AFTER Update:</h3>";
$after_query = "SELECT id, room_number, room_type, status FROM rooms";
$after_result = mysqli_query($conn, $after_query);

echo "<table border='1' cellpadding='10' style='background:white;'>";
echo "<tr><th>ID</th><th>Room No</th><th>Type</th><th>Status</th></tr>";

while($row = mysqli_fetch_assoc($after_result)) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['room_number'] . "</td>";
    echo "<td>" . $row['room_type'] . "</td>";
    echo "<td style='color:green; font-weight:bold;'>" . $row['status'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// Also check the exact query index.php uses
echo "<h3>Testing index.php query:</h3>";
$test_query = "SELECT * FROM rooms WHERE status='Available' ORDER BY rating DESC";
$test_result = mysqli_query($conn, $test_query);
$test_count = mysqli_num_rows($test_result);

echo "<p>Query: <code>SELECT * FROM rooms WHERE status='Available' ORDER BY rating DESC</code></p>";
echo "<p>Found <strong>" . $test_count . "</strong> available rooms</p>";

if($test_count > 0) {
    echo "<p class='success'>✓ GOOD: Rooms will show on homepage!</p>";
    echo "<a href='index.php' style='display:inline-block; padding:15px 30px; background:#667eea; color:white; text-decoration:none; border-radius:10px; margin:20px 0;'>Go to Homepage →</a>";
} else {
    echo "<p class='error'>✗ PROBLEM: Still no available rooms!</p>";
    echo "<p>Let's check status values in database...</p>";
    
    // Check all distinct status values
    $status_query = "SELECT DISTINCT status FROM rooms";
    $status_result = mysqli_query($conn, $status_query);
    
    echo "<p>Distinct status values in database:</p>";
    echo "<ul>";
    while($status_row = mysqli_fetch_assoc($status_result)) {
        echo "<li>'<strong>" . $status_row['status'] . "</strong>'</li>";
    }
    echo "</ul>";
}

// Add direct SQL command
echo "<h3>Direct SQL Command:</h3>";
echo "<p>Run this in phpMyAdmin:</p>";
echo "<pre style='background:#333; color:#fff; padding:15px; border-radius:5px;'>
UPDATE `rooms` SET `status` = 'Available';
</pre>";
?>