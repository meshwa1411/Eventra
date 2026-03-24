<?php 
session_start();

// Admin access check
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../dashboard.php');
    exit;
}

$page_title = 'Admin Dashboard - Eventra';
include '../includes/db.php';
include '../includes/header.php';

// Fetch stats
$stats = [
    'users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'events' => $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn(),
    'bookings' => $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn(),
    'blogs' => $pdo->query("SELECT COUNT(*) FROM blogs")->fetchColumn(),
];
?>

<main class="section">
    <div class="container">
        <h1 class="section-title">Admin Dashboard</h1>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['users']; ?></div>
                <div>Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['events']; ?></div>
                <div>Total Events</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['bookings']; ?></div>
                <div>Total Bookings</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['blogs']; ?></div>
                <div>Total Blogs</div>
            </div>
        </div>
        
        <div class="grid" style="margin-top: 3rem;">
            <div class="card">
                <h3>🗓️ Event Management</h3>
                <p>CRUD events completely</p>
                <div style="margin-top: 1rem;">
                    <a href="add_event.php" class="btn btn-success">Add Event</a>
                    <a href="manage_events.php" class="btn btn-primary">Manage Events</a>
                </div>
            </div>
            <div class="card">
                <h3>📝 Blog Management</h3>
                <p>Create and manage blog posts</p>
                <div style="margin-top: 1rem;">
                    <a href="add_blog.php" class="btn btn-success">Add Blog</a>
                    <a href="manage_blogs.php" class="btn btn-primary">Manage Blogs</a>
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 3rem;">
            <a href="../index.php" class="btn btn-secondary">View Public Site</a>
            <a href="../dashboard.php" class="btn btn-secondary">User Dashboard</a>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
