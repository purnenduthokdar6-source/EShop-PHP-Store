<?php
if (ob_get_level() === 0) {
    ob_start();
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db_host = getenv('DB_HOST') ?: 'localhost';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: '';
$db_name = getenv('DB_NAME') ?: 'eshop';

$local_config = __DIR__ . '/db.local.php';
if (is_file($local_config)) {
    $database = require $local_config;

    $db_host = $database['host'] ?? $db_host;
    $db_user = $database['user'] ?? $db_user;
    $db_pass = $database['pass'] ?? $db_pass;
    $db_name = $database['name'] ?? $db_name;
}

mysqli_report(MYSQLI_REPORT_OFF);
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    http_response_code(500);
    $has_local_config = is_file($local_config);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Database Setup Needed</title>
        <style>
            body { margin: 0; font-family: Arial, sans-serif; background: #f6f7fb; color: #18202f; }
            main { max-width: 760px; margin: 8vh auto; padding: 32px; background: #fff; border: 1px solid #dfe3ec; border-radius: 8px; }
            h1 { margin-top: 0; }
            code { background: #eef1f7; padding: 2px 6px; border-radius: 4px; }
            a { color: #0f62fe; }
            .error { color: #9f1239; }
        </style>
    </head>
    <body>
        <main>
            <h1>Database setup needed</h1>
            <p class="error">Connection failed: <?php echo htmlspecialchars($conn->connect_error, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php if (!$has_local_config): ?>
                <p>Create your database configuration first. Visit <a href="setup.php">setup.php</a>, enter your hosting MySQL details, and import the database.</p>
            <?php else: ?>
                <p>Your <code>config/db.local.php</code> file exists, but the host, database name, username, or password is incorrect.</p>
            <?php endif; ?>
            <p>On InfinityFree, use the MySQL host shown in the control panel, not <code>localhost</code>.</p>
        </main>
    </body>
    </html>
    <?php
    exit;
}

$conn->set_charset('utf8mb4');

if (!function_exists('e')) {
    function e($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('redirect')) {
    function redirect($path)
    {
        header('Location: ' . $path);
        exit;
    }
}

if (!function_exists('is_logged_in')) {
    function is_logged_in()
    {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('is_admin')) {
    function is_admin()
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
}

if (!function_exists('cart_count')) {
    function cart_count()
    {
        $count = 0;

        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $qty) {
                $count += (int) $qty;
            }
        }

        return $count;
    }
}
?>
