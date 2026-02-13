<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['add_room'])) {
    $room_number = mysqli_real_escape_string($conn, $_POST['room_number']);
    $room_type = mysqli_real_escape_string($conn, $_POST['room_type']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $status = 'Available'; // Always Available when adding
    $image = mysqli_real_escape_string($conn, $_POST['image']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $facilities = mysqli_real_escape_string($conn, $_POST['facilities']);
    
    // Set default image if empty
    if (empty($image)) {
        $default_images = [
            'Single' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800&h=600&fit=crop',
            'Double' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=800&h=600&fit=crop',
            'Deluxe' => 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=800&h=600&fit=crop',
            'Suite' => 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800&h=600&fit=crop'
        ];
        $image = $default_images[$room_type] ?? $default_images['Single'];
    }
    
    $query = "INSERT INTO rooms (room_number, room_type, price, status, image, rating, description, facilities) 
              VALUES ('$room_number', '$room_type', '$price', '$status', '$image', '$rating', '$description', '$facilities')";
    
    if (mysqli_query($conn, $query)) {
        $success = "Room added successfully!";
        // Clear form
        $_POST = array();
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Room | Grand Hotel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        
        body {
            background: #f5f7fa;
        }
        
        .header {
            background: white;
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .nav-links {
            display: flex;
            gap: 20px;
        }
        
        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 5px;
            transition: 0.3s;
        }
        
        .nav-links a:hover {
            background: #f0f2f5;
        }
        
        .nav-links a.active {
            background: #667eea;
            color: white;
        }
        
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .page-title {
            font-size: 32px;
            color: #333;
        }
        
        .back-btn {
            background: #667eea;
            color: white;
            padding: 10px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: 0.3s;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .btn {
            background: #28a745;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
        }
        
        .btn:hover {
            background: #218838;
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .preview-image {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 5px;
            margin-top: 10px;
            display: none;
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                padding: 15px;
            }
            
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .page-header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
            
            .form-container {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <div class="logo">
            <span>🏨</span>
            <span>Grand Hotel Admin</span>
        </div>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="rooms.php">Rooms</a>
            <a href="bookings.php">Bookings</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <!-- MAIN CONTENT -->
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Add New Room</h1>
            <a href="rooms.php" class="back-btn">← Back to Rooms</a>
        </div>
        
        <?php if(isset($success)): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <div class="form-container">
            <form method="POST" id="roomForm">
                <div class="form-group">
                    <label for="room_number">Room Number *</label>
                    <input type="text" id="room_number" name="room_number" 
                           value="<?php echo isset($_POST['room_number']) ? $_POST['room_number'] : ''; ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="room_type">Room Type *</label>
                    <select id="room_type" name="room_type" required>
                        <option value="Single" <?php echo (isset($_POST['room_type']) && $_POST['room_type'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                        <option value="Double" <?php echo (isset($_POST['room_type']) && $_POST['room_type'] == 'Double') ? 'selected' : ''; ?>>Double</option>
                        <option value="Deluxe" <?php echo (isset($_POST['room_type']) && $_POST['room_type'] == 'Deluxe') ? 'selected' : ''; ?>>Deluxe</option>
                        <option value="Suite" <?php echo (isset($_POST['room_type']) && $_POST['room_type'] == 'Suite') ? 'selected' : ''; ?>>Suite</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="price">Price per Night ($) *</label>
                    <input type="number" id="price" name="price" min="1" step="0.01"
                           value="<?php echo isset($_POST['price']) ? $_POST['price'] : ''; ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="image">Room Image URL</label>
                    <input type="url" id="image" name="image" 
                           value="<?php echo isset($_POST['image']) ? $_POST['image'] : ''; ?>"
                           placeholder="https://example.com/room-image.jpg"
                           oninput="showPreview(this.value)">
                    <small>Leave empty for default image based on room type</small>
                    <img id="imagePreview" class="preview-image" src="" alt="Preview">
                </div>
                
                <div class="form-group">
                    <label for="rating">Rating (1-5)</label>
                    <select id="rating" name="rating">
                        <option value="0">Not Rated</option>
                        <option value="5" <?php echo (isset($_POST['rating']) && $_POST['rating'] == '5') ? 'selected' : ''; ?>>★★★★★ (5.0)</option>
                        <option value="4.5" <?php echo (isset($_POST['rating']) && $_POST['rating'] == '4.5') ? 'selected' : ''; ?>>★★★★☆ (4.5)</option>
                        <option value="4" <?php echo (isset($_POST['rating']) && $_POST['rating'] == '4') ? 'selected' : ''; ?>>★★★★☆ (4.0)</option>
                        <option value="3.5" <?php echo (isset($_POST['rating']) && $_POST['rating'] == '3.5') ? 'selected' : ''; ?>>★★★☆☆ (3.5)</option>
                        <option value="3" <?php echo (isset($_POST['rating']) && $_POST['rating'] == '3') ? 'selected' : ''; ?>>★★★☆☆ (3.0)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" 
                              placeholder="Spacious room with sea view, king-size bed, and modern amenities"><?php echo isset($_POST['description']) ? $_POST['description'] : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="facilities">Facilities (comma separated)</label>
                    <textarea id="facilities" name="facilities" 
                              placeholder="Free WiFi, AC, TV, King Bed, Sea View, Mini Bar, Room Service"><?php echo isset($_POST['facilities']) ? $_POST['facilities'] : ''; ?></textarea>
                </div>
                
                <button type="submit" name="add_room" class="btn">Add Room</button>
            </form>
        </div>
    </div>
    
    <script>
        function showPreview(url) {
            var preview = document.getElementById('imagePreview');
            if (url) {
                preview.src = url;
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        }
        
        // Show default image based on room type
        document.getElementById('room_type').addEventListener('change', function() {
            var imageInput = document.getElementById('image');
            if (!imageInput.value) {
                var defaultImages = {
                    'Single': 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800&h=600&fit=crop',
                    'Double': 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=800&h=600&fit=crop',
                    'Deluxe': 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=800&h=600&fit=crop',
                    'Suite': 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800&h=600&fit=crop'
                };
                var preview = document.getElementById('imagePreview');
                preview.src = defaultImages[this.value] || defaultImages['Single'];
                preview.style.display = 'block';
            }
        });
    </script>
</body>
</html>