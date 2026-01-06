<?php
function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$raw = $_GET['names'] ?? '';
$names = [];

if ($raw !== '') {
    $parts = explode(',', $raw);
    $names = array_filter(
        array_map('trim', $parts),
        fn($x) => $x !== ''
    );
};
?>
<!DOCTYPE html>
<html>
<body>
<h3>Bài 1 – Danh sách tên</h3>

<p><b>Chuỗi gốc:</b> <?= h($raw) ?></p>

<?php if (count($names) === 0): ?>
    <p><i>?names=An,Binh,Chi,Dung</i></p>
<?php else: ?>
    <p><b>Số lượng tên hợp lệ:</b> <?= count($names) ?></p>
    <ol>
        <?php foreach ($names as $n): ?>
            <li><?= h($n) ?></li>
        <?php endforeach; ?>
    </ol>
<?php endif; ?>
</body>
</html>
