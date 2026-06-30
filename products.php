<?php
$page_title = 'Products - EShop';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';

$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');

$categories = $conn->query('SELECT DISTINCT category FROM products WHERE category <> "" ORDER BY category');
$sql = 'SELECT * FROM products WHERE 1';
$types = '';
$params = [];

if ($search !== '') {
    $sql .= ' AND (name LIKE ? OR description LIKE ?)';
    $like = '%' . $search . '%';
    $types .= 'ss';
    $params[] = $like;
    $params[] = $like;
}

if ($category !== '') {
    $sql .= ' AND category = ?';
    $types .= 's';
    $params[] = $category;
}

$sql .= ' ORDER BY id DESC';
$stmt = $conn->prepare($sql);

if ($params) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$products = $stmt->get_result();
?>

<main class="section">
    <div class="section-heading">
        <p class="eyebrow">Catalog</p>
        <h1>Products</h1>
    </div>

    <form class="filter-bar" method="get">
        <input type="search" name="search" placeholder="Search products" value="<?php echo e($search); ?>">
        <select name="category">
            <option value="">All categories</option>
            <?php while ($cat = $categories->fetch_assoc()): ?>
            <option value="<?php echo e($cat['category']); ?>"
                <?php echo $category === $cat['category'] ? 'selected' : ''; ?>>
                <?php echo e($cat['category']); ?>
            </option>
            <?php endwhile; ?>
        </select>
        <button class="button primary" type="submit">Filter</button>
    </form>

    <div class="product-grid">
        <?php if ($products->num_rows > 0): ?>
        <?php while ($product = $products->fetch_assoc()): ?>
        <article class="product-card">
            <a href="product.php?id=<?php echo (int) $product['id']; ?>">
                <img src="<?php echo e($product['image_url']); ?>" alt="<?php echo e($product['name']); ?>">
            </a>
            <div class="product-info">
                <span><?php echo e($product['category']); ?></span>
                <h2><?php echo e($product['name']); ?></h2>
                <p><?php echo e(substr($product['description'], 0, 95)); ?>...</p>
                <p class="price">₹<?php echo number_format((float) $product['price'], 2); ?></p>
                <a class="button small" href="product.php?id=<?php echo (int) $product['id']; ?>">View Details</a>
            </div>
        </article>
        <?php endwhile; ?>
        <?php else: ?>
        <p class="empty-state">No products matched your search.</p>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>