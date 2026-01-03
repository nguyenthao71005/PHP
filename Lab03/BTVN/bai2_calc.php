<?php
$a = (float)($_GET["a"] ?? 0);
$b = (float)($_GET["b"] ?? 0);
$op = $_GET["op"] ?? "add";

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
    case "div":
        if ($b == 0) {
            echo "Không chia được cho 0";
            exit;
        }
        $result = $a / $b;
        $symbol = "/";
        break;
    default:
        echo "Phép toán không hợp lệ";
        exit;
}

echo "$a $symbol $b = $result";
?>
