<?php
require_once 'includes/auth.php';
require_login();
require_once 'includes/cart.php';

$products = require 'includes/products.php';

$cart = $_SESSION['cart'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // cập nhật số lượng
    if (isset($_POST['update'])) {
        foreach ($_POST['qty'] as $id => $qty) {
            cart_update((int)$id, (int)$qty);
        }
    }

    // xóa toàn bộ
    if (isset($_POST['clear'])) {
        cart_clear();
    }

    // xóa 1 dòng
    if (isset($_POST['remove'])) {
        cart_remove((int)$_POST['remove']);
    }

    header('Location: cart.php');
    exit;
}
?>

<h2>📦 Giỏ hàng</h2>
<a href="products.php">⬅ Sản phẩm</a>
<hr>

<?php if (!$cart): ?>
    <p>Giỏ hàng trống.</p>
<?php else: ?>

<form method="post">
<table border="1" cellpadding="8">
<tr>
    <th>Sản phẩm</th>
    <th>Số lượng</th>
    <th>Giá</th>
    <th>Xóa</th>
</tr>

<?php foreach ($cart as $id => $qty): ?>
<tr>
    <td><?= htmlspecialchars($products[$id]['name']) ?></td>
    <td>
        <input type="number" name="qty[<?= $id ?>]" value="<?= $qty ?>" min="0">
    </td>
    <td><?= number_format($products[$id]['price'] * $qty) ?>đ</td>
    <td>
        <button name="remove" value="<?= $id ?>">X</button>
    </td>
</tr>
<?php endforeach; ?>
</table>

<br>
<button name="update">Cập nhật</button>
<button name="clear">Xóa toàn bộ</button>
</form>

<?php endif; ?>
