<?php
$page_title = 'Login - EShop';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        redirect('index.php');
    }

    $error = 'Invalid email or password.';
}
?>

<main class="auth-page">
    <form class="form-panel auth-panel" method="post">
        <h1>Login</h1>
        <?php if ($error): ?><p class="error"><?php echo e($error); ?></p><?php endif; ?>
        <label>Email <input type="email" name="email" required></label>
        <label>Password <input type="password" name="password" required></label>
        <button class="button primary full" type="submit">Login</button>
        <p>New customer? <a href="register.php">Create an account</a>.</p>
    </form>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
