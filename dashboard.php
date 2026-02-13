<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Fetch counts
$total_query = "SELECT COUNT(*) as count FROM rooms";
$total_result = mysqli_query($conn, $total_query);
$total_rooms = mysqli_fetch_assoc($total_result)['count'];

$available_query = "SELECT COUNT(*) as count FROM rooms WHERE status='Available'";
$available_result = mysqli_query($conn, $available_query);
$available_rooms = mysqli_fetch_assoc($available_result)['count'];

$booked_query = "SELECT COUNT(*) as count FROM rooms WHERE status='Booked'";
$booked_result = mysqli_query($conn, $booked_query);
$booked_rooms = mysqli_fetch_assoc($booked_result)['count'];

// Get recent bookings
$recent_bookings = mysqli_query($conn, "SELECT * FROM bookings ORDER BY id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Grand Hotel</title>
    <style>
        /* RESET AND BASE STYLES */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        
        body {
            background: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        
        /* HEADER */
        .header {
            background: white;
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }
        
        .logout-btn:hover {
            background: #c82333;
        }
        
        /* MAIN CONTENT */
        .main-content {
            margin-top: 80px;
            padding: 30px;
        }
        
        .page-title {
            font-size: 32px;
            color: #333;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #667eea;
        }
        
        /* STATS CARDS */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
        }
        
        .total-rooms .stat-icon { background: #e3f2fd; color: #1976d2; }
        .available-rooms .stat-icon { background: #e8f5e9; color: #2e7d32; }
        .booked-rooms .stat-icon { background: #fff3e0; color: #f57c00; }
        
        .stat-card h3 {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .total-rooms .stat-number { color: #1976d2; }
        .available-rooms .stat-number { color: #2e7d32; }
        .booked-rooms .stat-number { color: #f57c00; }
        
        /* QUICK ACTIONS */
        .quick-actions {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 40px;
        }
        
        .section-title {
            font-size: 24px;
            margin-bottom: 25px;
            color: #333;
        }
        
        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .action-btn {
            background: #667eea;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        
        .action-btn:hover {
            background: #5a67d8;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.2);
        }
        
        .action-btn i {
            font-size: 30px;
        }
        
        .action-btn span {
            font-weight: 600;
            font-size: 16px;
        }
        
        /* RECENT BOOKINGS */
        .recent-bookings {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .bookings-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .bookings-table th {
            background: #f8f9fa;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }
        
        .bookings-table td {
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        /* FOOTER */
        .footer {
            text-align: center;
            padding: 30px;
            margin-top: 50px;
            color: #666;
            border-top: 1px solid #eee;
            background: white;
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .main-content {
                padding: 20px;
                margin-top: 120px;
            }
            
            .stats-grid,
            .action-buttons {
                grid-template-columns: 1fr;
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
        <div class="user-info">
            <div>Welcome, Admin</div>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
    
    <!-- MAIN CONTENT -->
    <div class="main-content">
        <h1 class="page-title">Dashboard Overview</h1>
        
        <!-- STATS -->
        <div class="stats-grid">
            <div class="stat-card total-rooms">
                <div class="stat-icon">🏠</div>
                <h3>Total Rooms</h3>
                <div class="stat-number"><?php echo $total_rooms; ?></div>
                <p>All rooms in the hotel</p>
            </div>
            
            <div class="stat-card available-rooms">
                <div class="stat-icon">✅</div>
                <h3>Available Rooms</h3>
                <div class="stat-number"><?php echo $available_rooms; ?></div>
                <p>Ready for booking</p>
            </div>
            
            <div class="stat-card booked-rooms">
                <div class="stat-icon">📅</div>
                <h3>Booked Rooms</h3>
                <div class="stat-number"><?php echo $booked_rooms; ?></div>
                <p>Currently occupied</p>
            </div>
        </div>
        
        <!-- QUICK ACTIONS -->
        <div class="quick-actions">
            <h2 class="section-title">Quick Actions</h2>
            <div class="action-buttons">
                <a href="rooms.php" class="action-btn">
                    <div style="font-size: 30px;">🏠</div>
                    <span>Manage Rooms</span>
                </a>
                
                <a href="bookings.php" class="action-btn">
                    <div style="font-size: 30px;">📋</div>
                    <span>View Bookings</span>
                </a>
                
                <a href="add-room.php" class="action-btn">
                    <div style="font-size: 30px;">➕</div>
                    <span>Add New Room</span>
                </a>
                
                <a href="index.php" class="action-btn">
                    <div style="font-size: 30px;">🏨</div>
                    <span>View Website</span>
                </a>
            </div>
        </div>
        
        <!-- RECENT BOOKINGS -->
        <div class="recent-bookings">
            <h2 class="section-title">Recent Bookings</h2>
            <table class="bookings-table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Guest Name</th>
                        <th>Room</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($recent_bookings) > 0): ?>
                        <?php while($booking = mysqli_fetch_assoc($recent_bookings)): ?>
                        <tr>
                            <td>#<?php echo $booking['id']; ?></td>
                            <td><?php echo $booking['guest_name']; ?></td>
                            <td>Room <?php echo $booking['room_id']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($booking['check_in'])); ?></td>
                            <td><?php echo date('M d, Y', strtotime($booking['check_out'])); ?></td>
                            <td><span class="status-badge status-confirmed">Confirmed</span></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px;">
                                No bookings found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- FOOTER -->
    <div class="footer">
        <p>© <?php echo date('Y'); ?> Grand Hotel Management System</p>
        <p>All rights reserved</p>
    </div>
    
    <script>
        // Simple JavaScript to ensure page is interactive
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Dashboard loaded successfully');
            
            // Remove any potential overlays
            const overlays = document.querySelectorAll('.modal-backdrop, .overlay');
            overlays.forEach(overlay => {
                if(overlay) overlay.style.display = 'none';
            });
            
            // Ensure body is visible
            document.body.style.opacity = '1';
            document.body.style.filter = 'none';
            document.body.style.pointerEvents = 'auto';
        });
    </script>
</body>
</html>