<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'config/db.php';

$user_id = $_SESSION['user_id'];

// Upcoming bookings
$stmt = $pdo->prepare("SELECT b.*, e.title, e.event_date, e.price FROM bookings b JOIN events e ON b.event_id = e.id WHERE b.user_id = ? AND b.payment_status = 'completed' AND e.event_date > NOW() ORDER BY e.event_date ASC");
$stmt->execute([$user_id]);
$upcoming = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Past bookings
$stmt = $pdo->prepare("SELECT b.*, e.title, e.event_date, e.price FROM bookings b JOIN events e ON b.event_id = e.id WHERE b.user_id = ? AND b.payment_status = 'completed' AND e.event_date <= NOW() ORDER BY e.event_date DESC LIMIT 5");
$stmt->execute([$user_id]);
$past = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Eventra</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navbar with active Dashboard -->

    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3><?php echo count($upcoming); ?></h3>
                <p>Upcoming Events</p>
            </div>
            <div class="stat-card">
                <h3><?php echo count($past); ?></h3>
                <p>Past Bookings</p>
            </div>
        </div>
        
        <?php if ($upcoming): ?>
        <section class="bookings-section">
            <h2>Upcoming Bookings</h2>
            <div class="events-grid">
                <?php foreach ($upcoming as $booking): ?>
                <div class="event-card">
                    <div class="event-info">
                        <h3><?php echo htmlspecialchars($booking['title']); ?></h3>
                        <p class="event-date"><?php echo date('M d, Y g:i A', strtotime($booking['event_date'])); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
        
        <section class="bookings-section">
            <h2>Booking History</h2>
            <div class="events-grid">
                <?php foreach ($past as $booking): ?>
                <div class="event-card small">
                    <h4><?php echo htmlspecialchars($booking['title']); ?></h4>
                    <p><?php echo date('M d, Y', strtotime($booking['event_date'])); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-brand">
                <h3>Eventra</h3>
                <p>Your gateway to amazing events. Book with confidence.</p>
            </div>
            <div class="footer-links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="events.php">Events</a></li>
                    <li><a href="blog.php">Blog</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
            <div class="footer-contact">
                <h4>Contact</h4>
                <p><a href="mailto:support@eventra.com">support@eventra.com</a></p>
                <p>+91 98765 43210</p>
                <p><i class="fab fa-twitter"></i> @eventra</p>
            </div>
        </div>
        <div class="footer-bottom">
            © 2026 Eventra. All rights reserved.
        </div>
    </div>
</footer>
</body>
</html>

