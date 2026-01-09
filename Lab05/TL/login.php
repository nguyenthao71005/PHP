<?php
session_start();

// Nếu đã login thì vào thẳng dashboard
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$err = isset($_GET['err']) ? true : false;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

<h2>Đăng nhập</h2>

<?php if ($err): ?>
    <p style="color:red;">Email hoặc mật khẩu không đúng!</p>
<?php endif; ?>

<form method="post" action="process_login.php">
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>
</body>
</html>
