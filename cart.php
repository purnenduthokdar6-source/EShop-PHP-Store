<?php
$page_title = 'Cart - EShop';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';

$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0;

if ($cart) {
    $ids = array_map('intval', array_keys($cart));
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));
    $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($product = $result->fetch_assoc()) {
        $qty = (int) $cart[$product['id']];
        $subtotal = (float) $product['price'] * $qty;
        $total += $subtotal;
        $items[] = ['product' => $product, 'qty' => $qty, 'subtotal' => $subtotal];
    }
}
?>

<main class="section">
    <div class="section-heading">
        <p class="eyebrow">Review</p>
        <h1>Your Cart</h1>
    </div>

    <?php if ($items): ?>
    <div class="cart-layout">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="cart-product">
                            <img src="<?php echo e($item['product']['image_url']); ?>"
                                alt="<?php echo e($item['product']['name']); ?>">
                            <span><?php echo e($item['product']['name']); ?></span>
                        </td>
                        <td>₹<?php echo number_format((float) $item['product']['price'], 2); ?></td>
                        <td><?php echo (int) $item['qty']; ?></td>
                        <td>₹<?php echo number_format($item['subtotal'], 2); ?></td>
                        <td><a class="link-danger"
                                href="remove_cart.php?id=<?php echo (int) $item['product']['id']; ?>">Remove</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <aside class="summary-box">
            <h2>Order Summary</h2>
            <div class="summary-row">
                <span>Total</span>
                <strong>₹<?php echo number_format($total, 2); ?></strong>
            </div>
            <a class="button primary full" href="checkout.php">Checkout</a>
        </aside>
    </div>
    <?php else: ?>
    <p class="empty-state">Your cart is empty. <a href="products.php">Start shopping</a>.</p>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>