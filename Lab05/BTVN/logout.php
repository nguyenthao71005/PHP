<?php

session_start();

$username = $_SESSION['user']['username'] ?? '';

session_unset();
session_destroy();

setcookie('remember_username', '', time()-3600, '/');

session_start();
$_SESSION['flash']['info'] = 'Bạn đã đăng xuất.';

file_put_contents(
    'data/log.txt',
    date('Y-m-d H:i:s')." LOGOUT {$username}\n",
    FILE_APPEND
);

header('Location: login.php');
exit;
