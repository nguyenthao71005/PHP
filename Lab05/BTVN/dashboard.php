<?php
require_once 'includes/auth.php';
require_login();
require_once 'includes/flash.php';

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            margin-top: 0;
            color: #333;
        }

        .welcome {
            font-size: 16px;
            margin-bottom: 15px;
        }

        .role {
            color: #666;
            font-size: 14px;
        }

        .flash {
            background: #e8f7e4;
            color: #2d7a2d;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .menu a {
            display: block;
            text-decoration: none;
            padding: 18px;
            background: #f0f2f5;
            border-radius: 6px;
            text-align: center;
            font-weight: bold;
            color: #333;
            transition: 0.2s;
        }

        .menu a:hover {
            background: #007bff;
            color: white;
        }

        .logout {
            text-align: right;
            margin-top: 20px;
        }

        .logout a {
            color: white;
            background: #dc3545;
            padding: 10px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .logout a:hover {
            background: #b52a37;
        }
    </style>
</head>
<body>

<div class="container">

    <?php if ($msg = get_flash('success')): ?>
        <div class="flash"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <h2>📊 Dashboard</h2>

    <div class="welcome">
        Xin chào <b><?= htmlspecialchars($user['username']) ?></b><br>
        <span class="role">Quyền: <?= htmlspecialchars($user['role']) ?></span>
    </div>

    <hr>

    <div class="menu">
        <a href="products.php">🛒 Danh sách sản phẩm</a>
        <a href="cart.php">📦 Giỏ hàng</a>

        <?php if ($user['role'] === 'admin'): ?>
            <a href="admin.php">⚙️ Admin Panel</a>
        <?php endif; ?>
    </div>

    <div class="logout">
        <a href="logout.php">🚪 Đăng xuất</a>
    </div>

</div>

</body>
</html>
