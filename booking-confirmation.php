<?php
session_start();
include "config/db.php";

// Get booking ID from URL
$booking_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Basic validation
if ($booking_id <= 0) {
    die("<h2 style='text-align:center; margin-top:50px;'>❌ Invalid booking reference</h2>");
}

// Fetch booking details with error handling
$query = "SELECT b.*, r.room_number, r.room_type, r.price 
          FROM bookings b
          LEFT JOIN rooms r ON b.room_id = r.id 
          WHERE b.id = '$booking_id'";
          
$result = mysqli_query($conn, $query);

// Check if query failed
if (!$result) {
    die("<h2 style='text-align:center; margin-top:50px;'>Database Error: " . mysqli_error($conn) . "</h2>");
}

// Check if booking exists
if (mysqli_num_rows($result) == 0) {
    die("<h2 style='text-align:center; margin-top:50px;'>Booking not found</h2>");
}

$booking = mysqli_fetch_assoc($result);

// Calculate nights
$check_in = new DateTime($booking['check_in']);
$check_out = new DateTime($booking['check_out']);
$nights = $check_in->diff($check_out)->days;
$total_price = $booking['price'] * $nights;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation | Grand Hotel</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            margin: 0;
        }
        
        .confirmation-card {
            background: white;
            max-width: 700px;
            margin: 50px auto;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .success-icon {
            font-size: 80px;
            color: #43e97b;
            margin-bottom: 20px;
        }
        
        .booking-id {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 12px 25px;
            border-radius: 50px;
            display: inline-block;
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
        }
        
        .details-table {
            width: 100%;
            margin: 30px 0;
            border-collapse: collapse;
        }
        
        .details-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        
        .details-table tr:last-child td {
            border-bottom: none;
        }
        
        .highlight {
            background: #f8f9ff;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        
        .btn-home {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            font-weight: 600;
        }
        
        .error-box {
            background: #fff5f5;
            border: 1px solid #feb2b2;
            color: #c53030;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="confirmation-card">
        <div class="success-icon">✓</div>
        <h1>Booking Confirmed!</h1>
        <p>Thank you for choosing Grand Hotel. Your booking has been confirmed.</p>
        
        <div class="booking-id">Booking ID: #<?php echo $booking['id']; ?></div>
        
        <div class="highlight">
            <strong>Guest Information:</strong><br>
            <?php echo $booking['guest_name']; ?><br>
            <?php echo $booking['guest_email']; ?><br>
            <?php echo $booking['guest_phone']; ?>
        </div>
        
        <table class="details-table">
            <tr>
                <td><strong>Room:</strong></td>
                <td>
                    <?php 
                    if($booking['room_number']) {
                        echo "Room " . $booking['room_number'] . " (" . $booking['room_type'] . ")";
                    } else {
                        echo "Room ID: " . $booking['room_id'];
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>Check-in:</strong></td>
                <td><?php echo date('F j, Y', strtotime($booking['check_in'])); ?></td>
            </tr>
            <tr>
                <td><strong>Check-out:</strong></td>
                <td><?php echo date('F j, Y', strtotime($booking['check_out'])); ?></td>
            </tr>
            <tr>
                <td><strong>Total Nights:</strong></td>
                <td><?php echo $nights . ' night' . ($nights > 1 ? 's' : ''); ?></td>
            </tr>
            <tr>
                <td><strong>Price per Night:</strong></td>
                <td>$<?php echo $booking['price']; ?></td>
            </tr>
            <tr>
                <td><strong>Total Price:</strong></td>
                <td style="font-weight: bold; color: #667eea;">$<?php echo $total_price; ?></td>
            </tr>
        </table>
        
        <div style="background: #f0fff4; padding: 20px; border-radius: 10px; margin: 25px 0; border-left: 4px solid #38a169;">
            <strong>📧 Confirmation Email Sent</strong><br>
            A confirmation has been sent to <strong><?php echo $booking['guest_email']; ?></strong>
        </div>
        
        <div style="margin-top: 30px;">
            <a href="index.php" class="btn-home">Return to Homepage</a>
            <a href="dashboard.php" class="btn-home" style="background: #718096; margin-left: 10px;">Admin Dashboard</a>
        </div>
        
        <p style="margin-top: 30px; color: #666; font-size: 14px;">
            Need help? Contact us at +91 22 1234 5678 or email reservations@grandhotel.com
        </p>
    </div>
    
    <!-- Debug info (remove in production) -->
    <?php if(false): /* Set to true for debugging */ ?>
    <div style="background: white; padding: 20px; margin: 20px auto; max-width: 700px; border-radius: 10px;">
        <h3>Debug Info:</h3>
        <pre><?php print_r($booking); ?></pre>
        <p>Booking ID from URL: <?php echo $_GET['id'] ?? 'none'; ?></p>
        <p>Query: <?php echo $query; ?></p>
    </div>
    <?php endif; ?>
</body>
</html>