<?php
$n = (int)($_GET["n"] ?? 0);

/* A) Bảng cửu chương */
echo "<h3>Bảng cửu chương</h3>";
echo "<table border='1' cellpadding='5'>";
for ($i = 1; $i <= 9; $i++) {
    echo "<tr>";
    for ($j = 1; $j <= 9; $j++) {
        echo "<td>$i × $j = " . ($i * $j) . "</td>";
    }
    echo "</tr>";
}
echo "</table>";

/* B) Tổng chữ số */
$temp = abs($n);
$sum = 0;
while ($temp > 0) {
    $sum += $temp % 10;
    $temp = intdiv($temp, 10);
}
echo "<p>Tổng chữ số của $n = $sum</p>";

/* C) Số lẻ */
echo "<p>Số lẻ từ 1..$n: ";
for ($i = 1; $i <= $n; $i++) {
    if ($i % 2 == 0) continue;
    if ($i > 15) break;
    echo "$i ";
}
echo "</p>";
?>
