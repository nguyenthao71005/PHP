<?php
// 3. Tạo ex02/variables.php khai báo các biến: $fullName (string), $age (int), $gpa (float), $isActive (bool).
$fullName = "Nguyễn Phương Thảo";
$age = 20;
$gpa = 3.36;
$isActive = true;
// 4. Khai báo hằng SCHOOL bằng const hoặc define.
const SCHOOL = "Đại học Công nghệ Đông Á";
// 5. In ra thông tin theo 2 kiểu: dạng câu (echo/print) và dạng debug (var_dump) cho từng biến.
echo "Họ tên: $fullName<br>";
echo "Tuổi: $age<br>";
echo "GPA: $gpa<br>";
echo "Hoạt động: $isActive<br>";
echo "Trường: " . SCHOOL . "<br><br>";
var_dump($fullName);
echo "<br>";
var_dump($age);
echo "<br>";
var_dump($gpa);
echo "<br>";
var_dump($isActive);
echo "<br><br>";
// 6. Thử nội suy chuỗi: nháy kép "Tuoi: $age" và nháy đơn 'Tuoi: $age', ghi nhận xét ngắn bằng comment.
echo "Tuoi: $age<br>";
echo 'Tuoi: $age<br>';
?>
