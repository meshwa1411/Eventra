<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventra - Book Amazing Events</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="index.php">Eventra</a>
            </div>
            <ul class="nav-menu">
                <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="events.php" class="nav-link">Events</a></li>
                <li class="nav-item"><a href="blog.php"  class="nav-link">Blog</a></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                    <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
                    <li class="nav-item"><a href="register.php" class="nav-link">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <header class="hero">
        <div class="hero-content">
            <h1>Discover Amazing Events</h1>
            <p>Book tickets to the best events near you</p>
            <a href="events.php" class="cta-button">Find Events</a>
        </div>
    </header>

    <section class="featured-events">
        <div class="container">
            <h2>Featured Events</h2>
            <div class="events-grid">
                <?php
                include 'config/db.php';
                $stmt = $pdo->query("SELECT * FROM events ORDER BY id DESC LIMIT 3");
                while ($event = $stmt->fetch(PDO::FETCH_ASSOC)):
                ?>
                <div class="event-card">
                    <img src="assets/images/<?php echo htmlspecialchars($event['image']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
                    <div class="event-info">
                        <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p class="event-date"><i class="fas fa-calendar"></i> <?php echo date('M d, Y g:i A', strtotime($event['event_date'])); ?></p>
                        <p class="event-price">$<?php echo number_format($event['price'], 2); ?></p>
                        <a href="events.php" class="book-btn">View All Events</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <script src="assets/js/script.js"></script>


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
</body>
</html>

