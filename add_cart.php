<?php
require_once __DIR__ . '/config/db.php';

$product_id = (int) ($_POST['product_id'] ?? 0);
$quantity = max(1, (int) ($_POST['quantity'] ?? 1));

$stmt = $conn->prepare('SELECT id, stock FROM products WHERE id = ?');
$stmt->bind_param('i', $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if ($product && (int) $product['stock'] > 0) {
    $_SESSION['cart'][$product_id] = min((int) $product['stock'], ($_SESSION['cart'][$product_id] ?? 0) + $quantity);
}

redirect('cart.php');
?>
