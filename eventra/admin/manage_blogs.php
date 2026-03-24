<?php 
session_start();

// Admin check
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../dashboard.php');
    exit;
}

$page_title = 'Manage Blogs - Admin';
include '../includes/db.php';
include '../includes/header.php';

$mode = $_GET['mode'] ?? 'list'; 
$blog_id = (int)($_GET['id'] ?? 0);
$blog = null;
$success = '';
$error = '';

// Handle delete
if (isset($_GET['delete']) && $blog_id > 0) {
    // Get image path to delete
    $stmt = $pdo->prepare("SELECT image FROM blogs WHERE id = ?");
    $stmt->execute([$blog_id]);
    $old_blog = $stmt->fetch();
    if ($old_blog && $old_blog['image'] && file_exists('../' . $old_blog['image'])) {
        unlink('../' . $old_blog['image']);
    }
    
    $stmt = $pdo->prepare("DELETE FROM blogs WHERE id = ?");
    $stmt->execute([$blog_id]);
    $success = 'Blog deleted successfully!';
}

// Handle edit data
if ($blog_id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM blogs WHERE id = ?");
    $stmt->execute([$blog_id]);
    $blog = $stmt->fetch();
    if (!$blog) $mode = 'list';
}

// Handle POST add/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    
    if (strlen($title) < 3 || strlen($content) < 20) {
        $error = 'Title min 3 chars, content min 20 chars.';
    } else {
        $image_path = $_POST['existing_image'] ?? null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../assets/images/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            $image_name = uniqid() . '_blog_' . basename($_FILES['image']['name']);
            $target = $upload_dir . $image_name;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $image_path = 'assets/images/' . $image_name;
                // Delete old
                if ($blog && $blog['image'] && file_exists('../' . $blog['image'])) {
                    unlink('../' . $blog['image']);
                }
            } else {
                $error = 'Image upload failed.';
            }
        }
        
        if (!$error) {
            if ($blog_id > 0) { // Update
                $stmt = $pdo->prepare("UPDATE blogs SET title=?, content=?, image=? WHERE id=?");
                $stmt->execute([$title, $content, $image_path, $blog_id]);
                $success = 'Blog updated!';
            } else { // Add
                $stmt = $pdo->prepare("INSERT INTO blogs (title, content, image) VALUES (?, ?, ?)");
                $stmt->execute([$title, $content, $image_path]);
                $success = 'Blog added!';
            }
            $mode = 'list';
        }
    }
}

// List all blogs
$stmt = $pdo->query("SELECT * FROM blogs ORDER BY created_at DESC");
$blogs = $stmt->fetchAll();
?>

<main class="section">
    <div class="container">
        <div style="margin-bottom: 2rem;">
            <a href="dashboard.php" class="btn btn-secondary">&larr; Admin Dashboard</a>
            <a href="add_blog.php" class="btn btn-success">Add New Blog</a>
        </div>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($mode === 'list'): ?>
            <div class="card">
                <h2>All Blog Posts (<?php echo count($blogs); ?>)</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Excerpt</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($blogs as $b): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(substr($b['title'], 0, 40)); ?></td>
                                <td><?php echo htmlspecialchars(substr(strip_tags($b['content']), 0, 50)) . '...'; ?></td>
                                <td><?php echo date('M j, Y', strtotime($b['created_at'])); ?></td>
                                <td>
                                    <a href="?mode=edit&id=<?php echo $b['id']; ?>" class="btn btn-primary" style="padding: 0.5rem;">Edit</a>
                                    <a href="?delete=<?php echo $b['id']; ?>" 
                                       class="btn btn-danger" 
                                       onclick="return EventraUtils.confirmDelete(<?php echo $b['id']; ?>, 'blog');"
                                       style="padding: 0.5rem;">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
        <?php elseif ($mode === 'edit' && $blog): ?>
            <div class="form-container" style="max-width: 800px;">
                <h2>Edit Blog: <?php echo htmlspecialchars($blog['title']); ?></h2>
                <a href="?mode=list" class="btn btn-secondary" style="margin-bottom: 1rem;">&larr; Back to List</a>
                
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($blog['image'] ?? ''); ?>">
                    
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($blog['title']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea name="content" rows="15" required style="resize: vertical; font-family: inherit;"><?php echo htmlspecialchars($blog['content']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Image (current: <?php echo htmlspecialchars($blog['image'] ?: 'None'); ?>)</label>
                        <input type="file" name="image" accept="image/*">
                        <?php if ($blog['image']): ?>
                            <img src="../../<?php echo htmlspecialchars($blog['image']); ?>" style="max-width: 200px; margin-top: 1rem;" alt="Current">
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Update Blog</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
EventraUtils.previewImage('image', 'image-preview');
</script>

<?php include '../includes/footer.php'; ?>
