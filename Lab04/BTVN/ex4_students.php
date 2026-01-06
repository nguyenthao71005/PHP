<?php
require_once "Student.php";
function h($s){return htmlspecialchars($s,ENT_QUOTES,'UTF-8');}

$list = [
    new Student("SV01","An",3.5),
    new Student("SV02","Binh",2.8),
    new Student("SV03","Chi",3.1),
    new Student("SV04","Dung",2.2),
    new Student("SV05","Hoa",3.8),
];

$gpas = array_map(fn($s)=>$s->getGpa(), $list);
$avg = array_sum($gpas)/count($gpas);

$stat = ["Giỏi"=>0,"Khá"=>0,"Trung bình"=>0];
foreach ($list as $s) $stat[$s->rank()]++;
?>
<!DOCTYPE html>
<html>
<body>
<h3>Bài 4 – Student</h3>
<table border="1" cellpadding="6" style="border-collapse: collapse;">
<tr><th>STT</th><th>ID</th><th>Name</th><th>GPA</th><th>Rank</th></tr>
<?php foreach ($list as $i=>$s): ?>
<tr>
<td><?= $i+1 ?></td>
<td><?= h($s->getId()) ?></td>
<td><?= h($s->getName()) ?></td>
<td><?= $s->getGpa() ?></td>
<td><?= $s->rank() ?></td>
</tr>
<?php endforeach; ?>
</table>

<p>GPA TB lớp: <?= number_format($avg,2) ?></p>
<p>Giỏi: <?= $stat['Giỏi'] ?> | Khá: <?= $stat['Khá'] ?> | TB: <?= $stat['Trung bình'] ?></p>
</body>
</html>
