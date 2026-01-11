<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm kiếm sách</title>
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
        .search-form {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
        }
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 500;
            font-size: 14px;
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
            border-color: #667eea;
        }
        .button-group {
            text-align: center;
            margin-top: 20px;
        }
        button[type="submit"],
        a.btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
            margin: 0 5px;
        }
        button[type="submit"] {
            background: #667eea;
            color: white;
        }
        button[type="submit"]:hover {
            background: #5568d3;
        }
        a.btn {
            background: #6c757d;
            color: white;
        }
        a.btn:hover {
            background: #5a6268;
        }
        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #667eea;
        }
        .results-count {
            font-size: 16px;
            color: #666;
            font-weight: 500;
        }
        .results-count strong {
            color: #667eea;
        }
        .no-results {
            text-align: center;
            padding: 60px 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px dashed #dee2e6;
        }
        .no-results-icon {
            font-size: 64px;
            margin-bottom: 20px;
            color: #adb5bd;
        }
        .no-results h3 {
            color: #495057;
            margin-bottom: 10px;
            font-size: 20px;
        }
        .no-results p {
            color: #6c757d;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }
        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        th {
            font-weight: 600;
            font-size: 14px;
        }
        tbody tr:hover {
            background: #f8f9fa;
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
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            table {
                font-size: 12px;
            }
            th, td {
                padding: 8px;
            }
            .results-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tìm kiếm sách</h1>
        
        <?php
        // Lấy dữ liệu từ GET
        $kw = trim($_GET['kw'] ?? '');
        $category = $_GET['category'] ?? 'all';
        $year_from = trim($_GET['year_from'] ?? '');
        $year_to = trim($_GET['year_to'] ?? '');
        
        // Đọc dữ liệu từ books.json
        $booksFile = '../data/books.json';
        $allBooks = [];
        
        if (file_exists($booksFile)) {
            $jsonContent = file_get_contents($booksFile);
            $allBooks = json_decode($jsonContent, true);
            if ($allBooks === null || !is_array($allBooks)) {
                $allBooks = [];
            }
        }
        
        // Lọc dữ liệu
        $filteredBooks = [];
        
        foreach ($allBooks as $book) {
            $match = true;
            
            // Lọc theo từ khóa (tìm trong tên sách, tác giả, mã sách)
            if (!empty($kw)) {
                $searchText = strtolower($kw);
                $bookTitle = isset($book['ten_sach']) ? strtolower($book['ten_sach']) : '';
                $bookAuthor = isset($book['tac_gia']) ? strtolower($book['tac_gia']) : '';
                $bookCode = isset($book['ma_sach']) ? strtolower($book['ma_sach']) : '';
                
                if (strpos($bookTitle, $searchText) === false && 
                    strpos($bookAuthor, $searchText) === false && 
                    strpos($bookCode, $searchText) === false) {
                    $match = false;
                }
            }
            
            // Lọc theo thể loại
            if ($match && $category !== 'all') {
                $bookCategory = isset($book['the_loai']) ? $book['the_loai'] : '';
                if ($bookCategory !== $category) {
                    $match = false;
                }
            }
            
            // Lọc theo năm từ
            if ($match && !empty($year_from)) {
                $bookYear = isset($book['nam_xuat_ban']) ? intval($book['nam_xuat_ban']) : 0;
                if ($bookYear < intval($year_from)) {
                    $match = false;
                }
            }
            
            // Lọc theo năm đến
            if ($match && !empty($year_to)) {
                $bookYear = isset($book['nam_xuat_ban']) ? intval($book['nam_xuat_ban']) : 0;
                if ($bookYear > intval($year_to)) {
                    $match = false;
                }
            }
            
            if ($match) {
                $filteredBooks[] = $book;
            }
        }
        ?>
        
        <form method="GET" action="" class="search-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="kw">Từ khóa</label>
                    <input type="text" 
                           id="kw" 
                           name="kw" 
                           value="<?php echo htmlspecialchars($kw); ?>"
                           placeholder="Tên sách, tác giả, mã sách...">
                </div>
                
                <div class="form-group">
                    <label for="category">Thể loại</label>
                    <select id="category" name="category">
                        <option value="all" <?php echo $category === 'all' ? 'selected' : ''; ?>>Tất cả</option>
                        <option value="Giáo trình" <?php echo $category === 'Giáo trình' ? 'selected' : ''; ?>>Giáo trình</option>
                        <option value="Kỹ năng" <?php echo $category === 'Kỹ năng' ? 'selected' : ''; ?>>Kỹ năng</option>
                        <option value="Văn học" <?php echo $category === 'Văn học' ? 'selected' : ''; ?>>Văn học</option>
                        <option value="Khoa học" <?php echo $category === 'Khoa học' ? 'selected' : ''; ?>>Khoa học</option>
                        <option value="Khác" <?php echo $category === 'Khác' ? 'selected' : ''; ?>>Khác</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="year_from">Năm xuất bản từ</label>
                    <input type="number" 
                           id="year_from" 
                           name="year_from" 
                           value="<?php echo htmlspecialchars($year_from); ?>"
                           placeholder="Năm bắt đầu"
                           min="1900"
                           max="<?php echo date('Y'); ?>">
                </div>
                
                <div class="form-group">
                    <label for="year_to">Năm xuất bản đến</label>
                    <input type="number" 
                           id="year_to" 
                           name="year_to" 
                           value="<?php echo htmlspecialchars($year_to); ?>"
                           placeholder="Năm kết thúc"
                           min="1900"
                           max="<?php echo date('Y'); ?>">
                </div>
            </div>
            
            <div class="button-group">
                <button type="submit">Tìm kiếm</button>
                <a href="search.php" class="btn">Xóa bộ lọc</a>
            </div>
        </form>
        
        <?php if (isset($_GET['kw']) || isset($_GET['category']) || isset($_GET['year_from']) || isset($_GET['year_to'])): ?>
        <div class="results-header">
            <div class="results-count">
                Tìm thấy <strong><?php echo count($filteredBooks); ?></strong> kết quả
            </div>
        </div>
        
        <?php if (empty($filteredBooks)): ?>
        <div class="no-results">
            <div class="no-results-icon">🔍</div>
            <h3>Không tìm thấy kết quả</h3>
            <p>Không có sách nào phù hợp với điều kiện tìm kiếm của bạn. Vui lòng thử lại với từ khóa hoặc bộ lọc khác.</p>
        </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">STT</th>
                    <th style="width: 15%;">Mã sách</th>
                    <th style="width: 25%;">Tên sách</th>
                    <th style="width: 20%;">Tác giả</th>
                    <th style="width: 10%;" class="text-center">Năm XB</th>
                    <th style="width: 15%;">Thể loại</th>
                    <th style="width: 10%;" class="text-center">Số lượng</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filteredBooks as $index => $book): ?>
                <tr>
                    <td class="text-center"><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($book['ma_sach'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($book['ten_sach'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($book['tac_gia'] ?? ''); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($book['nam_xuat_ban'] ?? ''); ?></td>
                    <td>
                        <span class="badge badge-success"><?php echo htmlspecialchars($book['the_loai'] ?? ''); ?></span>
                    </td>
                    <td class="text-center">
                        <?php
                        $soLuong = isset($book['so_luong']) ? intval($book['so_luong']) : 0;
                        $badgeClass = 'badge-success';
                        if ($soLuong < 5) {
                            $badgeClass = 'badge-danger';
                        } elseif ($soLuong < 10) {
                            $badgeClass = 'badge-warning';
                        }
                        ?>
                        <span class="badge <?php echo $badgeClass; ?>"><?php echo $soLuong; ?></span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
        <?php else: ?>
        <div class="no-results">
            <div class="no-results-icon">📚</div>
            <h3>Chào mừng đến với trang tìm kiếm sách</h3>
            <p>Vui lòng nhập từ khóa hoặc chọn bộ lọc để bắt đầu tìm kiếm.</p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
