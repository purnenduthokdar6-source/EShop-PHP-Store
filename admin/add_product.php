<?php
$base_path = '../';
$page_title = 'Add Product - EShop';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';

if (!is_admin()) {
    redirect('login.php');
}

$editing = false;
$message = '';
$product = [
    'id' => 0,
    'name' => '',
    'description' => '',
    'category' => '',
    'price' => '',
    'stock' => '',
    'image_url' => ''
];

if (isset($_GET['id'])) {
    $editing = true;
    $id = (int) $_GET['id'];
    $stmt = $conn->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if (!$product) {
        redirect('products.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);
    $stock = (int) ($_POST['stock'] ?? 0);
    $image_url = trim($_POST['image_url'] ?? '');

    if ($name === '' || $description === '' || $category === '' || $price <= 0 || $image_url === '') {
        $message = 'Please complete all product fields.';
    } elseif ($id > 0) {
        $stmt = $conn->prepare('UPDATE products SET name = ?, description = ?, category = ?, price = ?, stock = ?, image_url = ? WHERE id = ?');
        $stmt->bind_param('sssdisi', $name, $description, $category, $price, $stock, $image_url, $id);
        $stmt->execute();
        redirect('products.php');
    } else {
        $stmt = $conn->prepare('INSERT INTO products (name, description, category, price, stock, image_url) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('sssdis', $name, $description, $category, $price, $stock, $image_url);
        $stmt->execute();
        redirect('products.php');
    }
}
?>

<main class="section">
    <div class="section-heading">
        <p class="eyebrow">Admin</p>
        <h1><?php echo $editing ? 'Edit Product' : 'Add Product'; ?></h1>
    </div>

    <form class="form-panel wide-form" method="post">
        <?php if ($message): ?><p class="error"><?php echo e($message); ?></p><?php endif; ?>
        <input type="hidden" name="id" value="<?php echo (int) ($product['id'] ?? 0); ?>">
        <label>Name <input type="text" name="name" value="<?php echo e($product['name']); ?>" required></label>
        <label>Category <input type="text" name="category" value="<?php echo e($product['category']); ?>" required></label>
        <div class="form-row">
            <label>Price <input type="number" name="price" min="0.01" step="0.01" value="<?php echo e($product['price']); ?>" required></label>
            <label>Stock <input type="number" name="stock" min="0" value="<?php echo e($product['stock']); ?>" required></label>
        </div>
        <label>Image URL <input type="url" name="image_url" value="<?php echo e($product['image_url']); ?>" required></label>
        <label>Description <textarea name="description" rows="7" required><?php echo e($product['description']); ?></textarea></label>
        <button class="button primary" type="submit"><?php echo $editing ? 'Update Product' : 'Create Product'; ?></button>
    </form>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
