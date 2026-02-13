<?php
include "config/db.php";

// Add status column if it doesn't exist
mysqli_query($conn, "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS status VARCHAR(20) DEFAULT 'Confirmed'");

// Add total_price if it doesn't exist  
mysqli_query($conn, "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS total_price DECIMAL(10,2)");

// Add created_at if it doesn't exist
mysqli_query($conn, "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");

echo "Fixed! <a href='bookings.php'>Go Back</a>";
?>