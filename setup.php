<?php
$config_path = __DIR__ . '/config/db.local.php';
$sql_path = __DIR__ . '/database/shop_import.sql';
$configured = is_file($config_path);
$message = '';
$error = '';

function setup_e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$configured) {
    $host = trim($_POST['host'] ?? '');
    $user = trim($_POST['user'] ?? '');
    $pass = $_POST['pass'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $import = isset($_POST['import']);

    if ($host === '' || $user === '' || $name === '') {
        $error = 'Please enter the MySQL host, username, and database name.';
    } else {
        mysqli_report(MYSQLI_REPORT_OFF);
        $conn = @new mysqli($host, $user, $pass, $name);

        if ($conn->connect_error) {
            $error = 'Database connection failed: ' . $conn->connect_error;
        } else {
            $conn->set_charset('utf8mb4');

            if ($import) {
                if (!is_file($sql_path)) {
                    $error = 'Could not find database/shop_import.sql.';
                } else {
                    $sql = file_get_contents($sql_path);

                    if (!$conn->multi_query($sql)) {
                        $error = 'Database import failed: ' . $conn->error;
                    } else {
                        do {
                            if ($result = $conn->store_result()) {
                                $result->free();
                            }
                        } while ($conn->more_results() && $conn->next_result());

                        if ($conn->errno) {
                            $error = 'Database import failed: ' . $conn->error;
                        }
                    }
                }
            }

            if ($error === '') {
                $config = "<?php\nreturn " . var_export([
                    'host' => $host,
                    'user' => $user,
                    'pass' => $pass,
                    'name' => $name,
                ], true) . ";\n";

                if (file_put_contents($config_path, $config) === false) {
                    $error = 'Connected to the database, but could not write config/db.local.php. Create it manually from config/db.local.example.php.';
                } else {
                    $configured = true;
                    $message = 'Setup complete. Delete setup.php from the server, then open index.php.';
                }
            }

            $conn->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EShop Setup</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f6f7fb; color: #18202f; }
        main { max-width: 760px; margin: 6vh auto; padding: 32px; background: #fff; border: 1px solid #dfe3ec; border-radius: 8px; }
        h1 { margin-top: 0; }
        label { display: block; margin: 16px 0; font-weight: 700; }
        input[type="text"], input[type="password"] { width: 100%; box-sizing: border-box; margin-top: 6px; padding: 12px; border: 1px solid #b8c0d2; border-radius: 6px; font: inherit; }
        button { padding: 12px 18px; border: 0; border-radius: 6px; background: #18202f; color: #fff; font: inherit; font-weight: 700; cursor: pointer; }
        code { background: #eef1f7; padding: 2px 6px; border-radius: 4px; }
        .notice { padding: 12px; background: #ecfdf3; border: 1px solid #9ad8b2; border-radius: 6px; }
        .error { padding: 12px; background: #fff1f2; border: 1px solid #f0a8b4; border-radius: 6px; }
        .hint { color: #5f6b7a; }
    </style>
</head>
<body>
    <main>
        <h1>EShop Setup</h1>

        <?php if ($message): ?><p class="notice"><?php echo setup_e($message); ?></p><?php endif; ?>
        <?php if ($error): ?><p class="error"><?php echo setup_e($error); ?></p><?php endif; ?>

        <?php if ($configured): ?>
            <p>Your database config file already exists at <code>config/db.local.php</code>.</p>
            <p>For security, delete <code>setup.php</code> from the server now.</p>
            <p><a href="index.php">Open the store</a></p>
        <?php else: ?>
            <p class="hint">Enter the MySQL details from your hosting control panel. On InfinityFree, the MySQL host is usually not <code>localhost</code>.</p>
            <form method="post">
                <label>MySQL Host
                    <input type="text" name="host" placeholder="sqlXXX.infinityfree.com" required>
                </label>
                <label>MySQL Username
                    <input type="text" name="user" placeholder="if0_12345678" required>
                </label>
                <label>MySQL Password
                    <input type="password" name="pass">
                </label>
                <label>Database Name
                    <input type="text" name="name" placeholder="if0_12345678_eshop" required>
                </label>
                <label>
                    <input type="checkbox" name="import" checked>
                    Import products and tables from <code>database/shop_import.sql</code>
                </label>
                <button type="submit">Save Setup</button>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>
