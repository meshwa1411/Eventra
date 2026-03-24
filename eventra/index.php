<?php 
session_start();
$page_title = 'Eventra - Home';
include 'includes/db.php';
include 'includes/header.php'; 

// Fetch featured events
$stmt = $pdo->query("SELECT * FROM events WHERE event_date > NOW() ORDER BY event_date ASC LIMIT 6");
$events = $stmt->fetchAll();


// Fetch latest blogs
$stmt = $pdo->query("SELECT * FROM blogs ORDER BY created_at DESC LIMIT 3");
$blogs = $stmt->fetchAll();
?>

<main>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Discover Amazing Events</h1>
            <p>Book tickets, read blogs, and never miss out on experiences that matter.</p>
            <a href="#events" class="btn btn-primary" style="font-size: 1.2rem; padding: 1rem 2.5rem;">Find Events</a>
        </div>
    </section>

    <!-- Featured Events -->
    <section id="events" class="section">
        <div class="container">
            <h2 class="section-title">Featured Events</h2>
            <div class="grid">
                <?php if (empty($events)): ?>
                    <p style="grid-column: 1 / -1; text-align: center; color: #b0b0b0;">No events yet. <a href="admin/add_event.php">Admin: Add some!</a></p>
                <?php else: ?>
                    <?php foreach ($events as $event): ?>
                        <div class="card">
                            <img src="assets/images/<?php echo htmlspecialchars($event['image'] ?: 'placeholder-event.jpg'); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
                            <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                            <p><?php echo htmlspecialchars(substr($event['description'], 0, 100)) . '...'; ?></p>
                            <p><strong>Date:</strong> <?php echo date('M j, Y g:i A', strtotime($event['event_date'])); ?></p>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                            <div class="event-price">$<?php echo number_format($event['price'], 2); ?></div>
                            <a href="payment.php?event_id=<?php echo $event['id']; ?>" class="btn booking-btn">Book Now</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Latest Blogs -->
    <section class="section" style="background: rgba(30,30,30,0.5);">
        <div class="container">
            <h2 class="section-title">Latest Blogs</h2>
            <div class="grid">
                <?php if (empty($blogs)): ?>
                    <p style="grid-column: 1 / -1; text-align: center; color: #b0b0b0;">No blogs yet. <a href="admin/add_blog.php">Create one!</a></p>
                <?php else: ?>
                    <?php foreach ($blogs as $blog): ?>
                        <div class="card">
                            <img src="assets/images/<?php echo htmlspecialchars($blog['image'] ?: 'placeholder-blog.jpg'); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                            <h3><?php echo htmlspecialchars($blog['title']); ?></h3>
                            <p><?php echo htmlspecialchars(substr($blog['content'], 0, 120)) . '...'; ?></p>
                            <p style="color: #b0b0b0;"><small><?php echo date('M j, Y', strtotime($blog['created_at'])); ?></small></p>
                            <a href="blog_details.php?id=<?php echo $blog['id']; ?>" class="btn btn-secondary">Read More</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div style="text-align: center; margin-top: 2rem;">
                <a href="blogs.php" class="btn btn-primary">View All Blogs</a>
            </div>
        </div>
    </section>

    <?php if (isset($_SESSION['user_id'])): ?>
    <div style="background: rgba(0,212,255,0.1); padding: 1rem; text-align: center; border-radius: 10px; margin: 2rem 0;">
        <p>Welcome back, <?php echo htmlspecialchars($_SESSION['name']); ?>! <a href="dashboard.php">View Dashboard →</a></p>
    </div>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
