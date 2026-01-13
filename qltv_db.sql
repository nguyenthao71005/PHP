-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th1 13, 2026 lúc 09:05 AM
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
-- Cơ sở dữ liệu: `qltv_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `books`
--

CREATE TABLE `books` (
  `ID` int(11) NOT NULL,
  `TITLE` varchar(200) NOT NULL,
  `AUTHOR` varchar(100) NOT NULL,
  `PRICE` decimal(10,2) NOT NULL,
  `PUBLISHED_YEAR` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `books`
--

INSERT INTO `books` (`ID`, `TITLE`, `AUTHOR`, `PRICE`, `PUBLISHED_YEAR`) VALUES
(1, 'Lập trình PHP', 'Nguyễn Văn A', 85000.00, 2021),
(2, 'Cấu trúc dữ liệu và giải thuật', 'Trần Văn B', 120000.00, 2020),
(3, 'Cơ sở dữ liệu', 'Lê Thị C', 104500.00, 2019),
(4, 'Lập trình hướng đối tượng', 'Phạm Văn D', 110000.00, 2022),
(5, 'Thiết kế Web', 'Hoàng Thị E', 99000.00, 2018);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `borrows`
--

CREATE TABLE `borrows` (
  `ID` int(11) NOT NULL,
  `READER_ID` int(11) NOT NULL,
  `BOOK_ID` int(11) NOT NULL,
  `BORROW_DATE` date NOT NULL,
  `RETURN_DATE` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `borrows`
--

INSERT INTO `borrows` (`ID`, `READER_ID`, `BOOK_ID`, `BORROW_DATE`, `RETURN_DATE`) VALUES
(1, 1, 1, '2024-10-01', '2024-10-10'),
(2, 2, 2, '2024-10-03', '2024-10-15'),
(3, 3, 3, '2024-10-05', NULL),
(4, 1, 4, '2024-10-07', NULL),
(5, 2, 5, '2024-10-08', '2024-10-20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `readers`
--

CREATE TABLE `readers` (
  `ID` int(11) NOT NULL,
  `FULL_NAME` varchar(120) NOT NULL,
  `PHONE` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `readers`
--

INSERT INTO `readers` (`ID`, `FULL_NAME`, `PHONE`) VALUES
(1, 'Nguyễn Phương Thảo', '0901234567'),
(2, 'Bạch Hoàng Dương', '0912345678'),
(3, 'Đào Phúc Việt', '0923456789');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `borrows`
--
ALTER TABLE `borrows`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_BORROWS_READER` (`READER_ID`),
  ADD KEY `FK_BORROWS_BOOK` (`BOOK_ID`);

--
-- Chỉ mục cho bảng `readers`
--
ALTER TABLE `readers`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `books`
--
ALTER TABLE `books`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `borrows`
--
ALTER TABLE `borrows`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `readers`
--
ALTER TABLE `readers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `borrows`
--
ALTER TABLE `borrows`
  ADD CONSTRAINT `FK_BORROWS_BOOK` FOREIGN KEY (`BOOK_ID`) REFERENCES `books` (`ID`),
  ADD CONSTRAINT `FK_BORROWS_READER` FOREIGN KEY (`READER_ID`) REFERENCES `readers` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
