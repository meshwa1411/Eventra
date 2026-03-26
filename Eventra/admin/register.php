<?php
session_start();
include '../config/db.php';
include '../config/db_role_functions.php';

if (isAdmin($pdo)) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_POST) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'All fields required';
    } elseif (strlen($password) < 6) {
        $error = 'Password min 6 chars';
    } else {
        // Check email
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Email exists';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
            if ($stmt->execute([$name, $email, $hashed])) {
                $success = 'Admin created! <a href="login.php">Login</a>';
            } else {
                $error = 'Failed to create';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Register - Eventra</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="admin-nav">
        <a href="../index.php">Home</a> | <a href="login.php">Login</a>
    </nav>
    
    <div class="container" style="max-width: 500px; margin: 100px auto;">
        <h1>Admin Registration</h1>
        <?php if ($error): ?><div class="alert error"><?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert success"><?php echo $success; ?></div><?php endif; ?>
        
        <form method="POST" class="auth-form">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary">Create Admin</button>
        </form>
        <p>Existing? <a href="login.php">Login</a></p>
    </div>
</body>
</html>

