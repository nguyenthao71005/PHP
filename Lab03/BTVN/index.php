<?php
require_once "functions.php";

$action = $_GET["action"] ?? "home";

echo "<h2>LAB03 – PHP Control Structures</h2>";

echo "<p>
<a href='?action=bai1&score=8.2'>Bài 1</a> |
<a href='?action=bai2&a=10&b=3&op=mul'>Bài 2</a> |
<a href='?action=bai3&n=25'>Bài 3</a> |
<a href='?action=max&a=10&b=22'>Max</a> |
<a href='?action=min&a=10&b=22'>Min</a> |
<a href='?action=prime&n=17'>Prime</a> |
<a href='?action=fact&n=6'>Factorial</a> |
<a href='?action=gcd&a=12&b=18'>GCD</a>
</p>
<hr>";

switch ($action) {

    case "bai1":
        include "bai1_grade.php";
        break;

    case "bai2":
        include "bai2_calc.php";
        break;

    case "bai3":
        include "bai3_loops.php";
        break;

    case "max":
        $a = (float)($_GET["a"] ?? 0);
        $b = (float)($_GET["b"] ?? 0);
        echo "Max($a, $b) = " . max2($a, $b);
        break;

    case "min":
        $a = (float)($_GET["a"] ?? 0);
        $b = (float)($_GET["b"] ?? 0);
        echo "Min($a, $b) = " . min2($a, $b);
        break;

    case "prime":
        $n = (int)($_GET["n"] ?? 0);
        echo "$n " . (isPrime($n) ? "là số nguyên tố" : "không phải số nguyên tố");
        break;

    case "fact":
        $n = (int)($_GET["n"] ?? 0);
        $f = factorial($n);
        echo ($f === null)
            ? "Không tính được giai thừa với n < 0"
            : "$n! = $f";
        break;

    case "gcd":
        $a = (int)($_GET["a"] ?? 0);
        $b = (int)($_GET["b"] ?? 0);
        echo "GCD($a, $b) = " . gcd($a, $b);
        break;
    }
?>
