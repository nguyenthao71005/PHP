<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xử lý trả sách</title>
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
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            padding: 30px;
        }
        h1 {
            color: #28a745;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }
        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 30px;
            color: #155724;
            text-align: center;
            font-weight: 500;
        }
        .info-section {
            background: #f8f9fa;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
            width: 180px;
            flex-shrink: 0;
        }
        .info-value {
            color: #212529;
            flex: 1;
        }
        .button-group {
            text-align: center;
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #4facfe;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin: 0 5px;
        }
        .btn:hover {
            background: #3d8bfe;
        }
        .error-message {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            color: #721c24;
            text-align: center;
        }
        .warning-message {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            color: #856404;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        session_start();
        
        // Kiểm tra xem có dữ liệu từ form không
        if (!isset($_SESSION['return_data'])) {
            header('Location: return_form.php');
            exit;
        }
        
        $data = $_SESSION['return_data'];
        $errors = [];
        $borrow = null;
        
        // Đọc borrows.json
        $borrowsFile = '../data/borrows.json';
        $borrows = [];
        
        if (file_exists($borrowsFile)) {
            $jsonContent = file_get_contents($borrowsFile);
            $borrows = json_decode($jsonContent, true);
            if ($borrows === null) {
                $borrows = [];
            }
        } else {
            $errors[] = 'Chưa có phiếu mượn nào trong hệ thống';
        }
        
        // Tìm phiếu mượn
        if (empty($errors)) {
            if ($data['search_type'] === 'ma_phieu') {
                // Tìm theo mã phiếu
                foreach ($borrows as $index => $borrowItem) {
                    if (isset($borrowItem['ma_phieu']) && $borrowItem['ma_phieu'] === $data['ma_phieu']) {
                        $borrow = $borrowItem;
                        $borrowIndex = $index;
                        break;
                    }
                }
                
                if ($borrow === null) {
                    $errors[] = 'Không tìm thấy phiếu mượn với mã: ' . htmlspecialchars($data['ma_phieu']);
                }
            } else {
                // Tìm theo mã TV + mã sách
                foreach ($borrows as $index => $borrowItem) {
                    if (isset($borrowItem['ma_tv']) && $borrowItem['ma_tv'] === $data['ma_tv'] &&
                        isset($borrowItem['ma_sach']) && $borrowItem['ma_sach'] === $data['ma_sach']) {
                        $borrow = $borrowItem;
                        $borrowIndex = $index;
                        break;
                    }
                }
                
                if ($borrow === null) {
                    $errors[] = 'Không tìm thấy phiếu mượn với mã TV: ' . htmlspecialchars($data['ma_tv']) . ' và mã sách: ' . htmlspecialchars($data['ma_sach']);
                }
            }
        }
        
        // Kiểm tra trạng thái
        if ($borrow !== null) {
            if (!isset($borrow['trang_thai']) || $borrow['trang_thai'] !== 'Đang mượn') {
                $errors[] = 'Phiếu mượn này đã được trả (trạng thái: ' . (isset($borrow['trang_thai']) ? htmlspecialchars($borrow['trang_thai']) : 'Không xác định') . ')';
                $borrow = null;
            }
        }
        
        // Nếu không có lỗi, cập nhật phiếu mượn và tăng số lượng sách
        if (empty($errors) && $borrow !== null) {
            // Cập nhật trạng thái phiếu mượn
            $borrows[$borrowIndex]['trang_thai'] = 'Đã trả';
            if (isset($data['ngay_tra'])) {
                $borrows[$borrowIndex]['ngay_tra'] = $data['ngay_tra'];
            }
            
            // Ghi borrows.json
            file_put_contents($borrowsFile, json_encode($borrows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            // Đọc books.json và tăng số lượng
            $booksFile = '../data/books.json';
            $books = [];
            $bookFound = false;
            
            if (file_exists($booksFile)) {
                $jsonContent = file_get_contents($booksFile);
                $books = json_decode($jsonContent, true);
                if ($books !== null && is_array($books)) {
                    foreach ($books as $index => $book) {
                        if (isset($book['ma_sach']) && $book['ma_sach'] === $borrow['ma_sach']) {
                            $bookFound = true;
                            $books[$index]['so_luong'] = isset($book['so_luong']) ? intval($book['so_luong']) + 1 : 1;
                            break;
                        }
                    }
                }
            }
            
            if ($bookFound) {
                // Ghi books.json
                file_put_contents($booksFile, json_encode($books, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
            
            // Xóa session data
            unset($_SESSION['return_data']);
            
            // Hiển thị kết quả
            ?>
            <h1>Trả sách thành công!</h1>
            
            <div class="success-message">
                Sách đã được trả thành công. Số lượng sách đã được cập nhật.
            </div>
            
            <div class="info-section">
                <h3 style="margin-bottom: 15px; color: #333;">Tóm tắt phiếu mượn:</h3>
                
                <div class="info-row">
                    <div class="info-label">Mã phiếu:</div>
                    <div class="info-value"><?php echo htmlspecialchars($borrow['ma_phieu'] ?? ''); ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Mã thành viên:</div>
                    <div class="info-value"><?php echo htmlspecialchars($borrow['ma_tv'] ?? ''); ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Mã sách:</div>
                    <div class="info-value"><?php echo htmlspecialchars($borrow['ma_sach'] ?? ''); ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Ngày mượn:</div>
                    <div class="info-value"><?php echo htmlspecialchars($borrow['ngay_muon'] ?? ''); ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Hạn trả:</div>
                    <div class="info-value"><?php echo htmlspecialchars($borrow['han_tra'] ?? ''); ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Ngày trả:</div>
                    <div class="info-value"><?php echo htmlspecialchars($data['ngay_tra'] ?? ''); ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Trạng thái:</div>
                    <div class="info-value">Đã trả</div>
                </div>
            </div>
            
            <div class="button-group">
                <a href="return_form.php" class="btn">Trả sách tiếp</a>
                <a href="borrow_form.php" class="btn">Mượn sách</a>
            </div>
            <?php
        } else {
            // Có lỗi
            unset($_SESSION['return_data']);
            ?>
            <h1>Lỗi xử lý</h1>
            
            <div class="error-message">
                <?php
                foreach ($errors as $error) {
                    echo htmlspecialchars($error) . '<br>';
                }
                ?>
            </div>
            
            <div class="button-group">
                <a href="return_form.php" class="btn">Quay lại</a>
            </div>
            <?php
        }
        ?>
    </div>
</body>
</html>
