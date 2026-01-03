<?php
require_once "functions.php";

$action = $_GET["action"] ?? "";

$a = (int)($_GET["a"] ?? 0);
$b = (int)($_GET["b"] ?? 0);
$n = (int)($_GET["n"] ?? 0);
?>

<h2>LAB03 – Functions</h2>

<p>
<a href="?action=max&a=10&b=22">Max</a> |
<a href="?action=min&a=10&b=22">Min</a> |
<a href="?action=prime&n=17">Prime</a> |
<a href="?action=fact&n=6">Factorial</a> |
<a href="?action=gcd&a=12&b=18">GCD</a>
</p>
<hr>

<?php
switch ($action) {
    case "max":
        echo "Max = " . max2($a, $b);
        break;

    case "min":
        echo "Min = " . min2($a, $b);
        break;

    case "prime":
        echo $n . (isPrime($n) ? " là số nguyên tố" : " không phải số nguyên tố");
        break;

    case "fact":
        $res = factorial($n);
        echo ($res === null) ? "n không hợp lệ" : "Giai thừa = $res";
        break;

    case "gcd":
        echo "GCD = " . gcd($a, $b);
        break;

    default:
        echo "Chọn chức năng";
}
