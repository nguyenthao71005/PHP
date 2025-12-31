<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
</head>
<body>
    <form method="post">
    Họ tên: <input type="text" name="hoten"><br><br>
    Email: <input type="mail" name="mail"><br><br>
    Giới tính: <input type="radio" name="gioitinh" value="Nam"> Nam <input type="radio" name="gioitinh" value="Nữ"> Nữ <br><br>
    Sở thích: <br><input type="checkbox" name="sothich[]" value="Coding"> Coding <br> <input type="checkbox" name="sothich[]" value="Sports"> Sports <br> <input type="checkbox" name="sothich[]" value="Music"> Music <br>
    <button type="submit"> Submit </button>
    </form>
    <hr>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST['hoten']) || empty($_POST['mail'])) {
            echo "<p> Vui lòng nhập</p>";
        } else {
            echo "<h3> Dữ liệu đã gửi</h3>";
            echo "<ul>";
            echo "<li> Họ tên: ".htmlspecialchars($_POST['hoten'])."</li>";
            echo "<li> Email: ".htmlspecialchars($_POST['mail'])."</li>";
            echo "<li> Giới tính: ".($_POST['gioitinh'] ?? '')."</li>";
            if (!empty($_POST['sothich'])) {
                echo "<li> Sở thích: ".implode(",", $_POST['sothich'])."</li>";
            }
            echo "</ul>";
        }
    }
    ?>
</body>
</html>