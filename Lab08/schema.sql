-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th1 15, 2026 lúc 04:17 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `ql_thu_vien`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `category_id` int(11) NOT NULL,
  `publisher_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL CHECK (`price` > 0),
  `published_year` int(11) DEFAULT NULL,
  `stock` int(11) NOT NULL CHECK (`stock` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `books`
--

INSERT INTO `books` (`book_id`, `title`, `category_id`, `publisher_id`, `price`, `published_year`, `stock`) VALUES
(1, 'PHP Cơ Bản', 2, 1, 120000.00, 2022, 10),
(2, 'MySQL Nâng Cao', 2, 2, 150000.00, 2021, 8),
(3, 'Lập Trình Web', 2, 3, 180000.00, 2023, 5),
(4, 'Khoa học vui', 1, 1, 90000.00, 2020, 7),
(5, 'Vật lý phổ thông', 1, 2, 110000.00, 2019, 6),
(6, 'Toán tư duy', 1, 2, 130000.00, 2021, 9),
(7, 'Văn học Việt Nam', 3, 1, 95000.00, 2018, 10),
(8, 'Truyện ngắn chọn lọc', 3, 3, 105000.00, 2020, 4),
(9, 'Kỹ năng sống', 4, 1, 115000.00, 2022, 6),
(10, 'Quản trị kinh doanh', 4, 2, 200000.00, 2023, 5),
(11, 'Khởi nghiệp', 4, 3, 175000.00, 2021, 8),
(12, 'Truyện tranh A', 5, 1, 45000.00, 2020, 20),
(13, 'Truyện tranh B', 5, 1, 50000.00, 2021, 15),
(14, 'Truyện thiếu nhi', 5, 2, 60000.00, 2022, 12),
(15, 'Kể chuyện bé nghe', 5, 3, 55000.00, 2019, 18);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`category_id`, `name`) VALUES
(2, 'Công nghệ'),
(1, 'Khoa học'),
(4, 'Kinh tế'),
(5, 'Thiếu nhi'),
(3, 'Văn học');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loans`
--

CREATE TABLE `loans` (
  `loan_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `loan_date` date NOT NULL,
  `due_date` date NOT NULL,
  `status` varchar(30) DEFAULT 'BORROWING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `loans`
--

INSERT INTO `loans` (`loan_id`, `member_id`, `loan_date`, `due_date`, `status`) VALUES
(1, 1, '2024-01-01', '2024-01-10', 'RETURNED'),
(2, 2, '2024-01-05', '2024-01-15', 'BORROWING'),
(3, 3, '2024-01-07', '2024-01-17', 'BORROWING'),
(4, 4, '2024-01-10', '2024-01-20', 'RETURNED'),
(5, 5, '2024-01-12', '2024-01-22', 'BORROWING'),
(6, 6, '2024-01-15', '2024-01-25', 'BORROWING'),
(7, 7, '2024-01-18', '2024-01-28', 'RETURNED'),
(8, 8, '2024-01-20', '2024-01-30', 'BORROWING'),
(9, 1, '2024-02-01', '2024-02-10', 'BORROWING'),
(10, 2, '2024-02-03', '2024-02-12', 'BORROWING'),
(11, 3, '2024-02-05', '2024-02-15', 'BORROWING'),
(12, 4, '2024-02-07', '2024-02-17', 'BORROWING');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loan_items`
--

CREATE TABLE `loan_items` (
  `loan_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL CHECK (`qty` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `loan_items`
--

INSERT INTO `loan_items` (`loan_id`, `book_id`, `qty`) VALUES
(1, 1, 1),
(1, 2, 1),
(2, 3, 2),
(2, 4, 1),
(3, 5, 1),
(3, 6, 1),
(4, 7, 1),
(4, 8, 1),
(5, 9, 2),
(5, 10, 1),
(6, 11, 1),
(6, 12, 2),
(7, 13, 1),
(7, 14, 1),
(8, 15, 2),
(9, 1, 1),
(9, 3, 1),
(10, 4, 1),
(10, 6, 1),
(10, 10, 1),
(10, 11, 1),
(10, 12, 1),
(11, 7, 1),
(11, 9, 1),
(12, 10, 1),
(12, 11, 1),
(12, 12, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `members`
--

CREATE TABLE `members` (
  `member_id` int(11) NOT NULL,
  `full_name` varchar(120) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `members`
--

INSERT INTO `members` (`member_id`, `full_name`, `phone`, `created_at`) VALUES
(1, 'Nguyễn Văn An', '0901111111', '2026-01-15 22:08:45'),
(2, 'Trần Thị Bình', '0902222222', '2026-01-15 22:08:45'),
(3, 'Lê Văn Cường', '0903333333', '2026-01-15 22:08:45'),
(4, 'Phạm Thị Dung', '0904444444', '2026-01-15 22:08:45'),
(5, 'Hoàng Minh Đức', '0905555555', '2026-01-15 22:08:45'),
(6, 'Đỗ Thanh Hà', '0906666666', '2026-01-15 22:08:45'),
(7, 'Bùi Quốc Huy', '0907777777', '2026-01-15 22:08:45'),
(8, 'Võ Thị Lan', '0908888888', '2026-01-15 22:08:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `publishers`
--

CREATE TABLE `publishers` (
  `publisher_id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `publishers`
--

INSERT INTO `publishers` (`publisher_id`, `name`) VALUES
(2, 'NXB Giáo Dục'),
(3, 'NXB Lao Động'),
(1, 'NXB Trẻ');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `fk_books_category` (`category_id`),
  ADD KEY `fk_books_publisher` (`publisher_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `fk_loans_member` (`member_id`);

--
-- Chỉ mục cho bảng `loan_items`
--
ALTER TABLE `loan_items`
  ADD PRIMARY KEY (`loan_id`,`book_id`),
  ADD KEY `fk_items_book` (`book_id`);

--
-- Chỉ mục cho bảng `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Chỉ mục cho bảng `publishers`
--
ALTER TABLE `publishers`
  ADD PRIMARY KEY (`publisher_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `loans`
--
ALTER TABLE `loans`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `publishers`
--
ALTER TABLE `publishers`
  MODIFY `publisher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `fk_books_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `fk_books_publisher` FOREIGN KEY (`publisher_id`) REFERENCES `publishers` (`publisher_id`);

--
-- Các ràng buộc cho bảng `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `fk_loans_member` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`);

--
-- Các ràng buộc cho bảng `loan_items`
--
ALTER TABLE `loan_items`
  ADD CONSTRAINT `fk_items_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`),
  ADD CONSTRAINT `fk_items_loan` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`loan_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
