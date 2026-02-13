<?php
include "config/db.php";

$room_id = $_GET['id'] ?? 0;
$room_query = "SELECT * FROM rooms WHERE id='$room_id'";
$room_result = mysqli_query($conn, $room_query);
$room = mysqli_fetch_assoc($room_result);

// COMPLETE IMAGE SET FOR EACH ROOM TYPE
$image_sets = [
    'Single' => [
        'bedroom' => [
            'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?w=800&h=600&fit=crop'
        ],
        'bathroom' => [
            'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1552321554-5fefe8c9ef14?w=800&h=600&fit=crop'
        ],
        'view' => [
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&h=600&fit=crop'
        ]
    ],
    'Double' => [
        'bedroom' => [
            'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=800&h=600&fit=crop'
        ],
        'bathroom' => [
            'https://images.unsplash.com/photo-1600566752355-35792bedcfea?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1552321554-5fefe8f7b7b2?w=800&h=600&fit=crop'
        ],
        'sitting' => [
            'https://images.unsplash.com/photo-1615873968403-89e068629265?w=800&h=600&fit=crop'
        ],
        'balcony' => [
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&h=600&fit=crop'
        ]
    ],
    'Deluxe' => [
        'bedroom' => [
            'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&h=600&fit=crop'
        ],
        'bathroom' => [
            'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1600566752355-35792bedcfea?w=800&h=600&fit=crop'
        ],
        'living' => [
            'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=800&h=600&fit=crop'
        ],
        'dining' => [
            'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=800&h=600&fit=crop'
        ],
        'balcony' => [
            'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop'
        ]
    ],
    'Suite' => [
        'bedroom' => [
            'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&h=600&fit=crop'
        ],
        'bathroom' => [
            'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1552321554-5fefe8c9ef14?w=800&h=600&fit=crop'
        ],
        'living' => [
            'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=800&h=600&fit=crop'
        ],
        'kitchen' => [
            'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=800&h=600&fit=crop'
        ],
        'dining' => [
            'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&h=600&fit=crop'
        ],
        'balcony' => [
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&h=600&fit=crop'
        ],
        'jacuzzi' => [
            'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=800&h=600&fit=crop'
        ]
    ]
];

// Get images for this room type
$room_type = $room['room_type'] ?? 'Single';
$room_images = $image_sets[$room_type] ?? $image_sets['Single'];

// Facilities
$default_facilities = ['Free WiFi', 'Air Conditioning', 'LED TV', 'Room Service', 'Mini Bar', 'Safe'];
$facilities = !empty($room['facilities']) ? explode(',', $room['facilities']) : $default_facilities;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room <?php echo $room['room_number'] ?? ''; ?> Details | Grand Hotel</title>
    <link rel="stylesheet" href="assets/css/style.css?v=6">
    <style>
        .details-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        
        .back-nav {
            margin-bottom: 30px;
        }
        
        .back-nav a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 16px;
            padding: 10px 20px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            display: inline-block;
        }
        
        .room-header {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 25px;
            padding: 40px;
            margin-bottom: 40px;
            backdrop-filter: blur(10px);
        }
        
        .room-title {
            color: white;
            font-size: 42px;
            margin-bottom: 10px;
        }
        
        .room-price {
            color: #667eea;
            font-size: 48px;
            font-weight: 800;
            margin: 20px 0;
        }
        
        /* Image Gallery Sections */
        .gallery-section {
            margin-bottom: 50px;
        }
        
        .section-title {
            color: white;
            font-size: 32px;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .section-title span {
            font-size: 28px;
        }
        
        .image-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        
        .gallery-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 15px;
            transition: transform 0.3s;
        }
        
        .gallery-image:hover {
            transform: scale(1.05);
        }
        
        /* Room Info Cards */
        .info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }
        
        .info-card {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            padding: 30px;
            backdrop-filter: blur(10px);
        }
        
        .info-card h3 {
            color: white;
            font-size: 24px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .facilities-list {
            list-style: none;
            padding: 0;
        }
        
        .facilities-list li {
            color: rgba(255, 255, 255, 0.9);
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .facilities-list li:before {
            content: '✓';
            color: #43e97b;
            font-weight: bold;
            font-size: 18px;
        }
        
        /* Booking CTA */
        .booking-cta {
            text-align: center;
            padding: 50px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 25px;
            margin-top: 50px;
            backdrop-filter: blur(10px);
        }
        
        @media (max-width: 768px) {
            .image-gallery {
                grid-template-columns: 1fr;
            }
            
            .room-title {
                font-size: 32px;
            }
            
            .room-price {
                font-size: 36px;
            }
        }
    </style>
</head>
<body>
    <div class="details-container">
        <!-- Navigation -->
        <div class="back-nav">
            <a href="index.php">← Back to All Rooms</a>
        </div>
        
        <?php if($room): ?>
        
        <!-- Room Header -->
        <div class="room-header">
            <h1 class="room-title">Room <?php echo $room['room_number']; ?> - <?php echo $room['room_type']; ?> Suite</h1>
            
            <div style="color: rgba(255,255,255,0.8); font-size: 18px; margin-bottom: 15px; display: flex; gap: 20px;">
                <?php if($room['rating'] > 0): ?>
                <span style="color: #FFD700;">★ <?php echo $room['rating']; ?>/5 Rating</span>
                <?php endif; ?>
                <span style="color: <?php echo $room['status'] == 'Available' ? '#43e97b' : '#fa709a'; ?>">
                    ● <?php echo $room['status']; ?>
                </span>
                <span>🛏️ <?php echo $room['room_type'] == 'Single' ? '1 Person' : ($room['room_type'] == 'Double' ? '2 Persons' : '3+ Persons'); ?></span>
            </div>
            
            <p style="color: rgba(255,255,255,0.9); font-size: 18px; line-height: 1.6; margin-bottom: 25px;">
                <?php echo $room['description'] ?: 'Experience luxury with premium amenities and breathtaking views.'; ?>
            </p>
            
            <div class="room-price">$<?php echo $room['price']; ?> / night</div>
        </div>
        
        <!-- BEDROOM IMAGES -->
        <div class="gallery-section">
            <h2 class="section-title"><span>🛏️</span> Bedroom</h2>
            <div class="image-gallery">
                <?php foreach($room_images['bedroom'] as $img): ?>
                <img src="<?php echo $img; ?>" alt="Bedroom" class="gallery-image">
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- BATHROOM IMAGES -->
        <?php if(isset($room_images['bathroom'])): ?>
        <div class="gallery-section">
            <h2 class="section-title"><span>🚿</span> Bathroom</h2>
            <div class="image-gallery">
                <?php foreach($room_images['bathroom'] as $img): ?>
                <img src="<?php echo $img; ?>" alt="Bathroom" class="gallery-image">
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- LIVING AREA IMAGES -->
        <?php if(isset($room_images['living'])): ?>
        <div class="gallery-section">
            <h2 class="section-title"><span>🛋️</span> Living Area</h2>
            <div class="image-gallery">
                <?php foreach($room_images['living'] as $img): ?>
                <img src="<?php echo $img; ?>" alt="Living Area" class="gallery-image">
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- KITCHEN IMAGES -->
        <?php if(isset($room_images['kitchen'])): ?>
        <div class="gallery-section">
            <h2 class="section-title"><span>🍽️</span> Kitchen</h2>
            <div class="image-gallery">
                <?php foreach($room_images['kitchen'] as $img): ?>
                <img src="<?php echo $img; ?>" alt="Kitchen" class="gallery-image">
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- DINING IMAGES -->
        <?php if(isset($room_images['dining'])): ?>
        <div class="gallery-section">
            <h2 class="section-title"><span>🍴</span> Dining Area</h2>
            <div class="image-gallery">
                <?php foreach($room_images['dining'] as $img): ?>
                <img src="<?php echo $img; ?>" alt="Dining Area" class="gallery-image">
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- BALCONY IMAGES -->
        <?php if(isset($room_images['balcony'])): ?>
        <div class="gallery-section">
            <h2 class="section-title"><span>🌅</span> Balcony & View</h2>
            <div class="image-gallery">
                <?php foreach($room_images['balcony'] as $img): ?>
                <img src="<?php echo $img; ?>" alt="Balcony View" class="gallery-image">
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- JACUZZI/EXTRA -->
        <?php if(isset($room_images['jacuzzi'])): ?>
        <div class="gallery-section">
            <h2 class="section-title"><span>💦</span> Premium Features</h2>
            <div class="image-gallery">
                <?php foreach($room_images['jacuzzi'] as $img): ?>
                <img src="<?php echo $img; ?>" alt="Jacuzzi" class="gallery-image">
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Room Info Cards -->
        <div class="info-cards">
            <!-- Facilities -->
            <div class="info-card">
                <h3>🏨 Room Facilities</h3>
                <ul class="facilities-list">
                    <?php foreach($facilities as $facility): ?>
                    <li><?php echo trim($facility); ?></li>
                    <?php endforeach; ?>
                    <li>24/7 Room Service</li>
                    <li>Daily Housekeeping</li>
                    <li>Laundry Service</li>
                    <li>Turndown Service</li>
                </ul>
            </div>
            
            <!-- Specifications -->
            <div class="info-card">
                <h3>📐 Room Specifications</h3>
                <ul class="facilities-list">
                    <li>Room Size: 
                        <?php 
                        $sizes = ['Single' => '250', 'Double' => '350', 'Deluxe' => '450', 'Suite' => '600'];
                        echo ($sizes[$room_type] ?? '300'); 
                        ?> sq.ft.
                    </li>
                    <li>Bed Type: <?php echo $room_type == 'Single' ? 'Single Bed' : 'King Size Bed'; ?></li>
                    <li>Max Guests: <?php echo $room_type == 'Single' ? '1' : ($room_type == 'Double' ? '2' : '3'); ?> Persons</li>
                    <li>View: Sea & City View</li>
                    <li>Floor: Upper Floors (Quiet Zone)</li>
                    <li>Accessibility: Elevator Access</li>
                </ul>
            </div>
            
            <!-- Hotel Services -->
            <div class="info-card">
                <h3>⭐ Hotel Services</h3>
                <ul class="facilities-list">
                    <li>24/7 Front Desk</li>
                    <li>Concierge Service</li>
                    <li>Airport Transfer</li>
                    <li>Tour & Travel Desk</li>
                    <li>Multilingual Staff</li>
                    <li>Currency Exchange</li>
                </ul>
            </div>
        </div>
        
        <!-- Booking CTA -->
        <?php if($room['status'] == 'Available'): ?>
        <div class="booking-cta">
            <h2 style="color: white; margin-bottom: 20px; font-size: 36px;">Ready to Experience Luxury?</h2>
            <p style="color: rgba(255,255,255,0.8); margin-bottom: 30px; font-size: 18px;">
                Book now to secure your stay in our premium <?php echo $room_type; ?> room.
            </p>
            <a href="book-room.php?id=<?php echo $room['id']; ?>" class="btn" style="padding: 22px 50px; font-size: 20px; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                Book This Room Now
            </a>
        </div>
        <?php else: ?>
        <div class="booking-cta" style="background: rgba(250, 112, 154, 0.1);">
            <h2 style="color: white; margin-bottom: 20px; font-size: 36px;">Currently Booked</h2>
            <p style="color: rgba(255,255,255,0.8); margin-bottom: 30px; font-size: 18px;">
                This luxury room is currently unavailable. Please check other rooms or contact us.
            </p>
            <a href="index.php" class="btn" style="padding: 22px 50px; font-size: 20px;">
                Browse Available Rooms
            </a>
        </div>
        <?php endif; ?>
        
        <?php else: ?>
        <!-- Room not found -->
        <div style="text-align: center; padding: 100px 20px;">
            <h2 style="color: white; margin-bottom: 20px; font-size: 36px;">Room Not Found</h2>
            <p style="color: rgba(255,255,255,0.8); margin-bottom: 30px; font-size: 18px;">
                The requested room doesn't exist or has been removed.
            </p>
            <a href="index.php" class="btn" style="padding: 20px 50px; font-size: 20px;">
                Browse All Rooms
            </a>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>