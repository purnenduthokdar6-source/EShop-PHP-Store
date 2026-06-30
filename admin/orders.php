<?php
$base_path = '../';
$page_title = 'Orders - EShop';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';

if (!is_admin()) {
    redirect('login.php');
}

$orders = $conn->query('SELECT orders.*, users.name AS account_name FROM orders LEFT JOIN users ON orders.user_id = users.id ORDER BY orders.id DESC');
?>

<main class="section">
    <div class="section-heading">
        <p class="eyebrow">Admin</p>
        <h1>Orders</h1>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = $orders->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo (int) $order['id']; ?></td>
                    <td>
                        <?php echo e($order['customer_name']); ?><br>
                        <small><?php echo e($order['customer_email']); ?></small>
                    </td>
                    <td>₹<?php echo number_format((float) $order['total_amount'], 2); ?></td>
                    <td><span class="status-pill"><?php echo e($order['status']); ?></span></td>
                    <td><?php echo e(date('M d, Y', strtotime($order['created_at']))); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>