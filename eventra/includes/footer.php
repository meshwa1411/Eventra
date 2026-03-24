    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About Eventra</h3>
                    <p>Your premier platform for event discovery, booking, and blogging. Join thousands finding amazing experiences.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <a href="index.php">Home</a>
                    <a href="blogs.php">Blogs</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="dashboard.php">Dashboard</a>
                    <?php else: ?>
                        <a href="login.php">Login</a>
                        <a href="register.php">Register</a>
                    <?php endif; ?>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <p>Email: support@eventra.com</p>
                    <p>Phone: (555) 123-4567</p>
                    <div class="social-links">
                        <a href="#">📘 Facebook</a>
                        <a href="#">🐦 Twitter</a>
                        <a href="#">📸 Instagram</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Eventra. All rights reserved. | Built with ❤️ for event lovers</p>
            </div>
        </div>
    </footer>
</body>
</html>
