<?php
$base_path = '../';
$page_title = 'Manage Products - EShop';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';

if (!is_admin()) {
    redirect('login.php');
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $conn->prepare('DELETE FROM products WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    redirect('products.php');
}

$products = $conn->query('SELECT * FROM products ORDER BY id DESC');
?>

<main class="section">
    <div class="admin-heading">
        <div>
            <p class="eyebrow">Admin</p>
            <h1>Products</h1>
        </div>
        <a class="button primary" href="add_product.php">Add Product</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = $products->fetch_assoc()): ?>
                <tr>
                    <td class="cart-product">
                        <img src="<?php echo e($product['image_url']); ?>" alt="<?php echo e($product['name']); ?>">
                        <span><?php echo e($product['name']); ?></span>
                    </td>
                    <td><?php echo e($product['category']); ?></td>
                    <td>₹<?php echo number_format((float) $product['price'], 2); ?></td>
                    <td><?php echo (int) $product['stock']; ?></td>
                    <td>
                        <a href="add_product.php?id=<?php echo (int) $product['id']; ?>">Edit</a>
                        <a class="link-danger" href="products.php?delete=<?php echo (int) $product['id']; ?>"
                            data-confirm="Delete this product?">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>