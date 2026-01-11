<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký thẻ thư viện</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        input[type="date"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
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
        .error {
            color: red;
            font-size: 12px;
            margin-top: 5px;
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
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        button[type="submit"],
        button[type="reset"] {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button[type="submit"] {
            background: #667eea;
            color: white;
        }
        button[type="submit"]:hover {
            background: #5568d3;
        }
        button[type="reset"] {
            background: #6c757d;
            color: white;
        }
        button[type="reset"]:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Đăng ký thẻ thư viện</h1>
        
        <?php
        $errors = [];
        $data = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $data['ho_ten'] = isset($_POST['ho_ten']) ? trim($_POST['ho_ten']) : '';
            $data['email'] = isset($_POST['email']) ? trim($_POST['email']) : '';
            $data['so_dien_thoai'] = isset($_POST['so_dien_thoai']) ? trim($_POST['so_dien_thoai']) : '';
            $data['ngay_sinh'] = isset($_POST['ngay_sinh']) ? trim($_POST['ngay_sinh']) : '';
            $data['gioi_tinh'] = isset($_POST['gioi_tinh']) ? $_POST['gioi_tinh'] : '';
            $data['dia_chi'] = isset($_POST['dia_chi']) ? trim($_POST['dia_chi']) : '';
            
            // Kiểm tra required
            if (empty($data['ho_ten'])) {
                $errors[] = 'Họ tên là bắt buộc';
            }
            
            if (empty($data['email'])) {
                $errors[] = 'Email là bắt buộc';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email không đúng định dạng';
            }
            
            if (empty($data['so_dien_thoai'])) {
                $errors[] = 'Số điện thoại là bắt buộc';
            } elseif (!preg_match('/^[0-9]+$/', $data['so_dien_thoai'])) {
                $errors[] = 'Số điện thoại chỉ được chứa số';
            } elseif (strlen($data['so_dien_thoai']) < 9 || strlen($data['so_dien_thoai']) > 11) {
                $errors[] = 'Số điện thoại phải có độ dài từ 9 đến 11 ký tự';
            }
            
            if (empty($data['ngay_sinh'])) {
                $errors[] = 'Ngày sinh là bắt buộc';
            }
            
            // Nếu không có lỗi, chuyển đến trang kết quả
            if (empty($errors)) {
                // Lưu dữ liệu vào session để truyền sang trang kết quả
                session_start();
                $_SESSION['member_data'] = $data;
                header('Location: member_result.php');
                exit;
            }
        }
        
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
            <div class="form-group">
                <label for="ho_ten">Họ tên <span class="required">*</span></label>
                <input type="text" 
                       id="ho_ten" 
                       name="ho_ten" 
                       value="<?php echo isset($data['ho_ten']) ? htmlspecialchars($data['ho_ten']) : ''; ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="<?php echo isset($data['email']) ? htmlspecialchars($data['email']) : ''; ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="so_dien_thoai">Số điện thoại <span class="required">*</span></label>
                <input type="text" 
                       id="so_dien_thoai" 
                       name="so_dien_thoai" 
                       value="<?php echo isset($data['so_dien_thoai']) ? htmlspecialchars($data['so_dien_thoai']) : ''; ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="ngay_sinh">Ngày sinh <span class="required">*</span></label>
                <input type="date" 
                       id="ngay_sinh" 
                       name="ngay_sinh" 
                       value="<?php echo isset($data['ngay_sinh']) ? htmlspecialchars($data['ngay_sinh']) : ''; ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label>Giới tính</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" 
                               name="gioi_tinh" 
                               value="Nam" 
                               <?php echo (isset($data['gioi_tinh']) && $data['gioi_tinh'] === 'Nam') ? 'checked' : ''; ?>>
                        Nam
                    </label>
                    <label>
                        <input type="radio" 
                               name="gioi_tinh" 
                               value="Nữ" 
                               <?php echo (isset($data['gioi_tinh']) && $data['gioi_tinh'] === 'Nữ') ? 'checked' : ''; ?>>
                        Nữ
                    </label>
                    <label>
                        <input type="radio" 
                               name="gioi_tinh" 
                               value="Khác" 
                               <?php echo (isset($data['gioi_tinh']) && $data['gioi_tinh'] === 'Khác') ? 'checked' : ''; ?>>
                        Khác
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label for="dia_chi">Địa chỉ</label>
                <textarea id="dia_chi" 
                          name="dia_chi" 
                          rows="3"><?php echo isset($data['dia_chi']) ? htmlspecialchars($data['dia_chi']) : ''; ?></textarea>
            </div>
            
            <div class="button-group">
                <button type="submit">Submit</button>
                <button type="reset">Reset</button>
            </div>
        </form>
    </div>
</body>
</html>
