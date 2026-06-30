<?php
require_once __DIR__ . '/config/db.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0 && isset($_SESSION['cart'][$id])) {
    unset($_SESSION['cart'][$id]);
}

redirect('cart.php');
?>
