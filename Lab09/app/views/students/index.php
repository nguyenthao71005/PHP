<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sinh viên</title>
    <link rel="stylesheet" href="/project/public/assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

<h2>Quản lý sinh viên</h2>

<form id="student-form">
    <input name="code" placeholder="Mã SV" required>
    <input name="full_name" placeholder="Họ tên" required>
    <input name="email" placeholder="Email" required>
    <input type="date" name="dob">
    <button type="submit">Add</button>
</form>

<table border="1" width="100%">
    <thead>
        <tr>
            <th>STT</th>
            <th>Mã SV</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Ngày sinh</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody id="student-list"></tbody>
</table>

<script src="assets/js/app.js"></script>
</body>
</html>
