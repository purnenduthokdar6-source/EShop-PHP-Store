<?php
$page_title = 'Checkout - EShop';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';

if (!is_logged_in()) {
    redirect('login.php');
}

$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0;
$message = '';

if ($cart) {
    $ids = array_map('intval', array_keys($cart));
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));
    $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($product = $result->fetch_assoc()) {
        $qty = min((int) $cart[$product['id']], (int) $product['stock']);
        if ($qty < 1) {
            continue;
        }
        $subtotal = (float) $product['price'] * $qty;
        $total += $subtotal;
        $items[] = ['product' => $product, 'qty' => $qty, 'subtotal' => $subtotal];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $items) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if ($name === '' || $email === '' || $address === '') {
        $message = 'Please complete all checkout fields.';
    } else {
        $conn->begin_transaction();

        try {
            $status = 'pending';
            $user_id = (int) $_SESSION['user_id'];
            $stmt = $conn->prepare('INSERT INTO orders (user_id, customer_name, customer_email, shipping_address, total_amount, status) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->bind_param('isssds', $user_id, $name, $email, $address, $total, $status);
            $stmt->execute();
            $order_id = $stmt->insert_id;

            foreach ($items as $item) {
                $product_id = (int) $item['product']['id'];
                $qty = (int) $item['qty'];
                $price = (float) $item['product']['price'];
                $line_total = (float) $item['subtotal'];

                $line = $conn->prepare('INSERT INTO order_items (order_id, product_id, quantity, price, line_total) VALUES (?, ?, ?, ?, ?)');
                $line->bind_param('iiidd', $order_id, $product_id, $qty, $price, $line_total);
                $line->execute();

                $stock = $conn->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');
                $stock->bind_param('ii', $qty, $product_id);
                $stock->execute();
            }

            $conn->commit();
            $_SESSION['cart'] = [];
            $message = 'Order placed successfully. Your order number is #' . $order_id . '.';
            $items = [];
            $total = 0;
        } catch (Throwable $error) {
            $conn->rollback();
            $message = 'Could not place the order. Please try again.';
        }
    }
}
?>

<main class="section">
    <div class="section-heading">
        <p class="eyebrow">Secure</p>
        <h1>Checkout</h1>
    </div>

    <?php if ($message): ?>
    <p class="notice"><?php echo e($message); ?></p>
    <?php endif; ?>

    <?php if ($items): ?>
    <div class="checkout-layout">
        <form class="form-panel" method="post">
            <label>Name <input type="text" name="name" value="<?php echo e($_SESSION['user_name'] ?? ''); ?>"
                    required></label>
            <label>Email <input type="email" name="email" required></label>
            <label>Shipping Address <textarea name="address" rows="5" required></textarea></label>
            <button class="button primary" type="submit">Place Order</button>
        </form>

        <aside class="summary-box">
            <h2>Total</h2>
            <strong class="summary-total">₹<?php echo number_format($total, 2); ?></strong>
        </aside>
    </div>
    <?php elseif (!$message): ?>
    <p class="empty-state">Your cart is empty.</p>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>