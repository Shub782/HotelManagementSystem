<?php
include "config/db.php";

// Fetch available rooms
$rooms_query = "SELECT * FROM rooms WHERE status='Available' ORDER BY rating DESC";
$rooms_result = mysqli_query($conn, $rooms_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Grand Hotel | Luxury Accommodation</title>
    <link rel="stylesheet" href="assets/css/style.css?v=6">
    <style>
        /* ===== HERO SECTION ===== */
        .hero {
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0 20px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.5)), 
                        url('https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1920&h=1080&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 900px;
            animation: fadeInUp 1.2s ease-out;
        }
        
        .hero h1 {
            font-size: 68px;
            font-weight: 800;
            color: white;
            margin-bottom: 25px;
            text-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            line-height: 1.2;
            background: linear-gradient(135deg, #fff 0%, #667eea 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero p {
            font-size: 24px;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 40px;
            line-height: 1.6;
            font-weight: 300;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .cta-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 22px 55px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 60px;
            font-size: 22px;
            font-weight: 700;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 20px 50px rgba(102, 126, 234, 0.5);
            letter-spacing: 1.5px;
            position: relative;
            overflow: hidden;
        }
        
        .cta-button:hover {
            transform: translateY(-10px) scale(1.05);
            box-shadow: 0 30px 70px rgba(102, 126, 234, 0.7);
        }
        
        /* ===== NAVIGATION ===== */
        .main-nav {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            padding: 30px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
        }
        
        .logo {
            font-size: 32px;
            font-weight: 800;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .nav-links {
            display: flex;
            gap: 25px;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 600;
            font-size: 17px;
            padding: 12px 25px;
            border-radius: 30px;
            transition: all 0.3s;
        }
        
        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-3px);
        }
        
        /* ===== ROOMS SECTION ===== */
        .rooms-section {
            padding: 100px 20px;
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
        }
        
        .section-title {
            text-align: center;
            color: white;
            font-size: 52px;
            font-weight: 800;
            margin-bottom: 70px;
            text-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
            position: relative;
            padding-bottom: 20px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 150px;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }
        
        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 40px;
        }
        
        .room-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 30px;
            overflow: hidden;
            transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.2);
        }
        
        .room-card:hover {
            transform: translateY(-20px) scale(1.02);
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.15);
        }
        
        .room-image {
            width: 100%;
            height: 280px;
            object-fit: cover;
            transition: transform 0.8s;
        }
        
        .room-card:hover .room-image {
            transform: scale(1.1);
        }
        
        .room-content {
            padding: 35px;
        }
        
        .room-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .room-title {
            font-size: 28px;
            font-weight: 800;
            color: white;
            margin: 0;
        }
        
        .room-price {
            font-size: 34px;
            font-weight: 900;
            color: #667eea;
            background: rgba(255, 255, 255, 0.1);
            padding: 12px 22px;
            border-radius: 20px;
        }
        
        .room-description {
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 25px;
            line-height: 1.6;
            font-size: 16px;
            min-height: 48px;
        }
        
        .room-features {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .room-type-badge {
            padding: 10px 22px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 1px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: white;
        }
        
        .single { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .double { background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); }
        .deluxe { background: linear-gradient(135deg, #fad0c4 0%, #ffd1ff 100%); color: #333; }
        .suite { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #333; }
        
        .rating {
            color: #FFD700;
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .room-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 30px;
        }
        
        .detail-btn {
            padding: 18px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            border-radius: 15px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.4s;
            text-align: center;
            backdrop-filter: blur(10px);
        }
        
        .detail-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        
        .book-btn {
            padding: 18px;
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
            text-decoration: none;
            border-radius: 15px;
            font-weight: 700;
            font-size: 16px;
            transition: all 0.4s;
            text-align: center;
            box-shadow: 0 10px 25px rgba(67, 233, 123, 0.3);
        }
        
        .book-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(67, 233, 123, 0.5);
        }
        
        /* ===== FACILITIES SECTION WITH PHOTOS ===== */
        .facilities-section {
            padding: 100px 20px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .facilities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .facility-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            overflow: hidden;
            transition: all 0.5s;
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
        }
        
        .facility-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
        }
        
        .facility-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: transform 0.8s;
        }
        
        .facility-card:hover .facility-image {
            transform: scale(1.1);
        }
        
        .facility-content {
            padding: 25px;
        }
        
        .facility-content h3 {
            color: white;
            font-size: 24px;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .facility-content p {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            font-size: 15px;
        }
        
        /* ===== TESTIMONIALS SECTION ===== */
        .testimonials-section {
            padding: 100px 20px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .testimonial-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            padding: 35px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: transform 0.4s;
        }
        
        .testimonial-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.15);
        }
        
        .testimonial-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .testimonial-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #667eea;
        }
        
        .testimonial-info h4 {
            color: white;
            margin: 0 0 5px 0;
            font-size: 20px;
        }
        
        .testimonial-info p {
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
            font-size: 14px;
        }
        
        .testimonial-rating {
            color: #FFD700;
            font-size: 18px;
            margin-bottom: 15px;
        }
        
        .testimonial-text {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            font-style: italic;
        }
        
        /* ===== FOOTER ===== */
        .footer {
            padding: 80px 20px 40px 20px;
            background: rgba(0, 0, 0, 0.3);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 100px;
        }
        
        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 50px;
        }
        
        .footer-column h3 {
            color: white;
            font-size: 22px;
            margin-bottom: 25px;
            font-weight: 700;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 15px;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .footer-links a:hover {
            color: white;
            transform: translateX(5px);
        }
        
        .footer-contact p {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .copyright {
            text-align: center;
            color: rgba(255, 255, 255, 0.5);
            padding-top: 40px;
            margin-top: 40px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 14px;
        }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .main-nav {
                padding: 20px;
                flex-direction: column;
                gap: 20px;
            }
            
            .hero h1 {
                font-size: 36px;
            }
            
            .hero p {
                font-size: 18px;
            }
            
            .section-title {
                font-size: 40px;
            }
            
            .rooms-grid, .facilities-grid, .testimonial-grid {
                grid-template-columns: 1fr;
            }
            
            .room-actions {
                grid-template-columns: 1fr;
            }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(60px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="main-nav">
        <a href="index.php" class="logo">
            <span style="font-size: 40px;">🏨</span> Grand Hotel
        </a>
        <div class="nav-links">
            <a href="index.php#rooms" class="nav-link">Rooms</a>
            <a href="index.php#facilities" class="nav-link">Facilities</a>
            <a href="contact.php" class="nav-link">Contact</a>
            <a href="login.php" class="nav-link">Admin</a>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Experience Ultimate Luxury & Comfort</h1>
            <p>Discover premium rooms with breathtaking views, world-class amenities, and exceptional service at Grand Hotel. Your perfect getaway starts here.</p>
            <a href="#rooms" class="cta-button">Explore Our Rooms →</a>
        </div>
    </section>
    
    <!-- Rooms Section -->
    <section class="rooms-section" id="rooms">
        <h2 class="section-title">Our Premium Rooms</h2>
        
        <div class="rooms-grid">
            <?php if($rooms_result && mysqli_num_rows($rooms_result) > 0): ?>
                <?php while($room = mysqli_fetch_assoc($rooms_result)): ?>
                <div class="room-card">
                    <img src="<?php echo $room['image'] ?: 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800&h=600&fit=crop'; ?>" 
                         alt="Room <?php echo $room['room_number']; ?>" 
                         class="room-image">
                    
                    <div class="room-content">
                        <div class="room-header">
                            <h3 class="room-title">Room <?php echo $room['room_number']; ?></h3>
                            <div class="room-price">$<?php echo $room['price']; ?></div>
                        </div>
                        
                        <?php if(!empty($room['description'])): ?>
                            <p class="room-description"><?php echo $room['description']; ?></p>
                        <?php endif; ?>
                        
                        <div class="room-features">
                            <span class="room-type-badge <?php echo strtolower($room['room_type']); ?>">
                                <?php echo $room['room_type']; ?> Room
                            </span>
                            
                            <?php if($room['rating'] > 0): ?>
                            <div class="rating">
                                <span style="font-size: 24px;">★</span>
                                <span><?php echo $room['rating']; ?>/5</span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="room-actions">
                            <a href="room-details.php?id=<?php echo $room['id']; ?>" class="detail-btn">
                                View Details
                            </a>
                            <a href="book-room.php?id=<?php echo $room['id']; ?>" class="book-btn">
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 80px 40px; background: rgba(255,255,255,0.08); border-radius: 30px;">
                    <h3 style="color: white; margin-bottom: 20px; font-size: 32px;">No Rooms Available</h3>
                    <p style="color: rgba(255,255,255,0.8); margin-bottom: 30px; font-size: 18px;">
                        All our luxury rooms are currently booked. Please check back later or contact us for special arrangements.
                    </p>
                    <a href="contact.php" class="cta-button" style="padding: 18px 40px; font-size: 18px;">
                        Contact Hotel
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Facilities Section WITH PHOTOS -->
    <section class="facilities-section" id="facilities">
        <h2 class="section-title">Hotel Facilities</h2>
        <div class="facilities-grid">
            <!-- Pool -->
            <div class="facility-card">
                <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop" 
                     alt="Infinity Pool" class="facility-image">
                <div class="facility-content">
                    <h3>Infinity Pool</h3>
                    <p>Temperature-controlled pool with stunning city views and poolside service.</p>
                </div>
            </div>
            
            <!-- Gym -->
            <div class="facility-card">
                <img src="https://images.unsplash.com/photo-1540497077202-7c8a3999166f?w=800&h=600&fit=crop" 
                     alt="Fitness Center" class="facility-image">
                <div class="facility-content">
                    <h3>Fitness Center</h3>
                    <p>24/7 gym with modern equipment, personal trainers, and yoga classes.</p>
                </div>
            </div>
            
            <!-- Restaurant -->
            <div class="facility-card">
                <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&h=600&fit=crop" 
                     alt="Fine Dining" class="facility-image">
                <div class="facility-content">
                    <h3>Fine Dining</h3>
                    <p>5-star restaurant with international cuisine and live music evenings.</p>
                </div>
            </div>
            
            <!-- Spa -->
            <div class="facility-card">
                <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?w=800&h=600&fit=crop" 
                     alt="Spa & Wellness" class="facility-image">
                <div class="facility-content">
                    <h3>Spa & Wellness</h3>
                    <p>Full-service spa with massage, beauty treatments, and steam rooms.</p>
                </div>
            </div>
            
            <!-- Parking -->
            <div class="facility-card">
                <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800&h=600&fit=crop" 
                     alt="Valet Parking" class="facility-image">
                <div class="facility-content">
                    <h3>Valet Parking</h3>
                    <p>Secure underground parking with valet service available 24/7.</p>
                </div>
            </div>
            
            <!-- Kids Club -->
            <div class="facility-card">
                <img src="https://images.unsplash.com/photo-1511988617509-a57c8a288659?w=800&h=600&fit=crop" 
                     alt="Kids Club" class="facility-image">
                <div class="facility-content">
                    <h3>Kids Club</h3>
                    <p>Supervised activities, games room, and entertainment for children.</p>
                </div>
            </div>
            
            <!-- Business Center -->
            <div class="facility-card">
                <img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=800&h=600&fit=crop" 
                     alt="Business Center" class="facility-image">
                <div class="facility-content">
                    <h3>Business Center</h3>
                    <p>Meeting rooms, conference facilities, and business services.</p>
                </div>
            </div>
            
            <!-- Pet Friendly -->
            <div class="facility-card">
                <img src="https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=800&h=600&fit=crop" 
                     alt="Pet Friendly" class="facility-image">
                <div class="facility-content">
                    <h3>Pet Friendly</h3>
                    <p>Special accommodations, pet beds, and walking services for your furry friends.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <h2 class="section-title">Guest Testimonials</h2>
        <div class="testimonial-grid">
            <div class="testimonial-card">
                <div class="testimonial-header">
                    <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=100&h=100&fit=crop" alt="Guest" class="testimonial-avatar">
                    <div class="testimonial-info">
                        <h4>Sarah Johnson</h4>
                        <p>Business Traveler</p>
                    </div>
                </div>
                <div class="testimonial-rating">★★★★★</div>
                <p class="testimonial-text">"The Deluxe Suite was absolutely stunning! The sea view from the balcony took my breath away. Service was impeccable."</p>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-header">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop" alt="Guest" class="testimonial-avatar">
                    <div class="testimonial-info">
                        <h4>Michael Chen</h4>
                        <p>Honeymoon Couple</p>
                    </div>
                </div>
                <div class="testimonial-rating">★★★★★</div>
                <p class="testimonial-text">"Perfect romantic getaway! The staff arranged everything for our anniversary. Room service was quick and delicious."</p>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-header">
                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop" alt="Guest" class="testimonial-avatar">
                    <div class="testimonial-info">
                        <h4>Priya Sharma</h4>
                        <p>Family Vacation</p>
                    </div>
                </div>
                <div class="testimonial-rating">★★★★☆</div>
                <p class="testimonial-text">"Great for families! Kids loved the pool and the staff was incredibly accommodating. Will definitely return!"</p>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-column">
                <h3>Grand Hotel</h3>
                <p style="color: rgba(255,255,255,0.7); line-height: 1.6;">
                    Experience luxury like never before. Book your stay at our premium hotel for an unforgettable experience.
                </p>
            </div>
            
            <div class="footer-column">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="index.php#rooms">Rooms & Suites</a></li>
                    <li><a href="index.php#facilities">Facilities</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="login.php">Admin Login</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3>Contact Info</h3>
                <div class="footer-contact">
                    <p>📍 123 Luxury Street, Marine Drive, Mumbai</p>
                    <p>📞 +91 22 1234 5678</p>
                    <p>✉️ info@grandhotel.com</p>
                    <p>🕐 24/7 Reception</p>
                </div>
            </div>
        </div>
        
        <div class="copyright">
            © 2024 Grand Hotel. All rights reserved. | Hotel Management System Project
        </div>
    </footer>
    
    <script>
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>