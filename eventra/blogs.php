<?php 
session_start();
$page_title = 'Blogs - Eventra';
include 'includes/db.php';
include 'includes/header.php';

// Fetch all blogs
$stmt = $pdo->query("SELECT * FROM blogs ORDER BY created_at DESC");
$blogs = $stmt->fetchAll();
?>

<main class="section">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 1rem;">All Blog Posts</h1>
        
        <?php if (empty($blogs)): ?>
            <div class="alert alert-info" style="text-align: center;">
                No blog posts yet. <a href="admin/add_blog.php">Admin: Create the first one!</a>
            </div>
        <?php else: ?>
            <div class="grid">
                <?php foreach ($blogs as $blog): ?>
                    <div class="card">
                        <img src="assets/images/<?php echo htmlspecialchars($blog['image'] ?: 'placeholder-blog.jpg'); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                        <h3><?php echo htmlspecialchars($blog['title']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($blog['content'], 0, 150)) . '...'; ?></p>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <p style="color: #b0b0b0;"><small><?php echo date('M j, Y', strtotime($blog['created_at'])); ?></small></p>
                            <a href="blog_details.php?id=<?php echo $blog['id']; ?>" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
            <div style="text-align: center; margin-top: 3rem;">
                <a href="admin/add_blog.php" class="btn btn-success">Admin: Add New Blog</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
