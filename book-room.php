<?php
include "config/db.php";

// Include PHPMailer classes at the top level
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// REAL EMAIL FUNCTION using PHPMailer
function sendBookingEmail($guest_email, $guest_name, $room_number, $check_in, $check_out, $room_type, $price, $booking_id) {
    
    $mail = new PHPMailer(true);
    
    try {
        // GMAIL SMTP SETTINGS - USE YOUR CREDENTIALS
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'shubhamaranha@gmail.com'; // Your Gmail
        $mail->Password   = 'felf rkmr etpz ethf'; // PASTE YOUR APP PASSWORD HERE
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        // SENDER & RECEIVER
        $mail->setFrom('noreply@grandhotel.com', 'Grand Hotel');
        $mail->addAddress($guest_email, $guest_name);
        $mail->addReplyTo('reservations@grandhotel.com', 'Reservations');
        
        // EMAIL CONTENT
        $mail->isHTML(true);
        $mail->Subject = "🎉 Booking Confirmation #$booking_id - Grand Hotel";
        
        $mail->Body = '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; background: #f5f7fa; margin: 0; padding: 20px; }
                .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 30px; text-align: center; }
                .logo { font-size: 32px; font-weight: bold; margin-bottom: 10px; }
                .content { padding: 40px 30px; }
                .greeting { font-size: 24px; color: #333; margin-bottom: 20px; }
                .booking-details { background: #f8f9ff; border-radius: 10px; padding: 25px; margin: 25px 0; border-left: 5px solid #667eea; }
                .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
                .detail-label { color: #666; font-weight: 600; }
                .detail-value { color: #333; font-weight: bold; }
                .highlight { background: #e3f2fd; color: #1565c0; padding: 10px 15px; border-radius: 8px; font-weight: bold; text-align: center; margin: 20px 0; }
                .contact-info { background: #fff3e0; padding: 20px; border-radius: 10px; margin-top: 30px; }
                .footer { background: #f5f7fa; padding: 25px; text-align: center; color: #666; font-size: 14px; border-top: 1px solid #eee; }
                .button { display: inline-block; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 50px; font-weight: bold; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <div class="logo">🏨 Grand Hotel</div>
                    <h1>Booking Confirmed!</h1>
                    <p>Your luxury stay is reserved</p>
                </div>
                
                <div class="content">
                    <div class="greeting">Dear ' . $guest_name . ',</div>
                    <p>Thank you for choosing Grand Hotel! Your booking has been successfully confirmed.</p>
                    
                    <div class="booking-details">
                        <h3 style="color: #667eea; margin-top: 0;">Booking Details</h3>
                        <div class="detail-row">
                            <span class="detail-label">Booking ID:</span>
                            <span class="detail-value">#' . $booking_id . '</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Room:</span>
                            <span class="detail-value">Room ' . $room_number . ' (' . $room_type . ')</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Check-in:</span>
                            <span class="detail-value">' . date('F j, Y', strtotime($check_in)) . '</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Check-out:</span>
                            <span class="detail-value">' . date('F j, Y', strtotime($check_out)) . '</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Total Price:</span>
                            <span class="detail-value">$' . $price . ' per night</span>
                        </div>
                    </div>
                    
                    <div class="highlight">
                        ✅ Your room is confirmed! Please arrive at the hotel on your check-in date.
                    </div>
                    
                    <div class="contact-info">
                        <h4 style="color: #e65100; margin-top: 0;">Hotel Contact Information</h4>
                        <p><strong>Address:</strong> 123 Luxury Street, Marine Drive, Mumbai</p>
                        <p><strong>Phone:</strong> +91 22 1234 5678</p>
                        <p><strong>Email:</strong> reservations@grandhotel.com</p>
                        <p><strong>Check-in time:</strong> 2:00 PM | <strong>Check-out time:</strong> 12:00 PM</p>
                    </div>
                    
                    <p style="text-align: center;">
                        <a href="http://localhost/Hotel-management-System/booking-confirmation.php?id=' . $booking_id . '" class="button">View Your Booking</a>
                    </p>
                </div>
                
                <div class="footer">
                    <p>This is an automated confirmation email. Please do not reply to this email.</p>
                    <p>© 2024 Grand Hotel. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>';
        
        // Send email
        $mail->send();
        return true;
        
    } catch (Exception $e) {
        // Log error but don't fail the booking
        error_log("Email failed: " . $mail->ErrorInfo);
        return false; // Email failed but booking succeeded
    }
}

// Get room details
$room_id = $_GET['id'] ?? 0;
$room_query = "SELECT * FROM rooms WHERE id='$room_id' AND status='Available'";
$room_result = mysqli_query($conn, $room_query);

if(!$room_result || mysqli_num_rows($room_result) == 0) {
    $room = null;
} else {
    $room = mysqli_fetch_assoc($room_result);
}

// Handle booking form submission
if (isset($_POST['book_now']) && $room) {
    $guest_name = mysqli_real_escape_string($conn, $_POST['guest_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $check_in = mysqli_real_escape_string($conn, $_POST['check_in']);
    $check_out = mysqli_real_escape_string($conn, $_POST['check_out']);
    
    // Validate dates
    if (strtotime($check_out) <= strtotime($check_in)) {
        $error = "Check-out date must be after check-in date.";
    } else {
        // Calculate total price
        $check_in_date = new DateTime($check_in);
        $check_out_date = new DateTime($check_out);
        $nights = $check_in_date->diff($check_out_date)->days;
        $total_price = $room['price'] * $nights;
        
        // INSERT booking with ALL fields
        $query1 = "INSERT INTO bookings (guest_name, guest_email, guest_phone, room_id, check_in, check_out, total_price) 
                  VALUES ('$guest_name', '$email', '$phone', '$room_id', '$check_in', '$check_out', '$total_price')";
        
        // Execute booking insert
        if(mysqli_query($conn, $query1)) {
            // Get the booking ID
            $booking_id = mysqli_insert_id($conn);
            
            // Update room status to 'Booked'
            $query2 = "UPDATE rooms SET status='Booked' WHERE id='$room_id'";
            mysqli_query($conn, $query2);
            
            // Send REAL EMAIL
            $email_sent = sendBookingEmail(
                $email,
                $guest_name,
                $room['room_number'],
                $check_in,
                $check_out,
                $room['room_type'],
                $room['price'],
                $booking_id
            );
            
            // SUCCESS - Redirect to confirmation page
            header("Location: booking-confirmation.php?id=" . $booking_id);
            exit();
            
        } else {
            $error = "Database Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Room | Grand Hotel</title>
    <link rel="stylesheet" href="assets/css/style.css?v=6">
    <style>
        .booking-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            min-height: 80vh;
            align-items: start;
        }
        
        .room-details {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            padding: 40px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        .room-image-large {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 20px;
            margin-bottom: 25px;
        }
        
        .room-title {
            color: white;
            font-size: 32px;
            margin-bottom: 15px;
            font-weight: 800;
        }
        
        .room-price {
            color: #667eea;
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 20px;
        }
        
        .room-description {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            margin-bottom: 25px;
            font-size: 16px;
        }
        
        .booking-form {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            padding: 40px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        .form-title {
            color: white;
            font-size: 28px;
            margin-bottom: 30px;
            font-weight: 700;
            text-align: center;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 30px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 16px;
        }
        
        .back-link:hover {
            color: white;
        }
        
        .email-notice {
            background: rgba(67, 233, 123, 0.1);
            border: 1px solid rgba(67, 233, 123, 0.3);
            color: #d4edda;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
        
        @media (max-width: 968px) {
            .booking-container {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            color: white;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 16px;
        }
        
        .form-group input {
            width: 100%;
            padding: 15px 20px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            color: white;
            font-size: 16px;
            box-sizing: border-box;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            background: rgba(255, 255, 255, 0.15);
        }
        
        .btn {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
            border: none;
            padding: 18px 40px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            display: block;
            width: 100%;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(67, 233, 123, 0.3);
        }
        
        .error {
            background: rgba(220, 53, 69, 0.25);
            color: #f8d7da;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            border-left: 5px solid #dc3545;
        }
        
        .success {
            background: rgba(40, 167, 69, 0.25);
            color: #d4edda;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            border-left: 5px solid #28a745;
        }
    </style>
</head>
<body>
    <div class="booking-container">
        <?php if($room): ?>
        <!-- Room Details -->
        <div class="room-details">
            <a href="index.php" class="back-link">← Back to Available Rooms</a>
            <img src="<?php echo $room['image']; ?>" alt="Room <?php echo $room['room_number']; ?>" class="room-image-large">
            
            <h2 class="room-title">Room <?php echo $room['room_number']; ?> - <?php echo $room['room_type']; ?></h2>
            
            <div class="room-price">$<?php echo $room['price']; ?> per night</div>
            
            <?php if($room['rating'] > 0): ?>
            <div style="color: #FFD700; font-size: 20px; margin-bottom: 15px;">
                <?php
                $fullStars = floor($room['rating']);
                $halfStar = ($room['rating'] - $fullStars) >= 0.5;
                echo str_repeat('★', $fullStars);
                echo $halfStar ? '½' : '';
                ?>
                <span style="color: rgba(255,255,255,0.8); font-size: 16px; margin-left: 10px;">
                    <?php echo $room['rating']; ?>/5 Rating
                </span>
            </div>
            <?php endif; ?>
            
            <?php if(!empty($room['description'])): ?>
                <p class="room-description"><?php echo $room['description']; ?></p>
            <?php endif; ?>
            
            <div style="color: rgba(255,255,255,0.8); padding: 15px; background: rgba(255,255,255,0.1); border-radius: 15px;">
                <strong>Status:</strong> <span style="color: #43e97b;">Available ✓</span>
                <div style="margin-top: 10px; font-size: 14px;">
                    • Confirmation email will be sent<br>
                    • Free cancellation up to 48 hours<br>
                    • 24/7 customer support
                </div>
            </div>
        </div>
        
        <!-- Booking Form -->
        <div class="booking-form">
            <h3 class="form-title">Book This Room</h3>
            
            <div class="email-notice">
                📧 A <strong>REAL confirmation email</strong> will be sent to the email address you provide
            </div>
            
            <?php if(isset($error)): ?>
                <div class="error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Full Name:</label>
                    <input type="text" name="guest_name" required placeholder="John Doe" value="<?php echo isset($_POST['guest_name']) ? $_POST['guest_name'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label>Email Address:</label>
                    <input type="email" name="email" required placeholder="john@example.com" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                    <small style="color: rgba(255,255,255,0.7);">Real confirmation email will be sent here</small>
                </div>
                
                <div class="form-group">
                    <label>Phone Number:</label>
                    <input type="tel" name="phone" required placeholder="+91 9876543210" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label>Check-in Date:</label>
                    <input type="date" name="check_in" required min="<?php echo date('Y-m-d'); ?>" value="<?php echo isset($_POST['check_in']) ? $_POST['check_in'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label>Check-out Date:</label>
                    <input type="date" name="check_out" required min="<?php echo date('Y-m-d'); ?>" value="<?php echo isset($_POST['check_out']) ? $_POST['check_out'] : ''; ?>">
                </div>
                
                <div id="priceSummary" style="background: rgba(255,255,255,0.05); padding: 20px; border-radius: 15px; margin-bottom: 25px;">
                    <h4 style="color: white; margin-bottom: 10px;">Booking Summary</h4>
                    <div style="display: flex; justify-content: space-between; color: rgba(255,255,255,0.9); margin: 5px 0;">
                        <span>Room:</span>
                        <span>Room <?php echo $room['room_number']; ?> (<?php echo $room['room_type']; ?>)</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; color: rgba(255,255,255,0.9); margin: 5px 0;">
                        <span>Price per night:</span>
                        <span>$<?php echo $room['price']; ?></span>
                    </div>
                    <div style="border-top: 1px solid rgba(255,255,255,0.2); margin-top: 10px; padding-top: 10px; color: #667eea; font-weight: bold; display: flex; justify-content: space-between;">
                        <span>Total Estimate:</span>
                        <span id="totalPrice">$<?php echo $room['price']; ?> × 1 night = $<?php echo $room['price']; ?></span>
                    </div>
                </div>
                
                <button type="submit" name="book_now" class="btn">
                    ✅ Confirm Booking & Get REAL Email
                </button>
            </form>
            
            <p style="color: rgba(255,255,255,0.7); text-align: center; margin-top: 20px; font-size: 14px;">
                By booking, you agree to our terms and conditions. Free cancellation within 48 hours.
            </p>
        </div>
        
        <?php else: ?>
        <!-- Room not available -->
        <div style="grid-column: 1 / -1; text-align: center; padding: 60px;">
            <h2 style="color: white; margin-bottom: 20px;">Room Not Available</h2>
            <p style="color: rgba(255,255,255,0.8); margin-bottom: 30px; font-size: 18px;">
                This room is either booked or doesn't exist.
            </p>
            <a href="index.php" class="btn">Browse Available Rooms</a>
        </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Set min dates and calculate price
        const today = new Date().toISOString().split('T')[0];
        document.querySelector('input[name="check_in"]').min = today;
        
        // Calculate nights and price when dates change
        function calculatePrice() {
            const checkIn = document.querySelector('input[name="check_in"]').value;
            const checkOut = document.querySelector('input[name="check_out"]').value;
            const pricePerNight = <?php echo $room['price'] ?? 0; ?>;
            
            if(checkIn && checkOut) {
                const start = new Date(checkIn);
                const end = new Date(checkOut);
                const timeDiff = end.getTime() - start.getTime();
                const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
                
                if(nights > 0) {
                    const total = pricePerNight * nights;
                    document.getElementById('totalPrice').textContent = 
                        `$${pricePerNight} × ${nights} night${nights > 1 ? 's' : ''} = $${total}`;
                } else if(nights < 0) {
                    document.getElementById('totalPrice').textContent = 'Invalid dates';
                }
            }
        }
        
        // Add event listeners
        document.querySelector('input[name="check_in"]').addEventListener('change', function() {
            const checkOutInput = document.querySelector('input[name="check_out"]');
            checkOutInput.min = this.value;
            calculatePrice();
        });
        
        document.querySelector('input[name="check_out"]').addEventListener('change', calculatePrice);
        
        // Initial calculation
        document.addEventListener('DOMContentLoaded', function() {
            if(document.querySelector('input[name="check_in"]').value && 
               document.querySelector('input[name="check_out"]').value) {
                calculatePrice();
            }
        });
    </script>
</body>
</html>