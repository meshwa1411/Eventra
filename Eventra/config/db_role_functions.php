<?php
// Role helper functions
function isAdmin($pdo) {
    session_start();
    if (!isset($_SESSION['user_id'])) return false;
    
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user && $user['role'] === 'admin';
}

function requireAdmin($pdo) {
    if (!isAdmin($pdo)) {
        header('Location: ../login.php?unauthorized=1');
        exit;
    }
}

function getUserRole($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user ? $user['role'] : 'user';
}
?>

