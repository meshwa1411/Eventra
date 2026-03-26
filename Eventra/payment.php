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

if ($_POST) {
    $payment_method = $_POST['payment_method'];
    $card_number = trim($_POST['card_number']);
    $name_on_card = trim($_POST['name_on_card']);
    
    // Dummy validation
    if (empty($card_number) || strlen($card_number) < 13 || empty($name_on_card)) {
        $error = 'Please fill all fields correctly';
    } else {
        // Simulate payment success
        $stmt = $pdo->prepare("INSERT INTO bookings (user_id, event_id, payment_status) VALUES (?, ?, 'completed')");
        if ($stmt->execute([$_SESSION['user_id'], $event_id])) {
            header('Location: success.php?booking_id=' . $pdo->lastInsertId());
            exit;
        } else {
            $error = 'Booking failed';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Eventra</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navbar -->

    <div class="container">
        <div class="payment-page">
            <h1>Secure Payment</h1>
            <div class="payment-summary">
                <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                <p>Total: $<span id="total-price"><?php echo number_format($event['price'], 2); ?></span></p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" id="paymentForm" class="payment-form">
                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="payment_method" required class="form-select">
                        <option value="card">Credit/Debit Card</option>
                        <option value="upi">UPI</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Card Number / UPI ID</label>
                    <input type="text" name="card_number" placeholder="1234 5678 9012 3456" required maxlength="19">
                </div>
                
                <div class="form-group">
                    <label>Name on Card</label>
                    <input type="text" name="name_on_card" required>
                </div>
                
                <button type="submit" class="btn-primary full-width">
                    <span class="loading" style="display:none;">Processing...</span>
                    <span class="default">Pay Now $<?php echo number_format($event['price'], 2); ?></span>
                </button>
            </form>
            
            <a href="booking.php?event_id=<?php echo $event_id; ?>" class="btn-secondary">Back</a>
        </div>
</div>

    <script src="assets/js/script.js"></script>
    <script>
        // Form validation and loading
        document.getElementById('paymentForm').addEventListener('submit', function() {
            const btn = this.querySelector('button');
            btn.querySelector('.loading').style.display = 'inline';
            btn.querySelector('.default').style.display = 'none';
        });
    </script>

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

