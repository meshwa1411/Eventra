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
    if (isset($_POST['add_event'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $image = $_POST['image'];
        $event_date = $_POST['event_date'];
        $price = $_POST['price'];
        
        $stmt = $pdo->prepare("INSERT INTO events (title, description, image, event_date, price) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$title, $description, $image, $event_date, $price])) {
            $message = 'Event added successfully';
        }
    }
    
    if (isset($_POST['delete_event'])) {
        $event_id = $_POST['event_id'];
        $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
        if ($stmt->execute([$event_id])) {
            $message = 'Event deleted';
        }
    }
    
    if (isset($_POST['update_event'])) {
        $event_id = $_POST['edit_event_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $image = $_POST['image'];
        $event_date = $_POST['event_date'];
        $price = $_POST['price'];
        
        $stmt = $pdo->prepare("UPDATE events SET title = ?, description = ?, image = ?, event_date = ?, price = ? WHERE id = ?");
        if ($stmt->execute([$title, $description, $image, $event_date, $price, $event_id])) {
            $message = 'Event updated successfully';
        }
    }
}

// Fetch events
$events = $pdo->query("SELECT * FROM events ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Events - Eventra Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="admin-nav">
        <a href="dashboard.php">Dashboard</a> | <a href="events.php">Events</a> | <a href="bookings.php">Bookings</a> | <a href="logout.php">Logout</a>
    </nav>
    
    <div class="container">
        <h1>Manage Events</h1>
        <?php if ($message): ?><div class="alert success"><?php echo $message; ?></div><?php endif; ?>
        
        <!-- Add Event Form -->
        <div class="admin-form">
            <h3>Add New Event</h3>
            <form method="POST">
                <div class="form-group"><label>Title</label><input type="text" name="title" required></div>
                <div class="form-group"><label>Description</label><textarea name="description" required></textarea></div>
                <div class="form-group"><label>Image (filename)</label><input type="text" name="image" placeholder="event.jpg" required></div>
                <div class="form-group"><label>Date & Time</label><input type="datetime-local" name="event_date" required></div>
                <div class="form-group"><label>Price</label><input type="number" step="0.01" name="price" required></div>
                <button type="submit" name="add_event">Add Event</button>
            </form>
        </div>
        
        <!-- Events List -->
        <h3>Events List</h3>
        <table class="admin-table">
            <thead>
                <tr><th>ID</th><th>Title</th><th>Date</th><th>Price</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                <tr>
                    <td><?php echo $event['id']; ?></td>
                    <td><?php echo htmlspecialchars($event['title']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($event['event_date'])); ?></td>
                    <td>$<?php echo $event['price']; ?></td>
                    <td>
                        <a href=\"#edit-<?php echo $event['id']; ?>\" onclick=\"document.getElementById('edit-form-<?php echo $event['id']; ?>').style.display='block'\">Edit</a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                            <button type="submit" name="delete_event" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <!-- Edit Form -->
                <tr id=\"edit-form-<?php echo $event['id']; ?>\" style=\"display:none;\">
                    <td colspan=\"5\">
                        <form method=\"POST\">
                            <input type=\"hidden\" name=\"edit_event_id\" value=\"<?php echo $event['id']; ?>\">
                            <input type=\"text\" name=\"title\" value=\"<?php echo htmlspecialchars($event['title']); ?>\">
                            <textarea name=\"description\"><?php echo htmlspecialchars($event['description']); ?></textarea>
                            <input type=\"text\" name=\"image\" value=\"<?php echo htmlspecialchars($event['image']); ?>\">
                            <input type=\"datetime-local\" name=\"event_date\" value=\"<?php echo date('Y-m-d\\TH:i', strtotime($event['event_date'])); ?>\">
                            <input type=\"number\" name=\"price\" step=\"0.01\" value=\"<?php echo $event['price']; ?>\">
                            <button type=\"submit\" name=\"update_event\">Update</button>
                            <button type=\"button\" onclick=\"this.parentElement.parentElement.parentElement.style.display='none'\">Cancel</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

