<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'config/db.php';

$event_id = $_GET['event_id'] ?? 0;
$event = null;

if ($event_id) {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$event_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$event) {
    header('Location: events.php');
    exit;
}

$userBooked = isEventBooked($pdo, $_SESSION['user_id'], $event_id);
if ($userBooked) {
    header('Location: events.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Booking - Eventra</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navbar -->

    <div class="container">
        <div class="booking-confirm">
            <h1>Confirm Your Booking</h1>
            <div class="event-details-card">
                <img src="assets/images/<?php echo htmlspecialchars($event['image'] ?? 'default.jpg'); ?>" alt="">
                <div>
                    <h2><?php echo htmlspecialchars($event['title']); ?></h2>
                    <p class="event-date"><?php echo date('M d, Y g:i A', strtotime($event['event_date'])); ?></p>
                    <div class="total-price">$<?php echo number_format($event['price'], 2); ?></div>
                </div>
            </div>
            <a href="payment.php?event_id=<?php echo $event['id']; ?>" class="btn-primary full-width">Proceed to Payment</a>
            <a href="events.php" class="btn-secondary">Back to Events</a>
        </div>
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

