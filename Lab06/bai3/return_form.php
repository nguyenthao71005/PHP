<?php
session_start();
$errors = [];
$data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $searchType = isset($_POST['search_type']) ? $_POST['search_type'] : 'ma_phieu';
    $data['search_type'] = $searchType;
    $data['ma_phieu'] = isset($_POST['ma_phieu']) ? trim($_POST['ma_phieu']) : '';
    $data['ma_tv'] = isset($_POST['ma_tv']) ? trim($_POST['ma_tv']) : '';
    $data['ma_sach'] = isset($_POST['ma_sach']) ? trim($_POST['ma_sach']) : '';
    $data['ngay_tra'] = isset($_POST['ngay_tra']) ? trim($_POST['ngay_tra']) : '';
    
    // Kiểm tra required
    if ($searchType === 'ma_phieu') {
        if (empty($data['ma_phieu'])) {
            $errors[] = 'Mã phiếu mượn là bắt buộc';
        }
    } else {
        if (empty($data['ma_tv'])) {
            $errors[] = 'Mã thành viên (Email) là bắt buộc';
        }
        if (empty($data['ma_sach'])) {
            $errors[] = 'Mã sách là bắt buộc';
        }
    }
    
    if (empty($data['ngay_tra'])) {
        $errors[] = 'Ngày trả là bắt buộc';
    }
    
    // Nếu không có lỗi, chuyển đến trang xử lý
    if (empty($errors)) {
        $_SESSION['return_data'] = $data;
        header('Location: return_process.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trả sách</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            padding: 30px;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 500;
        }
        .required {
            color: red;
        }
        input[type="text"],
        input[type="email"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #4facfe;
        }
        .error-list {
            background: #fee;
            border: 1px solid #fcc;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .error-list h3 {
            color: #c00;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .error-list ul {
            margin-left: 20px;
        }
        .error-list li {
            color: #c00;
            margin-bottom: 5px;
        }
        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 5px;
        }
        .radio-group label {
            display: flex;
            align-items: center;
            font-weight: normal;
            cursor: pointer;
        }
        .radio-group input[type="radio"] {
            margin-right: 5px;
            cursor: pointer;
        }
        .search-type-section {
            background: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .search-type-section h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }
        .conditional-field {
            display: none;
        }
        .conditional-field.show {
            display: block;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        button[type="submit"],
        button[type="reset"],
        a.btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        button[type="submit"] {
            background: #4facfe;
            color: white;
        }
        button[type="submit"]:hover {
            background: #3d8bfe;
        }
        button[type="reset"] {
            background: #6c757d;
            color: white;
        }
        button[type="reset"]:hover {
            background: #5a6268;
        }
        a.btn {
            background: #00f2fe;
            color: #333;
        }
        a.btn:hover {
            background: #00d9e6;
        }
        .info-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
    <script>
        function toggleSearchType() {
            const searchType = document.querySelector('input[name="search_type"]:checked').value;
            const maPhieuField = document.getElementById('ma_phieu_group');
            const maTVField = document.getElementById('ma_tv_group');
            const maSachField = document.getElementById('ma_sach_group');
            
            if (searchType === 'ma_phieu') {
                maPhieuField.classList.add('show');
                maTVField.classList.remove('show');
                maSachField.classList.remove('show');
                document.getElementById('ma_phieu').required = true;
                document.getElementById('ma_tv').required = false;
                document.getElementById('ma_sach').required = false;
            } else {
                maPhieuField.classList.remove('show');
                maTVField.classList.add('show');
                maSachField.classList.add('show');
                document.getElementById('ma_phieu').required = false;
                document.getElementById('ma_tv').required = true;
                document.getElementById('ma_sach').required = true;
            }
        }
        
        // Gọi khi trang load
        document.addEventListener('DOMContentLoaded', function() {
            toggleSearchType();
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Trả sách</h1>
        
        <?php
        // Hiển thị lỗi nếu có
        if (!empty($errors)) {
            echo '<div class="error-list">';
            echo '<h3>Vui lòng sửa các lỗi sau:</h3>';
            echo '<ul>';
            foreach ($errors as $error) {
                echo '<li>' . htmlspecialchars($error) . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
        ?>
        
        <form method="POST" action="">
            <div class="search-type-section">
                <h3>Chọn cách tìm phiếu:</h3>
                <div class="radio-group">
                    <label>
                        <input type="radio" 
                               name="search_type" 
                               value="ma_phieu" 
                               <?php echo (!isset($data['search_type']) || $data['search_type'] === 'ma_phieu') ? 'checked' : ''; ?>
                               onchange="toggleSearchType()">
                        Mã phiếu mượn
                    </label>
                    <label>
                        <input type="radio" 
                               name="search_type" 
                               value="ma_tv_ma_sach" 
                               <?php echo (isset($data['search_type']) && $data['search_type'] === 'ma_tv_ma_sach') ? 'checked' : ''; ?>
                               onchange="toggleSearchType()">
                        Mã TV + Mã sách
                    </label>
                </div>
            </div>
            
            <div class="form-group conditional-field" id="ma_phieu_group">
                <label for="ma_phieu">Mã phiếu mượn <span class="required">*</span></label>
                <input type="text" 
                       id="ma_phieu" 
                       name="ma_phieu" 
                       value="<?php echo isset($data['ma_phieu']) ? htmlspecialchars($data['ma_phieu']) : ''; ?>">
            </div>
            
            <div class="form-group conditional-field" id="ma_tv_group">
                <label for="ma_tv">Mã thành viên (Email) <span class="required">*</span></label>
                <input type="email" 
                       id="ma_tv" 
                       name="ma_tv" 
                       value="<?php echo isset($data['ma_tv']) ? htmlspecialchars($data['ma_tv']) : ''; ?>">
            </div>
            
            <div class="form-group conditional-field" id="ma_sach_group">
                <label for="ma_sach">Mã sách <span class="required">*</span></label>
                <input type="text" 
                       id="ma_sach" 
                       name="ma_sach" 
                       value="<?php echo isset($data['ma_sach']) ? htmlspecialchars($data['ma_sach']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="ngay_tra">Ngày trả <span class="required">*</span></label>
                <input type="date" 
                       id="ngay_tra" 
                       name="ngay_tra" 
                       value="<?php echo isset($data['ngay_tra']) ? htmlspecialchars($data['ngay_tra']) : date('Y-m-d'); ?>" 
                       required>
            </div>
            
            <div class="button-group">
                <button type="submit">Trả sách</button>
                <button type="reset">Reset</button>
                <a href="borrow_form.php" class="btn">Mượn sách</a>
            </div>
        </form>
    </div>
</body>
</html>
