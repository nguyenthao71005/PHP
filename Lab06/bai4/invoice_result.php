<?php
session_start();

// Kiểm tra xem có dữ liệu từ form không
if (!isset($_SESSION['invoice_data'])) {
    header('Location: invoice_form.php');
    exit;
}

$data = $_SESSION['invoice_data'];

// Lọc chỉ lấy các dòng hàng hợp lệ
$validItems = [];
$tam_tinh = 0;

foreach ($data['items'] as $item) {
    $ten_hang = trim($item['ten_hang']);
    $so_luong = floatval($item['so_luong']);
    $don_gia = floatval($item['don_gia']);
    
    if (!empty($ten_hang) && $so_luong > 0 && $don_gia > 0) {
        $thanh_tien = $so_luong * $don_gia;
        $validItems[] = [
            'ten_hang' => $ten_hang,
            'so_luong' => $so_luong,
            'don_gia' => $don_gia,
            'thanh_tien' => $thanh_tien
        ];
        $tam_tinh += $thanh_tien;
    }
}

// Tính toán
$giam_gia_percent = floatval($data['giam_gia']);
$tien_giam = $tam_tinh * ($giam_gia_percent / 100);
$tien_sau_giam = $tam_tinh - $tien_giam;

$vat_percent = floatval($data['vat']);
$tien_vat = $tien_sau_giam * ($vat_percent / 100);
$tong_thanh_toan = $tien_sau_giam + $tien_vat;

// Tạo mã hóa đơn và lưu file
$timestamp = time();
$invoiceNumber = 'HD' . date('YmdHis', $timestamp);

$invoiceData = [
    'ma_hoa_don' => $invoiceNumber,
    'ngay_tao' => date('Y-m-d H:i:s', $timestamp),
    'khach_hang' => [
        'ho_ten' => $data['ho_ten'],
        'email' => $data['email'],
        'so_dien_thoai' => $data['so_dien_thoai']
    ],
    'hang_hoa' => $validItems,
    'tam_tinh' => $tam_tinh,
    'giam_gia_percent' => $giam_gia_percent,
    'tien_giam' => $tien_giam,
    'vat_percent' => $vat_percent,
    'tien_vat' => $tien_vat,
    'tong_thanh_toan' => $tong_thanh_toan,
    'phuong_thuc_tt' => $data['phuong_thuc_tt']
];

// Lưu vào file
$invoicesDir = '../data/invoices';
if (!is_dir($invoicesDir)) {
    mkdir($invoicesDir, 0777, true);
}

$invoiceFile = $invoicesDir . '/invoice_' . $timestamp . '.json';
file_put_contents($invoiceFile, json_encode($invoiceData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Xóa session data
unset($_SESSION['invoice_data']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn bán hàng</title>
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
            padding: 40px;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #fee140;
        }
        .invoice-header h1 {
            color: #333;
            font-size: 32px;
            margin-bottom: 10px;
        }
        .invoice-header .invoice-number {
            color: #666;
            font-size: 14px;
        }
        .customer-info {
            margin-bottom: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .customer-info h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .info-row {
            display: flex;
            padding: 8px 0;
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
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th,
        .items-table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .items-table th {
            background: #fee140;
            font-weight: 600;
            color: #333;
        }
        .items-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        .items-table tbody tr:hover {
            background: #e9ecef;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary-section {
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .summary-row:last-child {
            border-bottom: none;
            border-top: 2px solid #333;
            margin-top: 10px;
            padding-top: 15px;
        }
        .summary-label {
            font-weight: 500;
            color: #495057;
        }
        .summary-value {
            font-weight: 600;
            color: #212529;
        }
        .summary-row:last-child .summary-label,
        .summary-row:last-child .summary-value {
            font-size: 18px;
            color: #fa709a;
        }
        .payment-method {
            margin-top: 20px;
            padding: 15px;
            background: #fff3cd;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
        }
        .payment-method strong {
            color: #856404;
        }
        .button-group {
            text-align: center;
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            padding: 12px 40px;
            background: #fa709a;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin: 0 5px;
        }
        .btn:hover {
            background: #e85d8a;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .container {
                box-shadow: none;
            }
            .button-group {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        
        <div class="invoice-header">
            <h1>HÓA ĐƠN BÁN HÀNG</h1>
            <div class="invoice-number">Mã hóa đơn: <?php echo htmlspecialchars($invoiceNumber); ?></div>
            <div class="invoice-number">Ngày tạo: <?php echo date('d/m/Y H:i:s', $timestamp); ?></div>
        </div>
        
        <div class="customer-info">
            <h3>Thông tin khách hàng</h3>
            <div class="info-row">
                <div class="info-label">Họ tên:</div>
                <div class="info-value"><?php echo htmlspecialchars($data['ho_ten']); ?></div>
            </div>
            <?php if (!empty($data['email'])): ?>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value"><?php echo htmlspecialchars($data['email']); ?></div>
            </div>
            <?php endif; ?>
            <div class="info-row">
                <div class="info-label">Số điện thoại:</div>
                <div class="info-value"><?php echo htmlspecialchars($data['so_dien_thoai']); ?></div>
            </div>
        </div>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">STT</th>
                    <th style="width: 40%;">Mặt hàng</th>
                    <th style="width: 15%;" class="text-center">Số lượng</th>
                    <th style="width: 20%;" class="text-right">Đơn giá</th>
                    <th style="width: 20%;" class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($validItems as $index => $item): ?>
                <tr>
                    <td class="text-center"><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($item['ten_hang']); ?></td>
                    <td class="text-center"><?php echo number_format($item['so_luong'], 0, ',', '.'); ?></td>
                    <td class="text-right"><?php echo number_format($item['don_gia'], 0, ',', '.'); ?> đ</td>
                    <td class="text-right"><?php echo number_format($item['thanh_tien'], 0, ',', '.'); ?> đ</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="summary-section">
            <div class="summary-row">
                <div class="summary-label">Tạm tính:</div>
                <div class="summary-value"><?php echo number_format($tam_tinh, 0, ',', '.'); ?> đ</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Giảm giá (<?php echo number_format($giam_gia_percent, 1); ?>%):</div>
                <div class="summary-value">-<?php echo number_format($tien_giam, 0, ',', '.'); ?> đ</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Sau giảm giá:</div>
                <div class="summary-value"><?php echo number_format($tien_sau_giam, 0, ',', '.'); ?> đ</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">VAT (<?php echo number_format($vat_percent, 1); ?>%):</div>
                <div class="summary-value">+<?php echo number_format($tien_vat, 0, ',', '.'); ?> đ</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Tổng thanh toán:</div>
                <div class="summary-value"><?php echo number_format($tong_thanh_toan, 0, ',', '.'); ?> đ</div>
            </div>
        </div>
        
        <div class="payment-method">
            <strong>Phương thức thanh toán:</strong> <?php echo htmlspecialchars($data['phuong_thuc_tt']); ?>
        </div>
        
        <div class="button-group">
            <a href="invoice_form.php" class="btn">Tạo hóa đơn mới</a>
            <button onclick="window.print()" class="btn btn-secondary">In hóa đơn</button>
        </div>
    </div>
</body>
</html>
