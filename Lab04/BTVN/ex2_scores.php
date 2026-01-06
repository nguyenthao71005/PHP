<?php
$scores = [8.5, 7.0, 9.25, 6.5, 8.0, 5.75];

$avg = array_sum($scores) / count($scores);
$good = array_filter($scores, fn($x) => $x >= 8.0);

$max = max($scores);
$min = min($scores);

$asc = $scores;
$desc = $scores;
sort($asc);
rsort($desc);
?>
<!DOCTYPE html>
<html>
<body>
<h3>Bài 2 – Thống kê điểm</h3>

<p>Điểm TB: <?= number_format($avg, 2) ?></p>
<p>Số điểm ≥ 8.0: <?= count($good) ?> (<?= implode(', ', $good) ?>)</p>
<p>Max: <?= $max ?> | Min: <?= $min ?></p>

<p>Tăng dần: <?= implode(', ', $asc) ?></p>
<p>Giảm dần: <?= implode(', ', $desc) ?></p>
</body>
</html>
