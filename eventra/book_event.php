<?php 
session_start();

// Require login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'includes/db.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = (int)($_POST['event_id'] ?? 0);
    
    if ($event_id <= 0) {
        $error = 'Invalid event.';
    } else {
        // Simulate payment success (demo)
        $payment_method = $_POST['method'] ?? 'card';
        
        try {
            // Check if already booked
            $stmt = $pdo->prepare("SELECT id FROM bookings WHERE user_id = ? AND event_id = ?");
            $stmt->execute([$_SESSION['user_id'], $event_id]);
            
            if ($stmt->fetch()) {
                $error = 'You already booked this event.';
            } else {
                // Create booking
                $stmt = $pdo->prepare("INSERT INTO bookings (user_id, event_id) VALUES (?, ?)");
                $stmt->execute([$_SESSION['user_id'], $event_id]);
                
                // Fetch event details for display
                $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
                $stmt->execute([$event_id]);
                $event = $stmt->fetch();
                
                $success = true;
            }
        } catch (PDOException $e) {
            $error = 'Booking failed. Try again.';
        }
    }
}

$page_title = $success ? 'Payment Success!' : 'Process Payment';
include 'includes/header.php';
?>

<main class="section">
    <div class="container">
        <?php if ($success && $event): ?>
            <!-- SUCCESS PAGE -->
            <div class="payment-success">
                <div class="success-icon">✅</div>
                <h1>Payment Successful!</h1>
                <p class="success-message">Your booking for <strong><?php echo htmlspecialchars($event['title']); ?></strong> has been confirmed.</p>
                
                <div class="booking-details card">
                    <h3>Booking Confirmation</h3>
                    <div class="booking-info">
                        <div><strong>Event:</strong> <?php echo htmlspecialchars($event['title']); ?></div>
                        <div><strong>Date:</strong> <?php echo date('M j, Y g:i A', strtotime($event['event_date'])); ?></div>
                        <div><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></div>
                        <div><strong>Amount Paid:</strong> $<?php echo number_format($event['price'], 2); ?></div>
                        <div><strong>Method:</strong> <?php echo ucfirst(str_replace('_', ' ', $_POST['method'] ?? 'Card')); ?></div>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <a href="dashboard.php" class="btn btn-primary">View My Bookings</a>
                    <a href="events.php" class="btn btn-secondary">Book Another Event</a>
                </div>
                
                <div class="success-note">
                    <p>Booking details available in your <a href="dashboard.php">dashboard</a>. You will receive confirmation email shortly.</p>
                </div>
            </div>
            
        <?php elseif ($error): ?>
            <!-- ERROR PAGE -->
            <div class="payment-error">
                <div class="error-icon">❌</div>
                <h1>Booking Failed</h1>
                <p><?php echo htmlspecialchars($error); ?></p>
                <a href="payment.php?event_id=<?php echo $event_id; ?>" class="btn btn-primary">Try Again</a>
                <a href="events.php" class="btn btn-secondary">Back to Events</a>
            </div>
            
        <?php else: ?>
            <?php header('Location: events.php'); exit; ?>
        <?php endif; ?>
    </div>
</main>

<style>
.payment-success, .payment-error {
    text-align: center;
    max-width: 600px;
    margin: 2rem auto;
}

.success-icon, .error-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.success-icon { color: #2ed573; }
.error-icon { color: #ff4757; }

.success-message { 
    font-size: 1.2rem; 
    margin-bottom: 2rem; 
    color: #2ed573;
}

.booking-details {
    background: rgba(46, 213, 115, 0.1);
    padding: 2rem;
    border-radius: 15px;
    margin: 2rem 0;
    text-align: left;
}

.booking-info div {
    margin-bottom: 0.8rem;
    font-size: 1.1rem;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    margin: 2rem 0;
}

.success-note {
    background: rgba(255,255,255,0.05);
    padding: 1.5rem;
    border-radius: 10px;
    border-left: 4px solid #2ed573;
}
</style>

<?php include 'includes/footer.php'; ?>

