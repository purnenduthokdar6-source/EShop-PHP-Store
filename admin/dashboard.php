<?php
$base_path = '../';
$page_title = 'Admin Dashboard - EShop';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';

if (!is_admin()) {
    redirect('login.php');
}

$product_count = $conn->query('SELECT COUNT(*) AS total FROM products')->fetch_assoc()['total'];
$order_count = $conn->query('SELECT COUNT(*) AS total FROM orders')->fetch_assoc()['total'];
$revenue = $conn->query('SELECT COALESCE(SUM(total_amount), 0) AS total FROM orders')->fetch_assoc()['total'];
$low_stock = $conn->query('SELECT COUNT(*) AS total FROM products WHERE stock < 5')->fetch_assoc()['total'];
?>

<main class="section">
    <div class="admin-heading">
        <div>
            <p class="eyebrow">Admin</p>
            <h1>Dashboard</h1>
        </div>
        <a class="button primary" href="add_product.php">Add Product</a>
    </div>

    <div class="stats-grid">
        <div class="stat-card"><span>Products</span><strong><?php echo (int) $product_count; ?></strong></div>
        <div class="stat-card"><span>Orders</span><strong><?php echo (int) $order_count; ?></strong></div>
        <div class="stat-card"><span>Revenue</span><strong>₹<?php echo number_format((float) $revenue, 2); ?></strong>
        </div>
        <div class="stat-card"><span>Low Stock</span><strong><?php echo (int) $low_stock; ?></strong></div>
    </div>

    <div class="admin-links">
        <a href="products.php">Manage Products</a>
        <a href="orders.php">View Orders</a>
        <a href="../index.php">Visit Store</a>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>