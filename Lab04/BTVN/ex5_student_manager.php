<?php
require_once "Student.php";
function h($s){return htmlspecialchars($s,ENT_QUOTES,'UTF-8');}

$list = [];
$error = '';

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $raw = $_POST['data'] ?? '';
    $threshold = $_POST['threshold'] ?? '';
    $sort = isset($_POST['sort']);

    foreach (explode(';',$raw) as $rec) {
        $f = explode('-',$rec);
        if (count($f)==3 && is_numeric($f[2])) {
            $list[] = new Student(trim($f[0]),trim($f[1]),(float)$f[2]);
        }
    }

    if (!$list) $error = "Không có dữ liệu hợp lệ";

    if ($threshold !== '') {
        $list = array_filter($list, fn($s)=>$s->getGpa() >= (float)$threshold);
    }

    if ($sort) {
        usort($list, fn($a,$b)=>$b->getGpa() <=> $a->getGpa());
    }
}
?>
<!DOCTYPE html>
<html>
<body>
<h3>Bài 5 – Student Manager</h3>

<form method="post">
<textarea name="data" rows="4" cols="60"><?= h($_POST['data'] ?? '') ?></textarea><br>
GPA ≥ <input name="threshold" value="<?= h($_POST['threshold'] ?? '') ?>">
<label><input type="checkbox" name="sort" <?= isset($_POST['sort'])?'checked':'' ?>> Sort GPA ↓</label>
<br><button>Parse & Show</button>
</form>

<?php if ($error): ?><p style="color:red"><?= $error ?></p><?php endif; ?>

<?php if ($list): ?>
<table border="1" cellpadding="6" style="border-collapse: collapse;">
<tr><th>ID</th><th>Name</th><th>GPA</th><th>Rank</th></tr>
<?php foreach ($list as $s): ?>
<tr>
<td><?= h($s->getId()) ?></td>
<td><?= h($s->getName()) ?></td>
<td><?= $s->getGpa() ?></td>
<td><?= $s->rank() ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
</body>
</html>
