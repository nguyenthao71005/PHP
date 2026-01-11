<?php
session_start();
$errors = [];
$data = [];

// Lấy dữ liệu từ session nếu có (để giữ lại khi có lỗi)
if (isset($_SESSION['invoice_data'])) {
    $data = $_SESSION['invoice_data'];
    unset($_SESSION['invoice_data']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu khách hàng
    $data['ho_ten'] = isset($_POST['ho_ten']) ? trim($_POST['ho_ten']) : '';
    $data['email'] = isset($_POST['email']) ? trim($_POST['email']) : '';
    $data['so_dien_thoai'] = isset($_POST['so_dien_thoai']) ? trim($_POST['so_dien_thoai']) : '';
    
    // Lấy dữ liệu hàng hóa (không giới hạn số lượng)
    $data['items'] = [];
    if (isset($_POST['ten_hang']) && is_array($_POST['ten_hang'])) {
        $itemCount = count($_POST['ten_hang']);
        for ($i = 0; $i < $itemCount; $i++) {
            $ten_hang = isset($_POST['ten_hang'][$i]) ? trim($_POST['ten_hang'][$i]) : '';
            $so_luong = isset($_POST['so_luong'][$i]) ? trim($_POST['so_luong'][$i]) : '';
            $don_gia = isset($_POST['don_gia'][$i]) ? trim($_POST['don_gia'][$i]) : '';
            
            $data['items'][] = [
                'ten_hang' => $ten_hang,
                'so_luong' => $so_luong,
                'don_gia' => $don_gia
            ];
        }
    }
    
    // Lấy dữ liệu khác
    $data['giam_gia'] = isset($_POST['giam_gia']) ? trim($_POST['giam_gia']) : '0';
    $data['vat'] = isset($_POST['vat']) ? trim($_POST['vat']) : '0';
    $data['phuong_thuc_tt'] = isset($_POST['phuong_thuc_tt']) ? $_POST['phuong_thuc_tt'] : '';
    
    // Validation
    if (empty($data['ho_ten'])) {
        $errors[] = 'Họ tên khách hàng là bắt buộc';
    }
    
    if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email không đúng định dạng';
    }
    
    if (empty($data['so_dien_thoai'])) {
        $errors[] = 'Số điện thoại là bắt buộc';
    }
    
    // Kiểm tra ít nhất 1 dòng hàng hợp lệ
    $validItems = 0;
    foreach ($data['items'] as $index => $item) {
        $ten_hang = $item['ten_hang'];
        $so_luong = floatval($item['so_luong']);
        $don_gia = floatval($item['don_gia']);
        
        if (!empty($ten_hang) && $so_luong > 0 && $don_gia > 0) {
            $validItems++;
        } elseif (!empty($ten_hang) || !empty($item['so_luong']) || !empty($item['don_gia'])) {
            // Nếu có điền nhưng không hợp lệ
            $errors[] = "Dòng hàng " . ($index + 1) . ": Tên hàng không được rỗng, số lượng và đơn giá phải lớn hơn 0";
        }
    }
    
    if ($validItems === 0) {
        $errors[] = 'Cần ít nhất 1 dòng hàng hợp lệ (tên hàng không rỗng, số lượng > 0, đơn giá > 0)';
    }
    
    // Kiểm tra giảm giá
    $giam_gia = floatval($data['giam_gia']);
    if ($giam_gia < 0 || $giam_gia > 30) {
        $errors[] = 'Giảm giá phải từ 0% đến 30%';
    }
    
    // Kiểm tra VAT
    $vat = floatval($data['vat']);
    if ($vat < 0 || $vat > 15) {
        $errors[] = 'VAT phải từ 0% đến 15%';
    }
    
    if (empty($data['phuong_thuc_tt'])) {
        $errors[] = 'Vui lòng chọn phương thức thanh toán';
    }
    
    // Nếu không có lỗi, chuyển đến trang kết quả
    if (empty($errors)) {
        $_SESSION['invoice_data'] = $data;
        header('Location: invoice_result.php');
        exit;
    } else {
        // Giữ lại dữ liệu khi có lỗi
        $_SESSION['invoice_data'] = $data;
    }
}

        // Khởi tạo dữ liệu mặc định nếu chưa có (ít nhất 3 dòng)
        if (!isset($data['items']) || empty($data['items'])) {
            $data['items'] = [
                ['ten_hang' => '', 'so_luong' => '', 'don_gia' => ''],
                ['ten_hang' => '', 'so_luong' => '', 'don_gia' => ''],
                ['ten_hang' => '', 'so_luong' => '', 'don_gia' => '']
            ];
        } else {
            // Đảm bảo ít nhất có 3 dòng
            while (count($data['items']) < 3) {
                $data['items'][] = ['ten_hang' => '', 'so_luong' => '', 'don_gia' => ''];
            }
        }

if (!isset($data['giam_gia'])) {
    $data['giam_gia'] = '0';
}

if (!isset($data['vat'])) {
    $data['vat'] = '0';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo hóa đơn bán hàng</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 900px;
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
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #555;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #fee140;
        }
        .form-group {
            margin-bottom: 15px;
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
            border-color: #fa709a;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .items-table th,
        .items-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .items-table th {
            background: #fee140;
            font-weight: 600;
            color: #333;
        }
        .items-table input {
            width: 100%;
            border: none;
            padding: 8px;
        }
        .items-table input:focus {
            outline: 2px solid #fa709a;
        }
        .items-table .btn-delete {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
        .items-table .btn-delete:hover {
            background: #c82333;
        }
        .btn-add-item {
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }
        .btn-add-item:hover {
            background: #218838;
        }
        .items-table .text-center {
            text-align: center;
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
            width: auto;
            margin-right: 5px;
            cursor: pointer;
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
            text-align: center;
            margin-top: 30px;
        }
        button[type="submit"],
        button[type="reset"] {
            padding: 12px 40px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin: 0 5px;
        }
        button[type="submit"] {
            background: #fa709a;
            color: white;
        }
        button[type="submit"]:hover {
            background: #e85d8a;
        }
        button[type="reset"] {
            background: #6c757d;
            color: white;
        }
        button[type="reset"]:hover {
            background: #5a6268;
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
        <h1>Tạo hóa đơn bán hàng</h1>
        
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
            <div class="section">
                <div class="section-title">Thông tin khách hàng</div>
                
                <div class="form-group">
                    <label for="ho_ten">Họ tên <span class="required">*</span></label>
                    <input type="text" 
                           id="ho_ten" 
                           name="ho_ten" 
                           value="<?php echo isset($data['ho_ten']) ? htmlspecialchars($data['ho_ten']) : ''; ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="<?php echo isset($data['email']) ? htmlspecialchars($data['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="so_dien_thoai">Số điện thoại <span class="required">*</span></label>
                    <input type="text" 
                           id="so_dien_thoai" 
                           name="so_dien_thoai" 
                           value="<?php echo isset($data['so_dien_thoai']) ? htmlspecialchars($data['so_dien_thoai']) : ''; ?>" 
                           required>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">Danh sách hàng hóa</div>
                
                <table class="items-table" id="items-table">
                    <thead>
                        <tr>
                            <th style="width: 35%;">Tên hàng</th>
                            <th style="width: 15%;">Số lượng</th>
                            <th style="width: 15%;">Đơn giá</th>
                            <th style="width: 20%;">Thành tiền</th>
                            <th style="width: 15%;" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="items-tbody">
                        <?php 
                        $itemCount = count($data['items']);
                        for ($i = 0; $i < $itemCount; $i++): 
                        ?>
                        <tr class="item-row">
                            <td>
                                <input type="text" 
                                       name="ten_hang[]" 
                                       class="item-name"
                                       value="<?php echo isset($data['items'][$i]['ten_hang']) ? htmlspecialchars($data['items'][$i]['ten_hang']) : ''; ?>"
                                       placeholder="Tên hàng hóa">
                            </td>
                            <td>
                                <input type="number" 
                                       name="so_luong[]" 
                                       class="item-quantity"
                                       value="<?php echo isset($data['items'][$i]['so_luong']) ? htmlspecialchars($data['items'][$i]['so_luong']) : ''; ?>"
                                       step="0.01"
                                       min="0"
                                       placeholder="SL"
                                       oninput="calculateTotal(this)">
                            </td>
                            <td>
                                <input type="number" 
                                       name="don_gia[]" 
                                       class="item-price"
                                       value="<?php echo isset($data['items'][$i]['don_gia']) ? htmlspecialchars($data['items'][$i]['don_gia']) : ''; ?>"
                                       step="0.01"
                                       min="0"
                                       placeholder="Đơn giá"
                                       oninput="calculateTotal(this)">
                            </td>
                            <td class="item-total" style="text-align: right; padding: 10px;">
                                <?php
                                $sl = isset($data['items'][$i]['so_luong']) ? floatval($data['items'][$i]['so_luong']) : 0;
                                $dg = isset($data['items'][$i]['don_gia']) ? floatval($data['items'][$i]['don_gia']) : 0;
                                $thanh_tien = $sl * $dg;
                                if ($thanh_tien > 0) {
                                    echo number_format($thanh_tien, 0, ',', '.') . ' đ';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <?php if ($i > 0): ?>
                                <button type="button" class="btn-delete" onclick="removeItem(this)">Xóa</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
                <button type="button" class="btn-add-item" onclick="addItem()">+ Thêm hàng</button>
            </div>
            
            <div class="section">
                <div class="section-title">Thông tin thanh toán</div>
                
                <div class="form-group">
                    <label for="giam_gia">Giảm giá (%)</label>
                    <input type="number" 
                           id="giam_gia" 
                           name="giam_gia" 
                           value="<?php echo isset($data['giam_gia']) ? htmlspecialchars($data['giam_gia']) : '0'; ?>"
                           min="0" 
                           max="30" 
                           step="0.01"
                           required>
                    <div class="info-text">Từ 0% đến 30%</div>
                </div>
                
                <div class="form-group">
                    <label for="vat">VAT (%)</label>
                    <input type="number" 
                           id="vat" 
                           name="vat" 
                           value="<?php echo isset($data['vat']) ? htmlspecialchars($data['vat']) : '0'; ?>"
                           min="0" 
                           max="15" 
                           step="0.01"
                           required>
                    <div class="info-text">Từ 0% đến 15%</div>
                </div>
                
                <div class="form-group">
                    <label>Phương thức thanh toán <span class="required">*</span></label>
                    <div class="radio-group">
                        <label>
                            <input type="radio" 
                                   name="phuong_thuc_tt" 
                                   value="Tiền mặt" 
                                   <?php echo (isset($data['phuong_thuc_tt']) && $data['phuong_thuc_tt'] === 'Tiền mặt') ? 'checked' : ''; ?>
                                   required>
                            Tiền mặt
                        </label>
                        <label>
                            <input type="radio" 
                                   name="phuong_thuc_tt" 
                                   value="Chuyển khoản" 
                                   <?php echo (isset($data['phuong_thuc_tt']) && $data['phuong_thuc_tt'] === 'Chuyển khoản') ? 'checked' : ''; ?>>
                            Chuyển khoản
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="button-group">
                <button type="submit">Tạo hóa đơn</button>
                <button type="reset">Reset</button>
            </div>
        </form>
    </div>
    
    <script>
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
        
        function calculateTotal(input) {
            const row = input.closest('tr');
            const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            const total = quantity * price;
            const totalCell = row.querySelector('.item-total');
            
            if (total > 0) {
                totalCell.textContent = formatNumber(Math.round(total)) + ' đ';
            } else {
                totalCell.textContent = '';
            }
        }
        
        function addItem() {
            const tbody = document.getElementById('items-tbody');
            const newRow = document.createElement('tr');
            newRow.className = 'item-row';
            newRow.innerHTML = `
                <td>
                    <input type="text" name="ten_hang[]" class="item-name" placeholder="Tên hàng hóa">
                </td>
                <td>
                    <input type="number" name="so_luong[]" class="item-quantity" step="0.01" min="0" placeholder="SL" oninput="calculateTotal(this)">
                </td>
                <td>
                    <input type="number" name="don_gia[]" class="item-price" step="0.01" min="0" placeholder="Đơn giá" oninput="calculateTotal(this)">
                </td>
                <td class="item-total" style="text-align: right; padding: 10px;"></td>
                <td class="text-center">
                    <button type="button" class="btn-delete" onclick="removeItem(this)">Xóa</button>
                </td>
            `;
            tbody.appendChild(newRow);
        }
        
        function removeItem(btn) {
            const tbody = document.getElementById('items-tbody');
            const rowCount = tbody.querySelectorAll('.item-row').length;
            if (rowCount > 1) {
                btn.closest('tr').remove();
            } else {
                alert('Phải có ít nhất 1 dòng hàng hóa!');
            }
        }
    </script>
</body>
</html>
