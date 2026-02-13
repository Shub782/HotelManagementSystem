<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission to ADD new room
if (isset($_POST['add_room'])) {
    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $image = $_POST['image'];
    $rating = $_POST['rating'];
    $description = $_POST['description'];
    
    // If no image provided, use default based on room type
    if (empty($image)) {
        $default_images = [
            'Single' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=400&h=300&fit=crop',
            'Double' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=400&h=300&fit=crop',
            'Deluxe' => 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=400&h=300&fit=crop',
            'Suite' => 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=400&h=300&fit=crop'
        ];
        $image = $default_images[$room_type] ?? $default_images['Single'];
    }
    
 $facilities = $_POST['facilities'];

$query = "INSERT INTO rooms (room_number, room_type, price, status, image, rating, description, facilities) 
          VALUES ('$room_number', '$room_type', '$price', '$status', '$image', '$rating', '$description', '$facilities')";
    
    if (mysqli_query($conn, $query)) {
        $success = "Room added successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Fetch all rooms for display
$rooms_query = "SELECT * FROM rooms ORDER BY id DESC";
$rooms_result = mysqli_query($conn, $rooms_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Rooms | Hotel Management</title>
    <link rel="stylesheet" href="assets/css/style.css?v=6">
    <style>
        /* Additional styling for rooms page */
        .container {
            padding: 30px;
        }
        
        .form-section, .table-section {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            padding: 40px;
            margin-bottom: 40px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            transition: transform 0.4s;
        }
        
        .form-section:hover, .table-section:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        }
        
        .form-section h2, .table-section h2 {
            color: white;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 700;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: rgba(255, 255, 255, 0.95);
            font-weight: 600;
            font-size: 15px;
            letter-spacing: 0.5px;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid transparent;
            border-radius: 15px;
            font-size: 16px;
            transition: all 0.4s;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
            font-family: 'Poppins', sans-serif;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            border-color: #667eea;
            background: white;
        }
        
        .btn {
            padding: 18px 35px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 15px;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.4s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 180px;
            letter-spacing: 1px;
            box-shadow: 0 12px 25px rgba(102, 126, 234, 0.3);
        }
        
        .btn:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.4);
        }
        
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            overflow: hidden;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        thead {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9));
        }
        
        th {
            padding: 22px 20px;
            text-align: left;
            color: white;
            font-weight: 600;
            font-size: 15px;
            letter-spacing: 1px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        td {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            color: rgba(255, 255, 255, 0.95);
            font-weight: 500;
        }
        
        tbody tr {
            transition: all 0.3s;
        }
        
        tbody tr:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.01);
        }
        
        .success {
            background: rgba(40, 167, 69, 0.25);
            color: #d4edda;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            border-left: 5px solid #28a745;
            font-weight: 500;
            animation: slideIn 0.5s ease-out;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }
        
        .error {
            background: rgba(220, 53, 69, 0.25);
            color: #f8d7da;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            border-left: 5px solid #dc3545;
            font-weight: 500;
            animation: shake 0.5s ease-out;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            padding: 15px 30px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            border-radius: 15px;
            font-weight: 600;
            transition: all 0.4s;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            margin-bottom: 20px;
        }
        
        .back-btn:hover {
            transform: translateX(-10px);
            color: white;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 0.25);
        }
        
        .status-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            min-width: 120px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s;
            color: white;
        }
        
        .available {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
        
        .booked {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }
        
        .maintenance {
            background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);
        }
        
        .room-type-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            color: white;
        }
        
        .single { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .double { background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); }
        .deluxe { background: linear-gradient(135deg, #fad0c4 0%, #ffd1ff 100%); color: #333; }
        .suite { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #333; }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
        <h1 style="color: white; margin-bottom: 30px; font-size: 36px; font-weight: 800; text-shadow: 0 2px 15px rgba(0,0,0,0.3);">Manage Rooms</h1>
        
        <!-- Success/Error Messages -->
        <?php if(isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if(isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- Add Room Form -->
        <div class="form-section">
            <h2>Add New Room</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Room Number:</label>
                    <input type="text" name="room_number" required>
                </div>
                
                <div class="form-group">
                    <label>Room Type:</label>
                    <select name="room_type" required>
                        <option value="Single">Single</option>
                        <option value="Double">Double</option>
                        <option value="Deluxe">Deluxe</option>
                        <option value="Suite">Suite</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Room Image (URL):</label>
                    <input type="text" name="image" placeholder="https://example.com/room-image.jpg">
                    <small style="color: rgba(255,255,255,0.7); display: block; margin-top: 5px;">Leave empty for default image</small>
                </div>
                
                <div class="form-group">
                    <label>Rating (1-5):</label>
                    <select name="rating">
                        <option value="0">Not Rated</option>
                        <option value="5.0">★★★★★ (5.0)</option>
                        <option value="4.5">★★★★★ (4.5)</option>
                        <option value="4.0">★★★★☆ (4.0)</option>
                        <option value="3.5">★★★☆☆ (3.5)</option>
                        <option value="3.0">★★★☆☆ (3.0)</option>
                    </select>
                </div>
                
        <div class="form-group">
            <label>Facilities (comma separated):</label>
            <textarea name="facilities" rows="3" placeholder="Free WiFi, AC, TV, King Bed, Sea View, Room Service, Mini Bar, Daily Cleaning"></textarea>
        </div>

                
                <div class="form-group">
                    <label>Price per Night ($):</label>
                    <input type="number" name="price" required>
                </div>
                
                <div class="form-group">
                    <label>Status:</label>
                    <select name="status" required>
                        <option value="Available">Available</option>
                        <option value="Booked">Booked</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                </div>
                
                <button type="submit" name="add_room" class="btn">Add Room</button>
            </form>
        </div>
        
        <!-- Rooms Table -->
        <div class="table-section">
            <h2>All Rooms</h2>
            <?php if(mysqli_num_rows($rooms_result) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Room Details</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($room = mysqli_fetch_assoc($rooms_result)): ?>
                        <tr>
                            <td style="width: 120px; vertical-align: middle;">
                                <img src="<?php echo $room['image'] ? $room['image'] : 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=400&h=300&fit=crop'; ?>" 
                                     alt="Room <?php echo $room['room_number']; ?>"
                                     style="width: 100px; height: 70px; object-fit: cover; border-radius: 8px; border: 2px solid rgba(255,255,255,0.3);">
                            </td>
                            <td style="vertical-align: middle;">
                                <strong style="font-size: 18px;">Room <?php echo $room['room_number']; ?></strong>
                                <?php if(!empty($room['description'])): ?>
                                    <br><small style="color: rgba(255,255,255,0.7); font-size: 14px;"><?php echo $room['description']; ?></small>
                                <?php endif; ?>
                            </td>
                            <td style="vertical-align: middle;">
                                <span class="room-type-badge <?php echo strtolower($room['room_type']); ?>">
                                    <?php echo $room['room_type']; ?>
                                </span>
                            </td>
                            <td style="vertical-align: middle;">
                                <strong style="font-size: 20px;">$<?php echo $room['price']; ?></strong><br>
                                <small style="color: rgba(255,255,255,0.7);">per night</small>
                            </td>
                            <td style="vertical-align: middle;">
                                <span class="status-badge <?php echo strtolower($room['status']); ?>">
                                    <?php echo $room['status']; ?>
                                </span>
                            </td>
                            <td style="vertical-align: middle;">
                                <?php if($room['rating'] > 0): ?>
                                    <div style="color: #FFD700; font-size: 16px; text-align: center;">
                                        <?php
                                        $fullStars = floor($room['rating']);
                                        $halfStar = ($room['rating'] - $fullStars) >= 0.5;
                                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                        
                                        echo str_repeat('★', $fullStars);
                                        echo $halfStar ? '½' : '';
                                        echo str_repeat('☆', $emptyStars);
                                        ?>
                                        <br>
                                        <small style="color: rgba(255,255,255,0.8); font-size: 14px;"><?php echo $room['rating']; ?>/5</small>
                                    </div>
                                <?php else: ?>
                                    <span style="color: rgba(255,255,255,0.5); font-size: 14px;">Not rated</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: rgba(255,255,255,0.8); text-align: center; padding: 40px; font-size: 18px;">No rooms added yet. Add your first room above!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>