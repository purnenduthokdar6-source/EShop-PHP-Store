<?php
require_once __DIR__ . '/config/db.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $conn->prepare('SELECT * FROM products WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    redirect('products.php');
}

$page_title = $product['name'] . ' - EShop';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>

<main class="section">
    <div class="product-detail">
        <img src="<?php echo e($product['image_url']); ?>" alt="<?php echo e($product['name']); ?>">
        <div>
            <p class="eyebrow"><?php echo e($product['category']); ?></p>
            <h1><?php echo e($product['name']); ?></h1>
            <p class="price">₹<?php echo number_format((float) $product['price'], 2); ?></p>
            <p><?php echo nl2br(e($product['description'])); ?></p>
            <p class="stock"><?php echo (int) $product['stock']; ?> in stock</p>

            <form class="cart-form" method="post" action="add_cart.php">
                <input type="hidden" name="product_id" value="<?php echo (int) $product['id']; ?>">
                <label>
                    Quantity
                    <input type="number" name="quantity" value="1" min="1"
                        max="<?php echo max(1, (int) $product['stock']); ?>">
                </label>
                <button class="button primary" type="submit"
                    <?php echo (int) $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                    Add to Cart
                </button>
            </form>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>