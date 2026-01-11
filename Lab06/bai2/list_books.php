<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sách</title>
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
            max-width: 1200px;
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
        .header-actions {
            text-align: right;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #11998e;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            font-size: 14px;
        }
        .btn:hover {
            background: #0e7a71;
        }
        .empty-message {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        thead {
            background: #11998e;
            color: white;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            font-weight: 600;
        }
        tbody tr:hover {
            background: #f5f5f5;
        }
        tbody tr:last-child td {
            border-bottom: none;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        @media (max-width: 768px) {
            table {
                font-size: 12px;
            }
            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Danh sách sách trong kho</h1>
        
        <div class="header-actions">
            <a href="add_book.php" class="btn">Thêm sách mới</a>
        </div>
        
        <?php
        $booksFile = '../data/books.json';
        $books = [];
        
        if (file_exists($booksFile)) {
            $jsonContent = file_get_contents($booksFile);
            $books = json_decode($jsonContent, true);
            if ($books === null) {
                $books = [];
            }
        }
        
        if (empty($books)) {
            echo '<div class="empty-message">Chưa có sách nào trong kho. <a href="add_book.php">Thêm sách mới</a></div>';
        } else {
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Mã sách</th>';
            echo '<th>Tên sách</th>';
            echo '<th>Tác giả</th>';
            echo '<th class="text-center">Năm xuất bản</th>';
            echo '<th>Thể loại</th>';
            echo '<th class="text-right">Số lượng</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            
            foreach ($books as $book) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($book['ma_sach'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($book['ten_sach'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($book['tac_gia'] ?? '') . '</td>';
                echo '<td class="text-center">' . htmlspecialchars($book['nam_xuat_ban'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($book['the_loai'] ?? '') . '</td>';
                echo '<td class="text-right">' . htmlspecialchars($book['so_luong'] ?? '0') . '</td>';
                echo '</tr>';
            }
            
            echo '</tbody>';
            echo '</table>';
        }
        ?>
    </div>
</body>
</html>
