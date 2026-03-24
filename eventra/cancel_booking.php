<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = (int)($_POST['booking_id']);
    
    // Verify booking belongs to user
    $stmt = $pdo->prepare("SELECT b.*, e.event_date FROM bookings b JOIN events e ON b.event_id = e.id WHERE b.id = ? AND b.user_id = ?");
    $stmt->execute([$booking_id, $_SESSION['user_id']]);
    $booking = $stmt->fetch();
    
    if (!$booking) {
        $_SESSION['error'] = 'Booking not found.';
        header('Location: dashboard.php');
        exit;
    }
    
    // Check if already cancelled
    if ($booking['booking_status'] === 'cancelled') {
        $_SESSION['error'] = 'Booking already cancelled.';
        header('Location: dashboard.php');
        exit;
    }
    
    // Check cancellation window (24 hours before event)
    $event_time = strtotime($booking['event_date']);
    $twenty_four_hours_before = $event_time - (24 * 60 * 60);
    
    if (time() > $twenty_four_hours_before) {
        $_SESSION['error'] = 'Cannot cancel. Less than 24 hours before event.';
        header('Location: dashboard.php');
        exit;
    }
    
    try {
        // Update booking status
        $stmt = $pdo->prepare("UPDATE bookings SET booking_status = 'cancelled', refund_status = 'pending', cancelled_at = NOW() WHERE id = ?");
        $stmt->execute([$booking_id]);
        
        // Log refund (full amount)
        $pdo->prepare("UPDATE bookings SET refund_amount = e.price FROM events e WHERE bookings.id = ? AND bookings.event_id = e.id")
            ->execute([$booking_id]);
        
        $_SESSION['success'] = 'Booking cancelled successfully! Refund initiated (Pending).';
    } catch (Exception $e) {
        $_SESSION['error'] = 'Cancellation failed. Try again.';
    }
}

header('Location: dashboard.php');
exit;
?>

