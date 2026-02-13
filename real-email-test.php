<?php
// Download PHPMailer quickly: https://github.com/PHPMailer/PHPMailer/archive/refs/heads/master.zip
// Extract and copy 'src' folder to your project as 'PHPMailer'

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Server settings - USE YOUR CREDENTIALS
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'shubhamaranha@gmail.com'; // YOUR GMAIL
    $mail->Password   = 'felf rkmr etpz ethf'; // THE PASSWORD YOU GOT
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    
    // Recipients
    $mail->setFrom('noreply@grandhotel.com', 'Grand Hotel');
    $mail->addAddress('shubhamaranha@gmail.com', 'You'); // SEND TO YOURSELF
    
    // Content
    $mail->isHTML(true);
    $mail->Subject = '🎉 REAL EMAIL TEST - Hotel System Working!';
    $mail->Body    = '<h1>✅ SUCCESS!</h1><p>Your hotel system can send <strong>REAL EMAILS</strong> now!</p><p>Date: ' . date('Y-m-d H:i:s') . '</p>';
    $mail->AltBody = 'SUCCESS! Your hotel system can send REAL EMAILS now!';
    
    $mail->send();
    echo '<h2 style="color:green;">✅ REAL EMAIL SENT!</h2>';
    echo '<p>Check your Gmail inbox <strong>RIGHT NOW</strong> (including Spam folder)</p>';
    echo '<p>Email sent from: noreply@grandhotel.com</p>';
    echo '<p>Email sent to: shubhamaranha@gmail.com</p>';
    echo '<p>Subject: 🎉 REAL EMAIL TEST - Hotel System Working!</p>';
    
} catch (Exception $e) {
    echo '<h2 style="color:red;">❌ EMAIL FAILED</h2>';
    echo '<p>Error: ' . $e->getMessage() . '</p>';
    echo '<p>Check:</p>';
    echo '<ol>';
    echo '<li>PHPMailer folder exists</li>';
    echo '<li>App password is correct (16 characters)</li>';
    echo '<li>2-Step Verification is ON</li>';
    echo '<li>Gmail: shubhamaranha@gmail.com</li>';
    echo '</ol>';
}
?>