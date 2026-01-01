<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tính BMI</title>
</head>
<body>

<form method="get">
    Họ tên: <input type="text" name="name"><br><br>
    Chiều cao (m): <input type="text" name="height"><br><br>
    Cân nặng (kg): <input type="text" name="weight"><br><br>
    <button type="submit">Tính BMI</button>
</form>

<hr>

<?php
// 17.Khi Submit: nhận dữ liệu từ $_GET, kiểm tra hợp lệ (không rỗng, số > 0).
if (isset($_GET['name'], $_GET['height'], $_GET['weight'])) {
    $name = $_GET['name'];
    $height = (float) $_GET['height'];
    $weight = (float) $_GET['weight'];

    if ($name == "" || $height <= 0 || $weight <= 0) {
        echo "Dữ liệu không hợp lệ";
    } else {
// 18.Tính BMI = weight / (height^2), làm tròn 2 chữ số.
        $bmi = round($weight / ($height * $height), 2);
// 19.Phân loại: <18.5 Gầy; 18.5–24.9 Bình thường; 25–29.9 Thừa cân; >=30 Béo phì.
        if ($bmi < 18.5) {
            $type = "Gầy";
        } elseif ($bmi < 25) {
            $type = "Bình thường";
        } elseif ($bmi < 30) {
            $type = "Thừa cân";
        } else {
            $type = "Béo phì";
        }
// 20.In kết quả rõ ràng (tên, BMI, phân loại).
        echo "Họ tên: $name<br>";
        echo "BMI: $bmi<br>";
        echo "Phân loại: $type";
    }
}
?>
</body>
</html>
