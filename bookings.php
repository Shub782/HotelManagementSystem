<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Fetch all bookings with room details
$bookings_query = "SELECT b.*, r.room_number, r.room_type, r.price 
                   FROM bookings b 
                   JOIN rooms r ON b.room_id = r.id 
                   ORDER BY b.id DESC";
$bookings_result = mysqli_query($conn, $bookings_query);

// Handle checkout
if (isset($_GET['checkout'])) {
    $booking_id = $_GET['checkout'];
    
    // Get room_id from booking
    $room_query = "SELECT room_id FROM bookings WHERE id='$booking_id'";
    $room_result = mysqli_query($conn, $room_query);
    $booking = mysqli_fetch_assoc($room_result);
    $room_id = $booking['room_id'];
    
    // Update room status back to Available
    mysqli_query($conn, "UPDATE rooms SET status='Available' WHERE id='$room_id'");
    
    // Mark booking as checked out
    mysqli_query($conn, "UPDATE bookings SET status='Checked Out' WHERE id='$booking_id'");
    
    header("Location: bookings.php?success=Checked+out+successfully");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings | Grand Hotel</title>
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
            max-width: 1200px;
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
        
        .bookings-table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: #f8f9fa;
        }
        
        th {
            padding: 20px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }
        
        td {
            padding: 20px;
            border-bottom: 1px solid #dee2e6;
        }
        
        tbody tr:hover {
            background: #f8f9ff;
        }
        
        .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-checkedout {
            background: #e2e3e5;
            color: #383d41;
        }
        
        .action-btn {
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            margin-right: 8px;
            display: inline-block;
        }
        
        .checkout-btn {
            background: #28a745;
            color: white;
        }
        
        .checkout-btn:hover {
            background: #218838;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .empty-state i {
            font-size: 60px;
            margin-bottom: 20px;
            opacity: 0.5;
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
            
            table {
                display: block;
                overflow-x: auto;
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
            <a href="bookings.php" class="active">Bookings</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <!-- MAIN CONTENT -->
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Manage Bookings</h1>
            <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
        </div>
        
        <?php if(isset($_GET['success'])): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>
        
        <div class="bookings-table">
            <?php if(mysqli_num_rows($bookings_result) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Guest Name</th>
                            <th>Room Details</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($booking = mysqli_fetch_assoc($bookings_result)): 
                            $status = isset($booking['status']) ? $booking['status'] : 'Confirmed';
                        ?>
                        <tr>
                            <td>#<?php echo $booking['id']; ?></td>
                            <td>
                                <strong><?php echo $booking['guest_name']; ?></strong><br>
                                <small><?php echo $booking['guest_email']; ?></small><br>
                                <small><?php echo $booking['guest_phone']; ?></small>
                            </td>
                            <td>
                                Room <?php echo $booking['room_number']; ?><br>
                                <small><?php echo $booking['room_type']; ?> - $<?php echo $booking['price']; ?>/night</small>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($booking['check_in'])); ?></td>
                            <td><?php echo date('M d, Y', strtotime($booking['check_out'])); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower(str_replace(' ', '', $status)); ?>">
                                    <?php echo $status; ?>
                                </span>
                            </td>
                            <td>
                                <?php if($status !== 'Checked Out'): ?>
                                    <a href="bookings.php?checkout=<?php echo $booking['id']; ?>" 
                                       class="action-btn checkout-btn"
                                       onclick="return confirm('Check out guest? Room will become available.')">
                                        Check Out
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div style="font-size: 60px; margin-bottom: 20px;">📭</div>
                    <h3>No Bookings Yet</h3>
                    <p>No bookings have been made yet. Bookings will appear here when guests make reservations.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>