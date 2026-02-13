<?php
include "config/db.php";

echo "<h3>Debugging Rooms Display</h3>";

// Check connection
if (!$conn) {
    die("Database connection failed!");
}
echo "✓ Database connected<br>";

// Check total rooms count
$total_query = "SELECT COUNT(*) as total FROM rooms";
$total_result = mysqli_query($conn, $total_query);
$total = mysqli_fetch_assoc($total_result);
echo "Total rooms in database: " . $total['total'] . "<br>";

// Check available rooms count
$available_query = "SELECT COUNT(*) as available FROM rooms WHERE status='Available'";
$available_result = mysqli_query($conn, $available_query);
$available = mysqli_fetch_assoc($available_result);
echo "Available rooms: " . $available['available'] . "<br>";

// Show all rooms with status
echo "<h4>All Rooms:</h4>";
$all_query = "SELECT id, room_number, room_type, status FROM rooms";
$all_result = mysqli_query($conn, $all_query);

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Room No</th><th>Type</th><th>Status</th></tr>";

while($row = mysqli_fetch_assoc($all_result)) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['room_number'] . "</td>";
    echo "<td>" . $row['room_type'] . "</td>";
    echo "<td style='color:" . ($row['status'] == 'Available' ? 'green' : 'red') . "'>" . $row['status'] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>