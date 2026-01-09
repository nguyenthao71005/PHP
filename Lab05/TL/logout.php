<?php
session_start();

// Xóa dữ liệu session
$_SESSION = [];

// Xóa cookie session nếu có
if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Hủy session
session_destroy();

// Quay về login
header('Location: login.php');
exit;
