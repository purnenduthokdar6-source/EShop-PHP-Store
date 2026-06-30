<?php
$page_title = 'Contact - EShop';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $body = trim($_POST['message'] ?? '');

    if ($name !== '' && $email !== '' && $body !== '') {
        $stmt = $conn->prepare('INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $name, $email, $body);
        $stmt->execute();
        $message = 'Thanks for contacting us. We will reply soon.';
    } else {
        $message = 'Please complete every field.';
    }
}
?>

<main class="section contact-layout">
    <div>
        <p class="eyebrow">Contact</p>
        <h1>Need help with an order?</h1>
        <p>Send a message and the EShop team will get back to you.</p>
        <div class="contact-details">
            <p><strong>Email:</strong> purnenduthokdar6@gmail.com</p>
            <p><strong>Phone:</strong> 9800868698</p>
            <p><strong>Address:</strong> Kolkata</p>
        </div>
    </div>

    <form class="form-panel" method="post">
        <?php if ($message): ?><p class="notice"><?php echo e($message); ?></p><?php endif; ?>
        <label>Name <input type="text" name="name" required></label>
        <label>Email <input type="email" name="email" required></label>
        <label>Message <textarea name="message" rows="6" required></textarea></label>
        <button class="button primary" type="submit">Send Message</button>
    </form>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
