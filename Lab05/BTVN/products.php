<?php
require_once 'includes/auth.php';
require_login();

require_once 'includes/cart.php';
$products = require 'includes/products.php';

$cart = $_SESSION['cart'] ?? [];
$cartCount = array_sum($cart);

// Thêm vào giỏ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    cart_add((int)$_POST['id']);
    header('Location: products.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sản phẩm</title>
</head>
<body>

<!-- HEADER -->
<div style="display:flex; justify-content:space-between; align-items:center">
    <h2>🛒 Danh sách sản phẩm</h2>

    <a href="cart.php" style="
        text-decoration:none;
        padding:8px 12px;
        border:1px solid #333;
        border-radius:5px;
        background:#f5f5f5
    ">
        🛒 Giỏ hàng (<?= $cartCount ?>)
    </a>
</div>

<a href="dashboard.php">⬅ Dashboard</a>
<hr>

<!-- LIST PRODUCTS -->
<?php foreach ($products as $id => $p): ?>
    <div style="margin-bottom:12px; padding:8px; border-bottom:1px solid #ddd">

        <b><?= htmlspecialchars($p['name']) ?></b>
        - <?= number_format($p['price']) ?>đ
        <br>

        👉 Trong giỏ: <b><?= $cart[$id] ?? 0 ?></b>

        <form method="post" style="display:inline">
            <input type="hidden" name="id" value="<?= $id ?>">
            <button type="submit">Thêm vào giỏ</button>
        </form>

    </div>
<?php endforeach; ?>

</body>
</html>
