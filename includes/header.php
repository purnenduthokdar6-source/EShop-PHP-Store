<?php
require_once __DIR__ . '/../config/db.php';
$page_title = $page_title ?? 'EShop';
$base_path = $base_path ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($page_title); ?></title>
    <link rel="stylesheet" href="<?php echo e($base_path); ?>css/style.css">
</head>
<body>
