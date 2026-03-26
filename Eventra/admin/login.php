<?php
session_start();
include '../config/db.php';

include 'db_role_functions.php';
if (isAdmin($pdo)) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT id FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin && password_verify($password, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')) {  // admin123
        $_SESSION['admin_id'] = $admin['id'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid credentials. Use admin/admin123';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Eventra</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>.admin-login { max-width: 400px; margin: 100px auto; padding: 2rem; }</style>
</head>
<body>
    <div class="container admin-login">
        <h2>Admin Login</h2>
        <?php if ($error): ?><div class="alert error"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="admin" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>

