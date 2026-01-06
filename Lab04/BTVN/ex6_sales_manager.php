<?php
require_once "Product.php";
function h($s){return htmlspecialchars($s,ENT_QUOTES,'UTF-8');}

$list=[];
if ($_POST) {
    foreach (explode(';',$_POST['data']) as $r) {
        $f=explode('-',$r);
        if (count($f)==4 && is_numeric($f[2]) && is_numeric($f[3])) {
            $list[]=new Product($f[0],$f[1],$f[2],$f[3]);
        }
    }

    if ($_POST['minPrice']!=='') {
        $list=array_filter($list,fn($p)=>$p->getPrice()>=$_POST['minPrice']);
    }

    if (isset($_POST['sort'])) {
        usort($list,fn($a,$b)=>$b->amount()<=>$a->amount());
    }
}
?>
<!DOCTYPE html>
<html>
<body>
<h3>Bài 6B – Sales Manager</h3>

<form method="post">
<textarea name="data" rows="4" cols="60"><?= h($_POST['data']??'') ?></textarea><br>
Min price: <input name="minPrice">
<label><input type="checkbox" name="sort"> Sort amount ↓</label>
<br><button>Show</button>
</form>

<table border="1" cellpadding="6" style="border-collapse: collapse;">
<tr><th>ID</th><th>Name</th><th>Price</th><th>Qty</th><th>Amount</th></tr>
<?php foreach($list as $p): ?>
<tr>
<td><?= h($p->getId()) ?></td>
<td><?= h($p->getName()) ?></td>
<td><?= $p->getPrice() ?></td>
<td><?= $p->getQty() ?></td>
<td><?= $p->getQty()<=0?'Invalid qty':$p->amount() ?></td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
