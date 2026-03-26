<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

include '../config/db.php';

$bookings = $pdo->query("SELECT b.*, u.name as user_name, e.title FROM bookings b 
                        JOIN users u ON b.user_id = u.id 
                        JOIN events e ON b.event_id = e.id 
                        ORDER BY b.booking_date DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bookings - Eventra Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="admin-nav">
        <a href="dashboard.php">Dashboard</a> | <a href="events.php">Events</a> | <a href="bookings.php">Bookings</a> | <a href="logout.php">Logout</a>
    </nav>
    
    <div class="container">
        <h1>All Bookings</h1>
        <table class="admin-table">
            <thead>
                <tr><th>ID</th><th>User</th><th>Event</th><th>Status</th><th>Date</th></tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?php echo $booking['id']; ?></td>
                    <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($booking['title']); ?></td>
                    <td><span class="status <?php echo $booking['payment_status']; ?>"><?php echo ucfirst($booking['payment_status']); ?></span></td>
                    <td><?php echo date('M d, Y H:i', strtotime($booking['booking_date'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

