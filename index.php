<?php
$page_title = 'EShop - Modern Online Store';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';

$featured = $conn->query('SELECT * FROM products ORDER BY id DESC LIMIT 4');
?>

<main>
    <section class="hero">
        <div class="hero-content">
            <p class="eyebrow">Fresh picks every week</p>
            <h1>Shop smart essentials for work, home, and everyday life.</h1>
            <div class="hero-actions">
                <a class="button primary" href="products.php">Shop Products</a>
                <a class="button ghost" href="about.php">Learn More</a>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="section-heading">
            <p class="eyebrow">Featured</p>
            <h2>Latest Products</h2>
        </div>
        <div class="product-grid">
            <?php if ($featured && $featured->num_rows > 0): ?>
            <?php while ($product = $featured->fetch_assoc()): ?>
            <article class="product-card">
                <a href="product.php?id=<?php echo (int) $product['id']; ?>">
                    <img src="<?php echo e($product['image_url']); ?>" alt="<?php echo e($product['name']); ?>">
                </a>
                <div class="product-info">
                    <span><?php echo e($product['category']); ?></span>
                    <h3><?php echo e($product['name']); ?></h3>
                    <p class="price">₹<?php echo number_format((float) $product['price'], 2); ?></p>
                    <a class="button small" href="product.php?id=<?php echo (int) $product['id']; ?>">View Product</a>
                </div>
            </article>
            <?php endwhile; ?>
            <?php else: ?>
            <p class="empty-state">No products yet. Add your first product from the admin panel.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>