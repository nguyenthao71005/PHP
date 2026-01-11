<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lập phiếu mượn sách</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #f5576c;
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
            background: #f5576c;
            color: white;
        }
        button[type="submit"]:hover {
            background: #d94459;
        }
        button[type="reset"] {
            background: #6c757d;
            color: white;
        }
        button[type="reset"]:hover {
            background: #5a6268;
        }
        a.btn {
            background: #f093fb;
            color: #333;
        }
        a.btn:hover {
            background: #e081e8;
        }
        .info-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lập phiếu mượn sách</h1>
        
        <?php
        $errors = [];
        $data = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $data['ma_thanh_vien'] = isset($_POST['ma_thanh_vien']) ? trim($_POST['ma_thanh_vien']) : '';
            $data['ma_sach'] = isset($_POST['ma_sach']) ? trim($_POST['ma_sach']) : '';
            $data['ngay_muon'] = isset($_POST['ngay_muon']) ? trim($_POST['ngay_muon']) : '';
            $data['so_ngay_muon'] = isset($_POST['so_ngay_muon']) ? trim($_POST['so_ngay_muon']) : '';
            
            // Kiểm tra required
            if (empty($data['ma_thanh_vien'])) {
                $errors[] = 'Mã thành viên (Email) là bắt buộc';
            }
            
            if (empty($data['ma_sach'])) {
                $errors[] = 'Mã sách là bắt buộc';
            }
            
            if (empty($data['ngay_muon'])) {
                $errors[] = 'Ngày mượn là bắt buộc';
            }
            
            if (empty($data['so_ngay_muon'])) {
                $errors[] = 'Số ngày mượn là bắt buộc';
            } else {
                $so_ngay = intval($data['so_ngay_muon']);
                if ($so_ngay < 1 || $so_ngay > 30) {
                    $errors[] = 'Số ngày mượn phải từ 1 đến 30 ngày';
                }
            }
            
            // Validate mã thành viên tồn tại trong CSV
            if (!empty($data['ma_thanh_vien']) && empty($errors)) {
                $membersFile = '../data/members.csv';
                $memberExists = false;
                
                if (file_exists($membersFile)) {
                    $fp = fopen($membersFile, 'r');
                    if ($fp) {
                        // Bỏ qua header
                        fgetcsv($fp);
                        while (($row = fgetcsv($fp)) !== FALSE) {
                            if (count($row) >= 2 && $row[1] === $data['ma_thanh_vien']) {
                                $memberExists = true;
                                break;
                            }
                        }
                        fclose($fp);
                    }
                }
                
                if (!$memberExists) {
                    $errors[] = 'Mã thành viên (Email) không tồn tại trong hệ thống';
                }
            }
            
            // Validate mã sách tồn tại và số lượng > 0
            if (!empty($data['ma_sach']) && empty($errors)) {
                $booksFile = '../data/books.json';
                $bookExists = false;
                $soLuong = 0;
                
                if (file_exists($booksFile)) {
                    $jsonContent = file_get_contents($booksFile);
                    $books = json_decode($jsonContent, true);
                    if ($books !== null && is_array($books)) {
                        foreach ($books as $book) {
                            if (isset($book['ma_sach']) && $book['ma_sach'] === $data['ma_sach']) {
                                $bookExists = true;
                                $soLuong = isset($book['so_luong']) ? intval($book['so_luong']) : 0;
                                break;
                            }
                        }
                    }
                }
                
                if (!$bookExists) {
                    $errors[] = 'Mã sách không tồn tại trong hệ thống';
                } elseif ($soLuong <= 0) {
                    $errors[] = 'Số lượng sách còn lại không đủ (số lượng: ' . $soLuong . ')';
                }
            }
            
            // Nếu không có lỗi, chuyển đến trang xử lý
            if (empty($errors)) {
                session_start();
                $_SESSION['borrow_data'] = $data;
                header('Location: borrow_process.php');
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
                <label for="ma_thanh_vien">Mã thành viên (Email) <span class="required">*</span></label>
                <input type="email" 
                       id="ma_thanh_vien" 
                       name="ma_thanh_vien" 
                       value="<?php echo isset($data['ma_thanh_vien']) ? htmlspecialchars($data['ma_thanh_vien']) : ''; ?>" 
                       required>
                <div class="info-text">Nhập email của thành viên đã đăng ký</div>
            </div>
            
            <div class="form-group">
                <label for="ma_sach">Mã sách <span class="required">*</span></label>
                <input type="text" 
                       id="ma_sach" 
                       name="ma_sach" 
                       value="<?php echo isset($data['ma_sach']) ? htmlspecialchars($data['ma_sach']) : ''; ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="ngay_muon">Ngày mượn <span class="required">*</span></label>
                <input type="date" 
                       id="ngay_muon" 
                       name="ngay_muon" 
                       value="<?php echo isset($data['ngay_muon']) ? htmlspecialchars($data['ngay_muon']) : date('Y-m-d'); ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="so_ngay_muon">Số ngày mượn <span class="required">*</span></label>
                <input type="number" 
                       id="so_ngay_muon" 
                       name="so_ngay_muon" 
                       value="<?php echo isset($data['so_ngay_muon']) ? htmlspecialchars($data['so_ngay_muon']) : ''; ?>" 
                       min="1" 
                       max="30" 
                       required>
                <div class="info-text">Số ngày từ 1 đến 30</div>
            </div>
            
            <div class="button-group">
                <button type="submit">Lập phiếu mượn</button>
                <button type="reset">Reset</button>
                <a href="return_form.php" class="btn">Trả sách</a>
            </div>
        </form>
    </div>
</body>
</html>
