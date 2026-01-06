<?php
function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

$products = [
    ['name'=>'Pen', 'price'=>5, 'qty'=>10],
    ['name'=>'Book', 'price'=>20, 'qty'=>3],
    ['name'=>'Bag', 'price'=>50, 'qty'=>1],
];

$products = array_map(function($p){
    $p['amount'] = $p['price'] * $p['qty'];
    return $p;
}, $products);

$total = array_reduce($products, fn($s,$p)=>$s+$p['amount'], 0);

$maxItem = $products[0];
foreach ($products as $p) {
    if ($p['amount'] > $maxItem['amount']) $maxItem = $p;
}

$sorted = $products;
usort($sorted, fn($a,$b)=>$b['price'] <=> $a['price']);
?>
<!DOCTYPE html>
<html>
<body>
<h3>Bài 3 – Giỏ hàng</h3>

<table border="1" cellpadding="6" style="border-collapse: collapse;">
<tr><th>STT</th><th>Name</th><th>Price</th><th>Qty</th><th>Amount</th></tr>
<?php foreach ($products as $i=>$p): ?>
<tr>
<td><?= $i+1 ?></td>
<td><?= h($p['name']) ?></td>
<td><?= $p['price'] ?></td>
<td><?= $p['qty'] ?></td>
<td><?= $p['amount'] ?></td>
</tr>
<?php endforeach; ?>
<tr>
<td colspan="4"><b>Total</b></td>
<td><b><?= $total ?></b></td>
</tr>
</table>

<p>SP amount lớn nhất: <?= h($maxItem['name']) ?> (<?= $maxItem['amount'] ?>)</p>

<h4>Sắp xếp theo price giảm dần</h4>
<ul>
<?php foreach ($sorted as $p): ?>
<li><?= h($p['name']) ?> - <?= $p['price'] ?></li>
<?php endforeach; ?>
</ul>
</body>
</html>
