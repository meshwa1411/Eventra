<?php
// Database configuration
$host = 'localhost';
$dbname = 'eventra';
$username = 'root';  // Default XAMPP MySQL user
$password = '';      // Default XAMPP MySQL password (empty)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to check if user has booked an event
function isEventBooked($pdo, $user_id, $event_id) {
    $stmt = $pdo->prepare("SELECT id FROM bookings WHERE user_id = ? AND event_id = ? AND payment_status = 'completed'");
    $stmt->execute([$user_id, $event_id]);
    return $stmt->fetch() !== false;
}
?>

