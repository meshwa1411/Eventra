<?php 
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$page_title = 'Dashboard - Eventra';
include 'includes/db.php';
include 'includes/header.php';


// Fetch user bookings with status
$stmt = $pdo->prepare("
    SELECT b.*, e.title, e.event_date, e.location, e.price, e.image 
    FROM bookings b 
    JOIN events e ON b.event_id = e.id 
    WHERE b.user_id = ? 
    ORDER BY b.booking_date DESC
");
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll();

?>

<main class="section">
    <div class="container">
<div class="dashboard-grid">
            <!-- Total Bookings Card -->
            <div class="card stat-card">
                <span class="stat-icon">🎫</span>
                <div class="stat-number"><?php echo count($bookings); ?></div>
                <div>Total Bookings</div>
            </div>
            
            <!-- Upcoming Events (future) -->
            <?php 
            $upcoming = 0;
            foreach ($bookings as $booking) {
                if (strtotime($booking['event_date']) > time()) $upcoming++;
            }
            ?>
            <div class="card stat-card" style="background: linear-gradient(135deg, rgba(46,213,115,0.2), rgba(46,213,115,0.1));">
                <span class="stat-icon">📅</span>
                <div class="stat-number"><?php echo $upcoming; ?></div>
                <div>Upcoming Events</div>
            </div>
            
            <!-- Total Spent -->
            <?php 
            $total_spent = 0;
            foreach ($bookings as $booking) {
                $total_spent += $booking['price'];
            }
            ?>
            <div class="card stat-card" style="background: linear-gradient(135deg, rgba(255,193,7,0.2), rgba(255,193,7,0.1));">
                <span class="stat-icon">💰</span>
                <div class="stat-number">$<?php echo number_format($total_spent, 2); ?></div>
                <div>Total Spent</div>
            </div>
            
            <!-- Profile Card -->
            <div class="card profile-card">
                <div class="profile-img">👤</div>
                <h3><?php echo htmlspecialchars($_SESSION['name']); ?></h3>
                <p style="color: #b0b0b0; margin-bottom: 1rem;"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                <span class="btn btn-secondary btn-glow" style="padding: 0.5rem 2rem; font-size: 0.9rem;">Edit Profile</span>
            </div>
        </div>
        
        <h2 style="text-align: center; color: #00d4ff; margin: 3rem 0 2rem;">My Bookings</h2>
        
        <h3 style="color: #00d4ff; margin: 2rem 0 1rem;">My Bookings</h3>
        <?php if (empty($bookings)): ?>
            <div class="alert alert-info">
                No bookings yet. <a href="index.php#events">Book some events!</a>
            </div>
        <?php else: ?>
            <div class="card">
                <table class="table">

                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($bookings as $booking): ?>

                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <img src="assets/images/<?php echo htmlspecialchars($booking['image'] ?: 'placeholder-event.jpg'); ?>" style="width: 40px; height: 40px; border-radius: 5px; object-fit: cover;">
                                    <?php echo htmlspecialchars($booking['title']); ?>
                                </div>
                            </td>
                            <td>
                                <span class="event-date"><?php echo date('M j, Y g:i A', strtotime($booking['event_date'])); ?></span>
                                <?php if (strtotime($booking['event_date']) < time()): ?>
                                    <span class="status-badge past">Past</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($booking['location']); ?></td>
                            <td>$<?php echo number_format($booking['price'], 2); ?></td>
                            <td><?php echo date('M j, Y', strtotime($booking['booking_date'])); ?></td>
                            <td>
                                <span class="status-badge <?php echo $booking['booking_status'] === 'cancelled' ? 'cancelled' : 'confirmed'; ?>">
                                    <?php echo ucfirst($booking['booking_status'] ?? 'confirmed'); ?>
                                </span>
                                <?php if (($booking['refund_status'] ?? 'none') !== 'none'): ?>
                                    <br><small class="refund-status <?php echo in_array($booking['refund_status'], ['completed']) ? 'refunded' : 'pending'; ?>">
                                        Refund: <?php echo ucfirst(str_replace('_', ' ', $booking['refund_status'])); ?>
                                    </small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($booking['booking_status'] === 'confirmed' && strtotime($booking['event_date']) > time()): ?>
                                    <button class="btn btn-danger btn-sm cancel-btn" data-booking-id="<?php echo $booking['id']; ?>">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 3rem;">
            <a href="index.php" class="btn btn-secondary">Browse Events</a>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
