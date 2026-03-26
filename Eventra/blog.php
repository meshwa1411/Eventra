<?php
session_start();
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Eventra</title>
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
                <li class="nav-item"><a href="blog.php" class="nav-link active">Blog</a></li>
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

    <div class="container">
        <div style="text-align: center; margin: 6rem 0 4rem;">
            <h1>Our Blog</h1>
            <p>Latest news, tips, and event insights</p>
        </div>
        
        <div class="blog-grid">
            <?php
            $stmt = $pdo->query("SELECT * FROM blogs ORDER BY created_at DESC");
            while ($blog = $stmt->fetch(PDO::FETCH_ASSOC)):
            ?>
            <div class="blog-card">
                <img src="assets/images/<?php echo htmlspecialchars($blog['image'] ?? 'default.jpg'); ?>" alt="" onerror="this.src='https://via.placeholder.com/400x250?text=Blog'">
                <div class="blog-info">
                    <div class="blog-meta">
                        <i class="fas fa-clock"></i> <?php echo date('M d, Y', strtotime($blog['created_at'])); ?>
                    </div>
                    <h3><?php echo htmlspecialchars($blog['title']); ?></h3>
                    <p class="blog-excerpt"><?php echo htmlspecialchars(substr($blog['content'], 0, 150)); ?>...</p>
                    <a href="blog-details.php?id=<?php echo $blog['id']; ?>" class="book-btn">Read More</a>
                </div>
            </div>
            <?php endwhile; ?>
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

    <script src="assets/js/script.js"></script>
</body>
</html>

