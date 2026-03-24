<?php 
session_start();

// Admin check
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../dashboard.php');
    exit;
}

$page_title = 'Add Event - Admin';
include '../includes/db.php';
include '../includes/header.php';

$success = '';
$error = '';

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
        $image_path = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../assets/images/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $image_name = uniqid() . '_' . basename($_FILES['image']['name']);
            $image_path = $upload_dir . $image_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                $image_path = 'assets/images/' . $image_name; // relative path for DB
            } else {
                $error = 'Failed to upload image.';
            }
        }
        
        if (!$error) {
            try {
                $stmt = $pdo->prepare("INSERT INTO events (title, description, event_date, location, price, image) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $description, $event_date, $location, $price, $image_path]);
                $success = 'Event added successfully!';
            } catch (PDOException $e) {
                $error = 'Failed to add event.';
            }
        }
    }
}
?>

<main class="section">
    <div class="container">
        <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
            <a href="dashboard.php" class="btn btn-secondary">&larr; Admin Dashboard</a>
            <a href="manage_events.php" class="btn btn-primary">View Events</a>
        </div>
        
        <div class="form-container">
            <h2>Add New Event</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?> <a href="add_event.php">Add Another</a></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" id="addEventForm">
                <div class="form-group">
                    <label for="title">Event Title *</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label for="event_date">Event Date & Time *</label>
                        <input type="datetime-local" id="event_date" name="event_date" value="<?php echo $_POST['event_date'] ?? ''; ?>" required>
                    </div>
                    <div>
                        <label for="price">Price ($)</label>
                        <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>" min="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="location">Location *</label>
                    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="image">Event Image</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <img id="image-preview" style="max-width: 200px; max-height: 150px; margin-top: 1rem; display: none; border-radius: 10px;">
                </div>
                
                <button type="submit" class="btn btn-success" style="width: 100%;">Add Event</button>
            </form>
        </div>
    </div>
</main>

<script>
EventraUtils.previewImage('image', 'image-preview');
</script>

<?php include '../includes/footer.php'; ?>
