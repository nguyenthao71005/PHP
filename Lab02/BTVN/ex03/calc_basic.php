<?php
// 7. Tạo ex03/calc_basic.php với $a = 10; $b = 3.
$a = 10;
$b = 3;
// 8. Tính và in ra: tổng, hiệu, tích, thương, chia lấy dư.
echo "Tổng: " . ($a + $b) . "<br>";
echo "Hiệu: " . ($a - $b) . "<br>";
echo "Tích: " . ($a * $b) . "<br>";
echo "Thương: " . ($a / $b) . "<br>";
echo "Chia dư: " . ($a % $b) . "<br><br>";
// 9. Tạo chuỗi thông báo và bắt buộc dùng toán tử nối chuỗi . và/hoặc .= ở ít nhất 1 vị trí.
$message = "Kết quả phép tính: ";
$message .= "a = " . $a . ", b = " . $b;
echo $message . "<br><br>";
// 10.In ra kết quả so sánh: "5" == 5 và "5" === 5, kèm giải thích 1–2 dòng comment.
var_dump("5" == 5);   // true: so sánh giá trị (== chỉ so sánh giá trị)
echo "<br>";
var_dump("5" === 5);  // false: khác kiểu dữ liệu (=== so sánh cả giá trị và kiểu)
?>
