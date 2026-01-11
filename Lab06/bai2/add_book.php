<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sách vào kho thư viện</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
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
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input:focus,
        select:focus {
            outline: none;
            border-color: #11998e;
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
        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            color: #155724;
            text-align: center;
            font-weight: 500;
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
            background: #11998e;
            color: white;
        }
        button[type="submit"]:hover {
            background: #0e7a71;
        }
        button[type="reset"] {
            background: #6c757d;
            color: white;
        }
        button[type="reset"]:hover {
            background: #5a6268;
        }
        a.btn {
            background: #38ef7d;
            color: #333;
        }
        a.btn:hover {
            background: #2dd16a;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thêm sách vào kho thư viện</h1>
        
        <?php
        $booksFile = '../data/books.json';
        $errors = [];
        $data = [];
        $success = false;
        
        // Khởi tạo file JSON nếu chưa tồn tại
        if (!file_exists($booksFile)) {
            $dataDir = dirname($booksFile);
            if (!is_dir($dataDir)) {
                mkdir($dataDir, 0777, true);
            }
            file_put_contents($booksFile, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $data['ma_sach'] = isset($_POST['ma_sach']) ? trim($_POST['ma_sach']) : '';
            $data['ten_sach'] = isset($_POST['ten_sach']) ? trim($_POST['ten_sach']) : '';
            $data['tac_gia'] = isset($_POST['tac_gia']) ? trim($_POST['tac_gia']) : '';
            $data['nam_xuat_ban'] = isset($_POST['nam_xuat_ban']) ? trim($_POST['nam_xuat_ban']) : '';
            $data['the_loai'] = isset($_POST['the_loai']) ? $_POST['the_loai'] : '';
            $data['so_luong'] = isset($_POST['so_luong']) ? trim($_POST['so_luong']) : '';
            
            // Kiểm tra required
            if (empty($data['ma_sach'])) {
                $errors[] = 'Mã sách là bắt buộc';
            }
            
            if (empty($data['ten_sach'])) {
                $errors[] = 'Tên sách là bắt buộc';
            }
            
            if (empty($data['tac_gia'])) {
                $errors[] = 'Tác giả là bắt buộc';
            }
            
            if (empty($data['nam_xuat_ban'])) {
                $errors[] = 'Năm xuất bản là bắt buộc';
            } else {
                $nam_xuat_ban = intval($data['nam_xuat_ban']);
                $nam_hien_tai = date('Y');
                if ($nam_xuat_ban < 1900 || $nam_xuat_ban > $nam_hien_tai) {
                    $errors[] = 'Năm xuất bản phải từ 1900 đến ' . $nam_hien_tai;
                }
            }
            
            if (empty($data['the_loai'])) {
                $errors[] = 'Thể loại là bắt buộc';
            }
            
            if (empty($data['so_luong'])) {
                $errors[] = 'Số lượng là bắt buộc';
            } else {
                $so_luong = intval($data['so_luong']);
                if ($so_luong < 0) {
                    $errors[] = 'Số lượng phải lớn hơn hoặc bằng 0';
                }
            }
            
            // Kiểm tra mã sách trùng
            if (!empty($data['ma_sach']) && empty($errors)) {
                $books = [];
                if (file_exists($booksFile)) {
                    $jsonContent = file_get_contents($booksFile);
                    $books = json_decode($jsonContent, true);
                    if ($books === null) {
                        $books = [];
                    }
                }
                
                foreach ($books as $book) {
                    if (isset($book['ma_sach']) && $book['ma_sach'] === $data['ma_sach']) {
                        $errors[] = 'Mã sách đã tồn tại trong hệ thống';
                        break;
                    }
                }
            }
            
            // Nếu không có lỗi, lưu vào file JSON
            if (empty($errors)) {
                $books = [];
                if (file_exists($booksFile)) {
                    $jsonContent = file_get_contents($booksFile);
                    $books = json_decode($jsonContent, true);
                    if ($books === null) {
                        $books = [];
                    }
                }
                
                // Thêm sách mới
                $books[] = [
                    'ma_sach' => $data['ma_sach'],
                    'ten_sach' => $data['ten_sach'],
                    'tac_gia' => $data['tac_gia'],
                    'nam_xuat_ban' => intval($data['nam_xuat_ban']),
                    'the_loai' => $data['the_loai'],
                    'so_luong' => intval($data['so_luong'])
                ];
                
                // Ghi vào file
                file_put_contents($booksFile, json_encode($books, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                
                $success = true;
                // Reset form data
                $data = [];
            }
        }
        
        // Hiển thị thông báo thành công
        if ($success) {
            echo '<div class="success-message">Sách đã được thêm vào kho thành công!</div>';
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
                <label for="ma_sach">Mã sách (ISBN hoặc mã nội bộ) <span class="required">*</span></label>
                <input type="text" 
                       id="ma_sach" 
                       name="ma_sach" 
                       value="<?php echo isset($data['ma_sach']) ? htmlspecialchars($data['ma_sach']) : ''; ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="ten_sach">Tên sách <span class="required">*</span></label>
                <input type="text" 
                       id="ten_sach" 
                       name="ten_sach" 
                       value="<?php echo isset($data['ten_sach']) ? htmlspecialchars($data['ten_sach']) : ''; ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="tac_gia">Tác giả <span class="required">*</span></label>
                <input type="text" 
                       id="tac_gia" 
                       name="tac_gia" 
                       value="<?php echo isset($data['tac_gia']) ? htmlspecialchars($data['tac_gia']) : ''; ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="nam_xuat_ban">Năm xuất bản <span class="required">*</span></label>
                <input type="number" 
                       id="nam_xuat_ban" 
                       name="nam_xuat_ban" 
                       value="<?php echo isset($data['nam_xuat_ban']) ? htmlspecialchars($data['nam_xuat_ban']) : ''; ?>" 
                       min="1900" 
                       max="<?php echo date('Y'); ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="the_loai">Thể loại <span class="required">*</span></label>
                <select id="the_loai" name="the_loai" required>
                    <option value="">-- Chọn thể loại --</option>
                    <option value="Giáo trình" <?php echo (isset($data['the_loai']) && $data['the_loai'] === 'Giáo trình') ? 'selected' : ''; ?>>Giáo trình</option>
                    <option value="Kỹ năng" <?php echo (isset($data['the_loai']) && $data['the_loai'] === 'Kỹ năng') ? 'selected' : ''; ?>>Kỹ năng</option>
                    <option value="Văn học" <?php echo (isset($data['the_loai']) && $data['the_loai'] === 'Văn học') ? 'selected' : ''; ?>>Văn học</option>
                    <option value="Khoa học" <?php echo (isset($data['the_loai']) && $data['the_loai'] === 'Khoa học') ? 'selected' : ''; ?>>Khoa học</option>
                    <option value="Khác" <?php echo (isset($data['the_loai']) && $data['the_loai'] === 'Khác') ? 'selected' : ''; ?>>Khác</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="so_luong">Số lượng <span class="required">*</span></label>
                <input type="number" 
                       id="so_luong" 
                       name="so_luong" 
                       value="<?php echo isset($data['so_luong']) ? htmlspecialchars($data['so_luong']) : ''; ?>" 
                       min="0" 
                       required>
            </div>
            
            <div class="button-group">
                <button type="submit">Thêm sách</button>
                <button type="reset">Reset</button>
                <a href="list_books.php" class="btn">Danh sách sách</a>
            </div>
        </form>
    </div>
</body>
</html>
