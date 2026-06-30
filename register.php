<?php
$page_title = 'Register - EShop';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || strlen($password) < 6) {
        $error = 'Please enter a name, valid email, and password with at least 6 characters.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $role = 'customer';
        $stmt = $conn->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $name, $email, $hash, $role);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_role'] = $role;
            redirect('index.php');
        }

        $error = 'That email is already registered.';
    }
}
?>

<main class="auth-page">
    <form class="form-panel auth-panel" method="post">
        <h1>Create Account</h1>
        <?php if ($error): ?><p class="error"><?php echo e($error); ?></p><?php endif; ?>
        <label>Name <input type="text" name="name" required></label>
        <label>Email <input type="email" name="email" required></label>
        <label>Password <input type="password" name="password" minlength="6" required></label>
        <button class="button primary full" type="submit">Register</button>
        <p>Already registered? <a href="login.php">Login</a>.</p>
    </form>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
