<?php 
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$page_title = 'Login - Eventra';
include 'includes/db.php';
include 'includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($password) >= 6) {
        try {
$stmt = $pdo->prepare("SELECT id, name, email, password, is_admin FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['is_admin'] = (bool)$user['is_admin'];

    header('Location: dashboard.php');
    exit;
} else {
    $error = 'Invalid email or password.';
}
        } catch (PDOException $e) {
            $error = 'Login failed. Try again.';
        }
    } else {
        $error = 'Please check your email and password.';
    }
}
?>

<main class="section">
    <div class="container">
        <div class="form-container">
            <h2 style="text-align: center; margin-bottom: 2rem; color: #00d4ff;">Welcome Back</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" id="loginForm">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
            </form>
            
            <p style="text-align: center; margin-top: 1rem;">
                No account? <a href="register.php">Register here</a>
            </p>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
