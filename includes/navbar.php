<?php $base_path = $base_path ?? ''; ?>
<header class="site-header">
    <a class="brand" href="<?php echo e($base_path); ?>index.php">EShop</a>
    <button class="nav-toggle" type="button" aria-label="Toggle navigation">Menu</button>
    <nav class="site-nav">
        <a href="<?php echo e($base_path); ?>index.php">Home</a>
        <a href="<?php echo e($base_path); ?>products.php">Products</a>
        <a href="<?php echo e($base_path); ?>about.php">About</a>
        <a href="<?php echo e($base_path); ?>contact.php">Contact</a>
        <a href="<?php echo e($base_path); ?>cart.php">Cart (<?php echo cart_count(); ?>)</a>
        <?php if (is_logged_in()): ?>
            <?php if (is_admin()): ?>
                <a href="<?php echo e($base_path); ?>admin/dashboard.php">Admin</a>
            <?php endif; ?>
            <a href="<?php echo e($base_path); ?>logout.php">Logout</a>
        <?php else: ?>
            <a href="<?php echo e($base_path); ?>login.php">Login</a>
            <a class="nav-cta" href="<?php echo e($base_path); ?>register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>
