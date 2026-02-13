<?php
include "config/db.php";

echo "<h2>Fixing Room Status</h2>";
echo "<style>body {font-family: Arial; padding: 20px; background: #f5f5f5;}</style>";

// Fix ALL rooms to Available
$fix_query = "UPDATE rooms SET status='Available'";
if(mysqli_query($conn, $fix_query)) {
    echo "<p style='color:green; font-size:18px;'>✓ SUCCESS: All rooms set to 'Available' status!</p>";
    
    // Show updated status
    $check_query = "SELECT * FROM rooms";
    $check_result = mysqli_query($conn, $check_query);
    
    echo "<h3>Updated Room Status:</h3>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Room No</th><th>Type</th><th>Status</th></tr>";
    
    while($row = mysqli_fetch_assoc($check_result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['room_number'] . "</td>";
        echo "<td>" . $row['room_type'] . "</td>";
        echo "<td style='color:green'>Available</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<br><a href='index.php' style='padding:10px 20px; background:#667eea; color:white; text-decoration:none; border-radius:5px;'>Go to Homepage</a>";
    
} else {
    echo "<p style='color:red'>✗ Error: " . mysqli_error($conn) . "</p>";
}
?>