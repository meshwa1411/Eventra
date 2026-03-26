<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'config/db.php';

$booking_id = $_GET['booking_id'] ?? 0;
$booking = null;

if ($booking_id) {
    $stmt = $pdo->prepare("SELECT b.*, e.title, e.event_date, e.price FROM bookings b JOIN events e ON b.event_id = e.id WHERE b.id = ? AND b.user_id = ?");
    $stmt->execute([$booking_id, $_SESSION['user_id']]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$booking || $booking['payment_status'] !== 'completed') {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Successful - Eventra</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navbar -->

    <div class="container">
        <div class="success-page">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1>Booking Confirmed!</h1>
            <p>Your ticket has been booked successfully.</p>
            
            <div class="booking-details">
                <h3>Booking #<?php echo $booking['id']; ?></h3>
                <p><strong>Event:</strong> <?php echo htmlspecialchars($booking['title']); ?></p>
                <p><strong>Date:</strong> <?php echo date('M d, Y g:i A', strtotime($booking['event_date'])); ?></p>
                <p><strong>Price:</strong> $<?php echo number_format($booking['price'], 2); ?></p>
            </div>
            
            <a href="dashboard.php" class="btn-primary">View Dashboard</a>
        <a href="events.php" class="btn-secondary">Book More</a>
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
                </ul>
            </div>
            <div class="footer-contact">
                <h4>Contact</h4>
                <p><a href="mailto:support@eventra.com">support@eventra.com</a></p>
                <p>+91 98765 43210</p>
            </div>
        </div>
        <div class="footer-bottom">
            © 2026 Eventra. All rights reserved.
        </div>
    </div>
</footer>
</body>
</html>

