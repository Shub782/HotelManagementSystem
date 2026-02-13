<?php
include "config/db.php";

$rooms = [
    ['103', 'Suite', 599, 5.0],
    ['104', 'Deluxe', 399, 4.8],
    ['105', 'Double', 249, 4.5],
    ['106', 'Single', 169, 4.2],
    ['201', 'Suite', 699, 5.0],
    ['202', 'Deluxe', 449, 4.9]
];

foreach($rooms as $room) {
    $query = "INSERT INTO rooms (room_number, room_type, price, rating, status) 
              VALUES ('$room[0]', '$room[1]', '$room[2]', '$room[3]', 'Available')";
    mysqli_query($conn, $query);
}

echo "Added 6 more rooms! <a href='index.php'>View Homepage</a>";
?>