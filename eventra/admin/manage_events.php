<?php 
session_start();

// Admin check
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../dashboard.php');
    exit;
}

$page_title = 'Manage Events - Admin';
include '../includes/db.php';
include '../includes/header.php';

$mode = $_GET['mode'] ?? 'list'; // list, edit
$event_id = (int)($_GET['id'] ?? 0);
$event = null;
$success = '';
$error = '';

// Handle delete
if (isset($_GET['delete']) && $event_id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
        $stmt->execute([$event_id]);
        $success = 'Event deleted successfully!';
    } catch (PDOException $e) {
        $error = 'Delete failed.';
    }
}

// Handle edit form data
if ($event_id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$event_id]);
    $event = $stmt->fetch();
    if (!$event) $mode = 'list';
}

// Handle POST (add or update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $event_date = $_POST['event_date'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    
    if (strlen($title) < 3 || strlen($description) < 10 || empty($event_date) || empty($location)) {
        $error = 'Please fill all required fields properly.';
    } elseif (strtotime($event_date) === false) {
        $error = 'Invalid event date.';
    } else {
        $image_path = $_POST['existing_image'] ?? null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../assets/images/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            $image_name = uniqid() . '_' . basename($_FILES['image']['name']);
            $target = $upload_dir . $image_name;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $image_path = 'assets/images/' . $image_name;
                // Delete old image if editing
                if ($event && $event['image'] && file_exists('../' . $event['image'])) {
                    unlink('../' . $event['image']);
                }
            } else {
                $error = 'Image upload failed.';
            }
        }
        
        if (!$error) {
            if ($event_id > 0) { // Update
                $stmt = $pdo->prepare("UPDATE events SET title=?, description=?, event_date=?, location=?, price=?, image=? WHERE id=?");
                $stmt->execute([$title, $description, $event_date, $location, $price, $image_path, $event_id]);
                $success = 'Event updated!';
            } else { // Add new
                $stmt = $pdo->prepare("INSERT INTO events (title, description, event_date, location, price, image) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $description, $event_date, $location, $price, $image_path]);
                $success = 'Event added!';
            }
            $mode = 'list'; // Back to list
        }
    }
}

// Fetch all events for list
$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC");
$events = $stmt->fetchAll();
?>

<main class="section">
    <div class="container">
        <div style="margin-bottom: 2rem;">
            <a href="dashboard.php" class="btn btn-secondary">&larr; Admin Dashboard</a>
            <a href="add_event.php" class="btn btn-success">Add New Event</a>
        </div>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($mode === 'list'): ?>
            <div class="card">
                <h2>All Events (<?php echo count($events); ?>)</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Price</th>
                            <th>Bookings</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $e): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(substr($e['title'], 0, 30)); ?></td>
                                <td><?php echo date('M j', strtotime($e['event_date'])); ?></td>
                                <td><?php echo htmlspecialchars(substr($e['location'], 0, 20)); ?></td>
                                <td>$<?php echo number_format($e['price'], 2); ?></td>
                                <td>
                                    <?php 
                                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE event_id = ?");
                                    $stmt->execute([$e['id']]);
                                    echo $stmt->fetchColumn();
                                    ?>
                                </td>
                                <td>
                                    <a href="?mode=edit&id=<?php echo $e['id']; ?>" class="btn btn-primary" style="padding: 0.5rem;">Edit</a>
                                    <a href="?delete=<?php echo $e['id']; ?>" 
                                       class="btn btn-danger" 
                                       onclick="return EventraUtils.confirmDelete(<?php echo $e['id']; ?>, 'event');"
                                       style="padding: 0.5rem;">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
        <?php elseif ($mode === 'edit' && $event): ?>
            <div class="form-container" style="max-width: 700px;">
                <h2>Edit Event: <?php echo htmlspecialchars($event['title']); ?></h2>
                <a href="?mode=list" class="btn btn-secondary" style="margin-bottom: 1rem;">&larr; Back to List</a>
                
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($event['image'] ?? ''); ?>">
                    
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" rows="5" required><?php echo htmlspecialchars($event['description']); ?></textarea>
                    </div>
                    
                    <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label for="event_date">Date & Time</label>
                            <input type="datetime-local" name="event_date" value="<?php echo date('Y-m-d\TH:i', strtotime($event['event_date'])); ?>" required>
                        </div>
                        <div>
                            <label for="price">Price</label>
                            <input type="number" name="price" step="0.01" value="<?php echo $event['price']; ?>" min="0">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Image (current: <?php echo htmlspecialchars($event['image'] ?: 'None'); ?>)</label>
                        <input type="file" name="image" accept="image/*">
                        <?php if ($event['image']): ?>
                            <img src="../../<?php echo htmlspecialchars($event['image']); ?>" style="max-width: 200px; margin-top: 1rem;" alt="Current">
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Update Event</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
EventraUtils.previewImage('image', 'image-preview');
</script>

<?php include '../includes/footer.php'; ?>
