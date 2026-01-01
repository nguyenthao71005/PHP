<?php
// 12.Nếu thiếu tham số: hiển thị hướng dẫn URL mẫu.
if (!isset($_GET['a'], $_GET['b'], $_GET['op'])) {
    echo "Vui lòng truy cập theo dạng:<br>";
    echo "calc_get.php?a=7&b=10&op=add|sub|mul|div";
    exit;
}
// 11.Tạo ex04/calc_get.php nhận tham số a, b, op (add|sub|mul|div) qua $_GET.
// 13.Ép kiểu a, b sang số (int hoặc float).
$a = (int) $_GET['a'];
$b = (int) $_GET['b'];
$op = $_GET['op'];
switch ($op) {
    case "add":
        $result = $a + $b;
        $symbol = "+";
        break;
    case "sub":
        $result = $a - $b;
        $symbol = "-";
        break;
    case "mul":
        $result = $a * $b;
        $symbol = "*";
        break;
// 14.Nếu op=div và b=0: báo lỗi “Không thể chia cho 0”.
    case "div":
        if ($b == 0) {
            echo "Không thể chia cho 0";
            exit;
        }
        $result = $a / $b;
        $symbol = "/";
        break;
    default:
        echo "Phép toán không hợp lệ";
        exit;
}
// 15.In kết quả dạng: 10 / 2 = 5.
echo "$a $symbol $b = $result";
?>
