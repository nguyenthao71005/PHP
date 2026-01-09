<?php
session_start();

// Lấy dữ liệu POST
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

// Kiểm tra rỗng
if ($email === '' || $password === '') {
    header('Location: login.php?err=1');
    exit;
}

$demoEmail = 'admin@example.com';
$demoPass  = '123456';

if ($email === $demoEmail && $password === $demoPass) {
    // Đăng nhập thành công
    $_SESSION['user'] = [
        'email' => $email,
        'role'  => 'admin',
        'login_time' => time()
    ];

    header('Location: dashboard.php');
    exit;
}

// Sai thông tin
header('Location: login.php?err=1');
exit;
