<?php
require_once 'require_login.php';

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>

<h2>Dashboard</h2>

<p>Xin chào: <b><?= htmlspecialchars($user['email']) ?></b></p>
<p>Role: <?= htmlspecialchars($user['role']) ?></p>
<p>Thời điểm login: <?= date('d/m/Y', $user['login_time']) ?></p>

<hr>

<p>
    <a href="logout.php">Logout</a>
</p>

</body>
</html>
