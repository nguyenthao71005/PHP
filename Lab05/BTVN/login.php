<?php
session_start();
require_once 'includes/flash.php';

$users = require 'includes/users.php';
$username_cookie = $_COOKIE['remember_username'] ?? '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Vui lòng nhập đầy đủ.';
    } elseif (!empty($users[$username]) &&
        password_verify($password, $users[$username]['hash'])) {

        $_SESSION['auth'] = true;
        $_SESSION['user'] = [
            'username' => $username,
            'role' => $users[$username]['role']
        ];

        if (!empty($_POST['remember'])) {
            setcookie('remember_username', $username, time()+7*24*60*60, '/');
        }

        set_flash('success', 'Đăng nhập thành công.');
        file_put_contents('data/log.txt', date('Y-m-d H:i:s')." LOGIN {$username}\n", FILE_APPEND);
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Sai tài khoản hoặc mật khẩu.';
    }
}
?>

<h2>Đăng nhập</h2>
<?php if ($msg = get_flash('success')) echo "<p style='color:green'>$msg</p>"; ?>               
<?php if ($error) echo "<p style='color:red'>$error</p>"; ?>

<form method="post">
    Username: <input name="username" value="<?= htmlspecialchars($username_cookie) ?>"><br>
    Password: <input type="password" name="password"><br>
    <label><input type="checkbox" name="remember"> Remember me</label><br>
    <button>Login</button>
</form>
