<?php 
session_start();

// Require login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$event_id = (int)($_GET['event_id'] ?? 0);
if ($event_id <= 0) {
    header('Location: index.php');
    exit;
}

// Fetch event details
$page_title = 'Payment - Eventra';
include 'includes/db.php';
include 'includes/header.php';

$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) {
    include 'includes/footer.php';
    die('<main class="section"><div class="container"><div class="alert alert-error">Event not found.</div></div></main>');
}
?>

<main class="section">
    <div class="container">
        <div class="grid" style="max-width: 900px; margin: 0 auto;">
            <!-- Event Summary -->
            <div class="card">
                <h2 style="color: #00d4ff;">Event Summary</h2>
                <img src="assets/images/<?php echo htmlspecialchars($event['image'] ?: 'placeholder-event.jpg'); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>" style="width: 100%; height: 200px; object-fit: cover; border-radius: 10px;">
                <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                <p><strong>Date:</strong> <?php echo date('M j, Y g:i A', strtotime($event['event_date'])); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                <div class="event-price" style="font-size: 1.5rem;">Total: $<?php echo number_format($event['price'], 2); ?></div>
            </div>
            
            <!-- Payment Form -->
            <div class="card">
                <h2 style="color: #2ed573;">Secure Payment</h2>
                <p style="color: #b0b0b0; margin-bottom: 2rem;">Choose your preferred payment method (Demo)</p>
                
                <form method="POST" action="book_event.php" id="paymentForm">
                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                    
                    <div class="form-group">
                        <label>Payment Method</label>
                        <div style="display: grid; gap: 1rem; margin-top: 1rem;">
                            <label style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: rgba(46,213,115,0.1); border-radius: 10px; cursor: pointer;">
                                <span style="font-size: 1.5rem;">💳</span>
                                <div>
                                    <strong>Credit/Debit Card</strong>
                                    <p style="color: #b0b0b0; font-size: 0.9rem;">Visa, MasterCard, Amex</p>
                                </div>
                                <input type="radio" name="method" value="card" style="margin-left: auto;" required>
                            </label>
                            
                            <label style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: rgba(0,212,255,0.1); border-radius: 10px; cursor: pointer;">
                                <span style="font-size: 1.5rem;">📱</span>
                                <div>
                                    <strong>UPI</strong>
                                    <p style="color: #b0b0b0; font-size: 0.9rem;">Google Pay, PhonePe, Paytm</p>
                                </div>
                                <input type="radio" name="method" value="upi" style="margin-left: auto;">
                            </label>
                            
                            <label style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: rgba(255,193,7,0.1); border-radius: 10px; cursor: pointer;">
                                <span style="font-size: 1.5rem;">🏦</span>
                                <div>
                                    <strong>Net Banking</strong>
                                    <p style="color: #b0b0b0; font-size: 0.9rem;">All major banks</p>
                                </div>
                                <input type="radio" name="method" value="netbanking" style="margin-left: auto;">
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-success" style="width: 100%; font-size: 1.1rem; padding: 1rem;">
                        Pay Now $<?php echo number_format($event['price'], 2); ?> →
                    </button>
                </form>
                
                <div style="text-align: center; margin-top: 1.5rem; color: #b0b0b0; font-size: 0.9rem;">
                    🔒 Secure SSL | No charges until booking confirmed | Cancel anytime
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 3rem;">
            <a href="javascript:history.back()" class="btn btn-secondary">← Back to Event</a>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
