<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả đăng ký thẻ thư viện</title>
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
            width: 150px;
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
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background: #5568d3;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        session_start();
        
        // Kiểm tra xem có dữ liệu từ form không
        if (!isset($_SESSION['member_data'])) {
            header('Location: register_member.php');
            exit;
        }
        
        $data = $_SESSION['member_data'];
        
        // Lưu vào file CSV
        $dataDir = '../data';
        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0777, true);
        }
        
        $csvFile = $dataDir . '/members.csv';
        
        // Tạo header nếu file chưa tồn tại
        if (!file_exists($csvFile)) {
            $header = "Họ tên,Email,Số điện thoại,Ngày sinh,Giới tính,Địa chỉ\n";
            file_put_contents($csvFile, $header);
        }
        
        // Chuẩn bị dữ liệu để ghi vào CSV
        $csvData = [
            $data['ho_ten'],
            $data['email'],
            $data['so_dien_thoai'],
            $data['ngay_sinh'],
            $data['gioi_tinh'],
            $data['dia_chi']
        ];
        
        // Ghi vào file CSV
        $fp = fopen($csvFile, 'a');
        if ($fp) {
            fputcsv($fp, $csvData);
            fclose($fp);
        }
        
        // Xóa session data sau khi đã lưu
        unset($_SESSION['member_data']);
        ?>
        
        <h1>Đăng ký thành công!</h1>
        
        <div class="success-message">
            Thông tin của bạn đã được lưu thành công vào hệ thống.
        </div>
        
        <div class="info-section">
            <div class="info-row">
                <div class="info-label">Họ tên:</div>
                <div class="info-value"><?php echo htmlspecialchars($data['ho_ten']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value"><?php echo htmlspecialchars($data['email']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Số điện thoại:</div>
                <div class="info-value"><?php echo htmlspecialchars($data['so_dien_thoai']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Ngày sinh:</div>
                <div class="info-value"><?php echo htmlspecialchars($data['ngay_sinh']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Giới tính:</div>
                <div class="info-value"><?php echo htmlspecialchars($data['gioi_tinh']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Địa chỉ:</div>
                <div class="info-value"><?php echo !empty($data['dia_chi']) ? htmlspecialchars($data['dia_chi']) : '(Không có)'; ?></div>
            </div>
        </div>
        
        <div class="button-group">
            <a href="register_member.php" class="btn">Đăng ký thêm</a>
        </div>
    </div>
</body>
</html>
