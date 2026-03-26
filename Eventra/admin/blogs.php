<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

include '../config/db.php';

// Handle CRUD
$message = '';

if ($_POST) {
    if (isset($_POST['add_blog'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $image = $_POST['image'];
        
        $stmt = $pdo->prepare("INSERT INTO blogs (title, content, image) VALUES (?, ?, ?)");
        if ($stmt->execute([$title, $content, $image])) {
            $message = 'Blog added successfully';
        }
    }
    
    if (isset($_POST['delete_blog'])) {
        $blog_id = $_POST['blog_id'];
        $stmt = $pdo->prepare("DELETE FROM blogs WHERE id = ?");
        if ($stmt->execute([$blog_id])) {
            $message = 'Blog deleted';
        }
    }
}

// Fetch blogs
$blogs = $pdo->query("SELECT * FROM blogs ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Blogs - Eventra Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
    .admin-form textarea { height: 150px; }
    </style>
</head>
<body>
    <nav class="admin-nav">
        <a href="dashboard.php">Dashboard</a> | 
        <a href="events.php">Events</a> | 
        <a href="blogs.php">Blogs</a> | 
        <a href="bookings.php">Bookings</a> | 
        <a href="logout.php">Logout</a>
    </nav>
    
    <div class="container">
        <h1>Manage Blogs</h1>
        <?php if ($message): ?><div class="alert success"><?php echo $message; ?></div><?php endif; ?>
        
        <!-- Add Blog Form -->
        <div class="admin-form">
            <h3>Add New Blog</h3>
            <form method="POST">
                <div class="form-group"><label>Title</label><input type="text" name="title" required></div>
                <div class="form-group"><label>Content</label><textarea name="content" required></textarea></div>
                <div class="form-group"><label>Image (filename)</label><input type="text" name="image" placeholder="blog1.jpg" required></div>
                <button type="submit" name="add_blog">Add Blog</button>
            </form>
        </div>
        
        <!-- Blogs List -->
        <h3>Blogs List</h3>
        <table class="admin-table">
            <thead>
                <tr><th>ID</th><th>Title</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($blogs as $blog): ?>
                <tr>
                    <td><?php echo $blog['id']; ?></td>
                    <td><?php echo htmlspecialchars(substr($blog['title'], 0, 30)); ?>...</td>
                    <td><?php echo date('M d, Y', strtotime($blog['created_at'])); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="blog_id" value="<?php echo $blog['id']; ?>">
                            <button type="submit" name="delete_blog" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

