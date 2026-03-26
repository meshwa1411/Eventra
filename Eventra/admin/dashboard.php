<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

include '../config/db.php';

// Enhanced Stats
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_admins = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
$total_events = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
$total_blogs = $pdo->query("SELECT COUNT(*) FROM blogs")->fetchColumn();
$total_bookings = $pdo->query("SELECT COUNT(*) FROM bookings WHERE payment_status = 'completed'")->fetchColumn();

// Bookings by user (top 5)
$bookings_by_user = $pdo->query("SELECT u.name, COUNT(b.id) as booking_count FROM users u LEFT JOIN bookings b ON u.id = b.user_id AND b.payment_status = 'completed' GROUP BY u.id ORDER BY booking_count DESC LIMIT 5")->fetchAll();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Eventra</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="admin-nav">
        <a href="dashboard.php">Dashboard</a> |
        <a href="events.php">Events</a> |
        <a href="bookings.php">Bookings</a> |
        <a href="logout.php">Logout</a>
    </nav>
    
    <div class="container">
        <h1>Admin Dashboard</h1>
<div class="dashboard-stats">
            <div class="stat-card"><h2><?php echo $total_users; ?></h2><p>Total Users</p></div>
            <div class="stat-card"><h2><?php echo $total_admins; ?></h2><p>Admins</p></div>
            <div class="stat-card"><h2><?php echo $total_events; ?></h2><p>Total Events</p></div>
            <div class="stat-card"><h2><?php echo $total_bookings; ?></h2><p>Total Bookings</p></div>
            <div class="stat-card"><h2><?php echo $total_blogs; ?></h2><p>Total Blogs</p></div>
        </div>
        
        <!-- Top Bookers -->
        <section class="top-bookers">
            <h3>Top Bookers (by count)</h3>
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr><th>User</th><th>Bookings</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings_by_user as $stat): ?>
                        <tr><td><?php echo htmlspecialchars($stat['name']); ?></td><td><?php echo $stat['booking_count']; ?></td></tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

<!-- Simple Admin Footer -->
<footer style="background: #333; color: white; text-align: center; padding: 1rem; margin-top: 2rem;">
    <p>&copy; 2026 Eventra Admin. All rights reserved.</p>
</footer>
</body>
</html>

