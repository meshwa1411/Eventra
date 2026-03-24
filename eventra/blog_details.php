<?php 
session_start();
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: blogs.php');
    exit;
}

$page_title = 'Blog Details - Eventra';
include 'includes/db.php';
include 'includes/header.php';

// Fetch single blog
$stmt = $pdo->prepare("SELECT * FROM blogs WHERE id = ?");
$stmt->execute([$id]);
$blog = $stmt->fetch();

if (!$blog) {
    include 'includes/footer.php';
    die('<main class="section"><div class="container"><div class="alert alert-error">Blog not found.</div></div></main>');
}
?>

<main class="section">
    <div class="container">
        <div class="card" style="max-width: 800px; margin: 0 auto;">
            <img src="assets/images/<?php echo htmlspecialchars($blog['image'] ?: 'placeholder-blog.jpg'); ?>" 
                 alt="<?php echo htmlspecialchars($blog['title']); ?>" 
                 style="height: 300px; width: 100%; object-fit: cover;">
            
            <div style="text-align: center; margin: 2rem 0;">
                <h1 style="color: #00d4ff; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($blog['title']); ?></h1>
                <p style="color: #b0b0b0;"><?php echo date('F j, Y', strtotime($blog['created_at'])); ?></p>
            </div>
            
            <div style="line-height: 1.8; font-size: 1.1rem;">
                <?php echo nl2br(htmlspecialchars($blog['content'])); ?>
            </div>
            
            <div style="margin-top: 3rem; display: flex; gap: 1rem; justify-content: space-between; align-items: center;">
                <a href="blogs.php" class="btn btn-secondary">&larr; Back to Blogs</a>
                <div>
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                        <a href="admin/manage_blogs.php?edit=<?php echo $blog['id']; ?>" class="btn btn-primary">Edit</a>
                        <a href="admin/manage_blogs.php?delete=<?php echo $blog['id']; ?>" 
                           class="btn btn-danger" 
                           onclick="return EventraUtils.confirmDelete(<?php echo $blog['id']; ?>, 'blog');">Delete</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
