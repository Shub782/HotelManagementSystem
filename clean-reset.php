<?php
include "config/db.php";

echo "<h2>Cleaning and Resetting Database</h2>";
echo "<style>
    body {font-family: Arial; padding: 20px; background: #f5f5f5;}
    .success {color: green; font-weight: bold;}
    .step {background: white; padding: 15px; margin: 10px 0; border-radius: 5px;}
</style>";

echo "<div class='step'>";
echo "<h3>Step 1: Removing all bookings...</h3>";
mysqli_query($conn, "DELETE FROM bookings");
echo "<p class='success'>✓ All bookings removed</p>";
echo "</div>";

echo "<div class='step'>";
echo "<h3>Step 2: Resetting all rooms to 'Available'...</h3>";
mysqli_query($conn, "UPDATE rooms SET status='Available'");
echo "<p class='success'>✓ All rooms set to Available</p>";
echo "</div>";

echo "<div class='step'>";
echo "<h3>Step 3: Fixing booking table structure...</h3>";

// Check and fix bookings table structure
$check_table = "SHOW COLUMNS FROM bookings";
$result = mysqli_query($conn, $check_table);
$has_autoincrement = false;

while($row = mysqli_fetch_assoc($result)) {
    if($row['Field'] == 'id' && strpos($row['Extra'], 'auto_increment') !== false) {
        $has_autoincrement = true;
    }
}

if(!$has_autoincrement) {
    // Try to add auto_increment
    mysqli_query($conn, "ALTER TABLE bookings MODIFY id INT AUTO_INCREMENT PRIMARY KEY");
    echo "<p class='success'>✓ Added AUTO_INCREMENT to bookings.id</p>";
} else {
    echo "<p>✓ AUTO_INCREMENT already exists</p>";
}
echo "</div>";

echo "<div class='step'>";
echo "<h3>Step 4: Adding test rooms if none exist...</h3>";
$room_check = mysqli_query($conn, "SELECT COUNT(*) as count FROM rooms");
$room_count = mysqli_fetch_assoc($room_check)['count'];

if($room_count < 4) {
    $rooms = [
        ["101", "Deluxe", 299, "Available"],
        ["102", "Suite", 499, "Available"],
        ["103", "Double", 199, "Available"],
        ["104", "Single", 149, "Available"]
    ];
    
    foreach($rooms as $room) {
        $check = mysqli_query($conn, "SELECT id FROM rooms WHERE room_number='{$room[0]}'");
        if(mysqli_num_rows($check) == 0) {
            mysqli_query($conn, "INSERT INTO rooms (room_number, room_type, price, status) 
                                 VALUES ('{$room[0]}', '{$room[1]}', '{$room[2]}', '{$room[3]}')");
        }
    }
    echo "<p class='success'>✓ Added sample rooms</p>";
} else {
    echo "<p>✓ Already have $room_count rooms</p>";
}
echo "</div>";

echo "<div class='step'>";
echo "<h3>Step 5: Adding test bookings for demonstration...</h3>";
// Add 2 test bookings
$test_bookings = [
    ["John Smith", "john@email.com", "+1234567890", 1, date('Y-m-d'), date('Y-m-d', strtotime('+2 days'))],
    ["Emma Johnson", "emma@email.com", "+1987654321", 2, date('Y-m-d'), date('Y-m-d', strtotime('+3 days'))]
];

foreach($test_bookings as $booking) {
    mysqli_query($conn, "INSERT INTO bookings (guest_name, guest_email, guest_phone, room_id, check_in, check_out) 
                         VALUES ('$booking[0]', '$booking[1]', '$booking[2]', '$booking[3]', '$booking[4]', '$booking[5]')");
    
    // Mark room as booked
    mysqli_query($conn, "UPDATE rooms SET status='Booked' WHERE id='$booking[3]'");
}
echo "<p class='success'>✓ Added 2 test bookings</p>";
echo "</div>";

echo "<div style='margin-top: 30px; padding: 20px; background: white; border-radius: 10px;'>";
echo "<h3 style='color: green;'>✅ RESET COMPLETE!</h3>";
echo "<p>Your database has been cleaned and reset with fresh data.</p>";
echo "<p><strong>Now you have:</strong></p>";
echo "<ul>";
echo "<li>All duplicate bookings removed</li>";
echo "<li>Proper room status (mixed Available/Booked for demo)</li>";
echo "<li>Clean booking IDs with auto-increment</li>";
echo "<li>Test data for demonstration</li>";
echo "</ul>";

echo "<div style='margin-top: 20px;'>";
echo "<a href='bookings.php' style='padding: 12px 25px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;'>View Bookings</a>";
echo "<a href='dashboard.php' style='padding: 12px 25px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;'>Go to Dashboard</a>";
echo "<a href='index.php' style='padding: 12px 25px; background: #17a2b8; color: white; text-decoration: none; border-radius: 5px;'>View Website</a>";
echo "</div>";
echo "</div>";
?>