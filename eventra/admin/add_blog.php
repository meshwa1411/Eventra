<?php 
session_start();

// Admin check
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../dashboard.php');
    exit;
}

$page_title = 'Add Blog - Admin';
include '../includes/db.php';
include '../includes/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    
    if (strlen($title) < 3 || strlen($content) < 20) {
        $error = 'Title min 3 chars, content min 20 chars.';
    } else {
        $image_path = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../assets/images/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $image_name = uniqid() . '_blog_' . basename($_FILES['image']['name']);
            $image_path = $upload_dir . $image_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                $image_path = 'assets/images/' . $image_name;
            } else {
                $error = 'Failed to upload image.';
            }
        }
        
        if (!$error) {
            try {
                $stmt = $pdo->prepare("INSERT INTO blogs (title, content, image) VALUES (?, ?, ?)");
                $stmt->execute([$title, $content, $image_path]);
                $success = 'Blog post added successfully!';
            } catch (PDOException $e) {
                $error = 'Failed to add blog.';
            }
        }
    }
}
?>

<main class="section">
    <div class="container">
        <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
            <a href="dashboard.php" class="btn btn-secondary">&larr; Admin Dashboard</a>
            <a href="manage_blogs.php" class="btn btn-primary">View Blogs</a>
        </div>
        
        <div class="form-container">
            <h2>Add New Blog Post</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?> <a href="add_blog.php">Add Another</a></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" id="addBlogForm">
                <div class="form-group">
                    <label for="title">Blog Title *</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="content">Content *</label>
                    <textarea id="content" name="content" rows="12" required style="resize: vertical; font-family: inherit;"><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="image">Featured Image</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <img id="image-preview" style="max-width: 200px; max-height: 150px; margin-top: 1rem; display: none; border-radius: 10px;">
                </div>
                
                <button type="submit" class="btn btn-success" style="width: 100%;">Publish Blog</button>
            </form>
        </div>
    </div>
</main>

<script>
EventraUtils.previewImage('image', 'image-preview');
</script>

<?php include '../includes/footer.php'; ?>
