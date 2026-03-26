<?php
session_start();
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Events - Eventra</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar same as index.php -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="index.php">Eventra</a>
            </div>
            <ul class="nav-menu">
                <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="events.php" class="nav-link active">Events</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                    <li class="nav-item"><a href="logout.php" class="nav-link">Logout (<?php echo $_SESSION['user_name']; ?>)</a></li>
                <?php else: ?>
                    <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
                    <li class="nav-item"><a href="register.php" class="nav-link">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1>All Events</h1>
        <div class="events-grid">
            <?php
            $stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC");
            while ($event = $stmt->fetch(PDO::FETCH_ASSOC)):
                $userBooked = isset($_SESSION['user_id']) ? isEventBooked($pdo, $_SESSION['user_id'], $event['id']) : false;
            ?>
            <div class="event-card">
                <img src="assets/images/<?php echo htmlspecialchars($event['image'] ?? 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>" onerror="this.src='https://via.placeholder.com/300x200?text=Event'">
                <div class="event-info">
                    <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                    <p><?php echo htmlspecialchars(substr($event['description'], 0, 100)); ?>...</p>
                    <p class="event-date"><i class="fas fa-calendar"></i> <?php echo date('M d, Y g:i A', strtotime($event['event_date'])); ?></p>
                    <div class="event-price">$<?php echo number_format($event['price'], 2); ?></div>
                    <?php if ($userBooked): ?>
                        <button class="book-btn disabled" disabled><i class="fas fa-check"></i> Already Booked</button>
                    <?php else: ?>
                        <a href="booking.php?event_id=<?php echo $event['id']; ?>" class="book-btn"><i class="fas fa-ticket-alt"></i> Book Now</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>

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
                    <li><a href="login.php">Login</a></li>
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
</html>

