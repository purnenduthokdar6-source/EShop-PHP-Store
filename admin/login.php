<?php
$base_path = '../';
$page_title = 'Admin Login - EShop';
include __DIR__ . '/../includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare('SELECT * FROM users WHERE email = ? AND role = "admin" LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['user_id'] = (int) $admin['id'];
        $_SESSION['user_name'] = $admin['name'];
        $_SESSION['user_role'] = $admin['role'];
        redirect('dashboard.php');
    }

    $error = 'Invalid admin credentials.';
}
?>

<main class="auth-page">
    <form class="form-panel auth-panel" method="post">
        <h1>Admin Login</h1>
        <?php if ($error): ?><p class="error"><?php echo e($error); ?></p><?php endif; ?>
        <label>Email <input type="email" name="email" required></label>
        <label>Password <input type="password" name="password" required></label>
        <button class="button primary full" type="submit">Login</button>
        <p><a href="../index.php">Back to store</a></p>
    </form>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
