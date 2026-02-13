<?php
include "config/db.php";

echo "<h3>Checking Rooms Table Structure</h3>";

// Get table structure
$structure_query = "DESCRIBE rooms";
$structure_result = mysqli_query($conn, $structure_query);

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";

while($row = mysqli_fetch_assoc($structure_result)) {
    echo "<tr>";
    echo "<td>" . $row['Field'] . "</td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . $row['Null'] . "</td>";
    echo "<td>" . $row['Key'] . "</td>";
    echo "<td>" . $row['Default'] . "</td>";
    echo "<td>" . $row['Extra'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// Check status column specifically
echo "<h4>Status Column Values:</h4>";
$values_query = "SELECT status, COUNT(*) as count FROM rooms GROUP BY status";
$values_result = mysqli_query($conn, $values_query);

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Status Value</th><th>Count</th></tr>";

while($row = mysqli_fetch_assoc($values_result)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
    echo "<td>" . $row['count'] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>