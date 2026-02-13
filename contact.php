<?php
include "config/db.php";

// Handle contact form submission
if (isset($_POST['send_message'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    // In real app, you would save to database or send email
    $success = "Thank you, $name! Your message has been sent. We'll contact you within 24 hours.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us | Grand Hotel</title>
    <link rel="stylesheet" href="assets/css/style.css?v=6">
    <style>
        .contact-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .contact-header {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .contact-header h1 {
            color: white;
            font-size: 42px;
            margin-bottom: 15px;
            font-weight: 800;
        }
        
        .contact-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 18px;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }
        
        .contact-info {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 25px;
            padding: 40px;
            backdrop-filter: blur(10px);
        }
        
        .contact-form {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 25px;
            padding: 40px;
            backdrop-filter: blur(10px);
        }
        
        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-icon {
            font-size: 32px;
            color: #667eea;
        }
        
        .info-content h3 {
            color: white;
            margin-bottom: 5px;
            font-size: 20px;
        }
        
        .info-content p {
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
            line-height: 1.5;
        }
        
        .form-title {
            color: white;
            font-size: 28px;
            margin-bottom: 30px;
            font-weight: 700;
        }
        
        .back-nav {
            margin-bottom: 30px;
        }
        
        .back-nav a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 16px;
        }
        
        @media (max-width: 900px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="contact-container">
        <!-- Navigation -->
        <div class="back-nav">
            <a href="index.php">← Back to Home</a>
        </div>
        
        <!-- Header -->
        <div class="contact-header">
            <h1>Contact Grand Hotel</h1>
            <p>We're here to help! Reach out to us for any inquiries, bookings, or support.</p>
        </div>
        
        <!-- Contact Grid -->
        <div class="contact-grid">
            <!-- Contact Information -->
            <div class="contact-info">
                <div class="info-item">
                    <div class="info-icon">📍</div>
                    <div class="info-content">
                        <h3>Our Location</h3>
                        <p>
                            Grand Hotel<br>
                            123 Luxury Street, Marine Drive<br>
                            Mumbai, Maharashtra 400001<br>
                            India
                        </p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">📞</div>
                    <div class="info-content">
                        <h3>Phone Numbers</h3>
                        <p>
                            Reservations: +91 22 1234 5678<br>
                            Front Desk: +91 22 1234 5679<br>
                            Emergency: +91 98765 43210
                        </p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">✉️</div>
                    <div class="info-content">
                        <h3>Email Address</h3>
                        <p>
                            reservations@grandhotel.com<br>
                            support@grandhotel.com<br>
                            info@grandhotel.com
                        </p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">⏰</div>
                    <div class="info-content">
                        <h3>Working Hours</h3>
                        <p>
                            Front Desk: 24/7<br>
                            Reservations: 8 AM - 10 PM<br>
                            Restaurant: 7 AM - 11 PM<br>
                            Room Service: 24/7
                        </p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">👥</div>
                    <div class="info-content">
                        <h3>Staff & Support</h3>
                        <p>
                            • 50+ Room Attendants<br>
                            • 24/7 Security Staff<br>
                            • Multilingual Concierge<br>
                            • Quick Response Team
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="contact-form">
                <h3 class="form-title">Send us a Message</h3>
                
                <?php if(isset($success)): ?>
                    <div class="success" style="background: rgba(40, 167, 69, 0.25); color: #d4edda; padding: 15px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #28a745;">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label>Your Name:</label>
                        <input type="text" name="name" required placeholder="John Doe">
                    </div>
                    
                    <div class="form-group">
                        <label>Email Address:</label>
                        <input type="email" name="email" required placeholder="john@example.com">
                    </div>
                    
                    <div class="form-group">
                        <label>Phone Number:</label>
                        <input type="tel" name="phone" required placeholder="+91 9876543210">
                    </div>
                    
                    <div class="form-group">
                        <label>Subject:</label>
                        <select name="subject" required>
                            <option value="">Select a subject</option>
                            <option value="Booking Inquiry">Booking Inquiry</option>
                            <option value="Room Availability">Room Availability</option>
                            <option value="Facilities Info">Facilities Information</option>
                            <option value="Special Requests">Special Requests</option>
                            <option value="Complaint">Complaint</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Your Message:</label>
                        <textarea name="message" rows="5" required placeholder="Type your message here..."></textarea>
                    </div>
                    
                    <button type="submit" name="send_message" class="btn" style="width: 100%; padding: 18px; font-size: 17px;">
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>