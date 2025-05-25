-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 25, 2025 lúc 08:52 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `maytinhpro`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdonhang`
--

CREATE TABLE `chitietdonhang` (
  `id` int(11) NOT NULL,
  `donhang_id` int(11) DEFAULT NULL,
  `sanpham_id` int(11) DEFAULT NULL,
  `soluong` int(11) NOT NULL,
  `gia` decimal(15,2) NOT NULL,
  `tong` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`id`, `donhang_id`, `sanpham_id`, `soluong`, `gia`, `tong`) VALUES
(1, 1, 1, 1, 32990000.00, 32990000.00),
(2, 2, 3, 1, 28990000.00, 28990000.00),
(3, 3, 2, 1, 18000000.00, 18000000.00),
(4, 4, 7, 1, 12000000.00, 12000000.00),
(5, 4, 6, 1, 30000000.00, 30000000.00),
(6, 4, 3, 2, 28000000.00, 56000000.00),
(7, 4, 2, 1, 18000000.00, 18000000.00),
(8, 5, 1, 1, 32000000.00, 32000000.00),
(9, 5, 2, 1, 18000000.00, 18000000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhgia`
--

CREATE TABLE `danhgia` (
  `id` int(11) NOT NULL,
  `sanpham_id` int(11) DEFAULT NULL,
  `nguoidung_id` int(11) DEFAULT NULL,
  `diemso` tinyint(4) NOT NULL,
  `binhluan` text DEFAULT NULL,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danhgia`
--

INSERT INTO `danhgia` (`id`, `sanpham_id`, `nguoidung_id`, `diemso`, `binhluan`, `ngaytao`) VALUES
(1, 1, 1, 5, 'Laptop rất đẹp, hiệu năng mạnh, màn hình OLED tuyệt vời. Rất hài lòng với sản phẩm này.', '2025-01-15 03:00:00'),
(2, 1, 2, 4, 'Máy đẹp, cấu hình tốt nhưng pin hơi yếu so với mong đợi. Tổng thể vẫn hài lòng.', '2025-01-12 08:30:00'),
(3, 1, 3, 5, 'Thiết kế sang trọng, màn hình OLED cực đẹp. Phù hợp cho công việc thiết kế đồ họa. Recommend!', '2025-01-08 02:15:00'),
(4, 2, 1, 4, 'Gaming laptop tốt trong tầm giá', '2025-01-10 07:20:00'),
(5, 3, 2, 5, 'MacBook Air M2 quá tuyệt vời', '2025-01-05 09:45:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhmuc`
--

CREATE TABLE `danhmuc` (
  `id` int(11) NOT NULL,
  `ten` varchar(100) NOT NULL,
  `duongdan` varchar(100) NOT NULL,
  `mota` text DEFAULT NULL,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danhmuc`
--

INSERT INTO `danhmuc` (`id`, `ten`, `duongdan`, `mota`, `ngaytao`) VALUES
(1, 'Gaming', 'gaming', 'Laptop dành cho game thủ', '2025-05-24 10:04:47'),
(2, 'Văn phòng', 'van-phong', 'Laptop cho công việc văn phòng', '2025-05-24 10:04:47'),
(3, 'Đồ họa', 'do-hoa', 'Laptop cho thiết kế đồ họa', '2025-05-24 10:04:47'),
(4, 'Học tập', 'hoc-tap', 'Laptop phục vụ học tập', '2025-05-24 10:04:47');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donhang`
--

CREATE TABLE `donhang` (
  `id` int(11) NOT NULL,
  `nguoidung_id` int(11) DEFAULT NULL,
  `hoten` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `sodienthoai` varchar(20) NOT NULL,
  `diachi` text NOT NULL,
  `tongtien` decimal(15,2) NOT NULL,
  `phivanchuyen` decimal(10,2) NOT NULL,
  `tiengiamgia` decimal(10,2) DEFAULT 0.00,
  `tienthucte` decimal(15,2) NOT NULL,
  `trangthai` enum('choxuly','dangxuly','dagiao','hoanthanh','dahuy') DEFAULT 'choxuly',
  `phuongthucthanhtoan` varchar(50) DEFAULT NULL,
  `trangthaithanhtoan` enum('choxuly','dathanhtoan','thatbai') DEFAULT 'choxuly',
  `ghichu` text DEFAULT NULL,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp(),
  `ngaycapnhat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `magiamgia_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `donhang`
--

INSERT INTO `donhang` (`id`, `nguoidung_id`, `hoten`, `email`, `sodienthoai`, `diachi`, `tongtien`, `phivanchuyen`, `tiengiamgia`, `tienthucte`, `trangthai`, `phuongthucthanhtoan`, `trangthaithanhtoan`, `ghichu`, `ngaytao`, `ngaycapnhat`, `magiamgia_id`) VALUES
(1, 1, 'Nguyễn Văn A', 'nguyenvana@example.com', '0123456789', '123 Đường Công Nghệ, TP.HCM', 32990000.00, 50000.00, 1000000.00, 32040000.00, 'hoanthanh', 'cod', 'dathanhtoan', 'Giao giờ hành chính', '2025-05-24 10:04:47', '2025-05-24 10:04:47', NULL),
(2, 2, 'Trần Thị B', 'tranthib@example.com', '0987654321', '456 Đường ABC, Hà Nội', 28990000.00, 50000.00, 0.00, 29040000.00, 'dangxuly', 'bank_transfer', 'choxuly', NULL, '2025-05-24 10:04:47', '2025-05-24 10:04:47', NULL),
(3, 4, 'DAO VAN TAM', 'tam@gmail.com', '0969859400', 'ok, Xã Nam Cao, Huyện Bảo Lâm, Tỉnh Cao Bằng', 18000000.00, 50000.00, 0.00, 18050000.00, 'choxuly', 'cod', 'choxuly', 'ok', '2025-05-24 19:46:21', '2025-05-24 19:46:21', NULL),
(4, 4, 'Đào Văn Tâm', 'tam@gmail.com', '0969859400', 'ok, Phường Châu Sơn, Thành phố Sông Công, Tỉnh Thái Nguyên', 116000000.00, 50000.00, 0.00, 116050000.00, 'choxuly', 'bank_transfer', 'choxuly', 'ok', '2025-05-25 04:05:26', '2025-05-25 04:05:26', NULL),
(5, 4, 'Đào Văn Tâm', 'tam@gmail.com', '0969859400', 'ok, Phường Hàng Mã, Quận Hoàn Kiếm, Thành phố Hà Nội', 50000000.00, 50000.00, 200000.00, 49850000.00, 'choxuly', 'cod', 'choxuly', 'qq', '2025-05-25 04:33:07', '2025-05-25 04:33:07', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giohang`
--

CREATE TABLE `giohang` (
  `id` int(11) NOT NULL,
  `nguoidung_id` int(11) NOT NULL,
  `sanpham_id` int(11) NOT NULL,
  `soluong` int(11) NOT NULL DEFAULT 1,
  `ngaythem` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `giohang`
--

INSERT INTO `giohang` (`id`, `nguoidung_id`, `sanpham_id`, `soluong`, `ngaythem`) VALUES
(25, 4, 1, 1, '2025-05-25 11:55:31');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hinhanhsanpham`
--

CREATE TABLE `hinhanhsanpham` (
  `id` int(11) NOT NULL,
  `sanpham_id` int(11) DEFAULT NULL,
  `url_hinhanh` varchar(255) NOT NULL,
  `hinhanhchinh` tinyint(1) DEFAULT 0,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lienhe`
--

CREATE TABLE `lienhe` (
  `id` int(11) NOT NULL,
  `hoten` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sodienthoai` varchar(20) DEFAULT NULL,
  `chude` varchar(200) DEFAULT NULL,
  `noidung` text NOT NULL,
  `trangthai` enum('moi','dangxuly','hoanthanh') DEFAULT 'moi',
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lienhe`
--

INSERT INTO `lienhe` (`id`, `hoten`, `email`, `sodienthoai`, `chude`, `noidung`, `trangthai`, `ngaytao`) VALUES
(3, 'Đào Văn Tâm', 'admin@gmail.com', '0969859400', 'complaint', 'Máy tỉnh lởm', 'moi', '2025-05-25 02:40:31'),
(4, 'Đào Văn Tâm', 'admin@gmail.com', '0969859400', 'complaint', 'Máy tỉnh lởm', 'moi', '2025-05-25 02:41:56'),
(5, 'Đào Văn Tâm', 'tam@gmail.com', '0969859400', 'product_inquiry', 'Sản phẩm nào xịn k', 'moi', '2025-05-25 03:00:47'),
(6, 'Đào Văn Tâm', 'tam@gmail.com', '0969859400', 'cooperation', 'Muốn hợp tác chứ', 'moi', '2025-05-25 03:06:07'),
(7, 'Đào Văn Tâm', 'tam@gmail.com', '0969859400', 'warranty', 'Tôi muốn bảo hành', 'moi', '2025-05-25 03:10:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `magiamgia`
--

CREATE TABLE `magiamgia` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_order_value` decimal(10,2) DEFAULT 0.00,
  `max_discount_value` decimal(10,2) DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `magiamgia`
--

INSERT INTO `magiamgia` (`id`, `code`, `discount_type`, `discount_value`, `min_order_value`, `max_discount_value`, `start_date`, `end_date`, `is_active`, `usage_limit`, `used_count`) VALUES
(1, 'NEWCUSTOMER', 'percentage', 10.00, 500000.00, 200000.00, '2025-01-01 00:00:00', '2025-12-31 23:59:59', 1, 100, 1),
(2, 'SAVE100K', 'fixed', 100000.00, 1000000.00, NULL, '2025-01-01 00:00:00', '2025-12-31 23:59:59', 1, 50, 0),
(3, 'LAPTOP20', 'percentage', 20.00, 2000000.00, 500000.00, '2025-01-01 00:00:00', '2025-12-31 23:59:59', 1, 200, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoidung`
--

CREATE TABLE `nguoidung` (
  `id` int(11) NOT NULL,
  `tendangnhap` varchar(50) NOT NULL,
  `matkhau` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `hoten` varchar(100) DEFAULT NULL,
  `Chucvu` varchar(50) NOT NULL,
  `sodienthoai` varchar(20) DEFAULT NULL,
  `diachi` text DEFAULT NULL,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp(),
  `ngaycapnhat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoidung`
--

INSERT INTO `nguoidung` (`id`, `tendangnhap`, `matkhau`, `email`, `hoten`, `Chucvu`, `sodienthoai`, `diachi`, `ngaytao`, `ngaycapnhat`) VALUES
(1, 'nguyenvana', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'nguyenvana@example.com', 'Nguyễn Văn A', 'nhanvien', '0123456789', '123 Đường Công Nghệ, TP.HCM', '2025-05-24 10:04:47', '2025-05-24 10:04:47'),
(2, 'tranthib', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'tranthib@example.com', 'Trần Thị B', 'nhanvien', '0987654321', '456 Đường ABC, Hà Nội', '2025-05-24 10:04:47', '2025-05-24 10:04:47'),
(3, 'leminhc', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'leminhc@example.com', 'Lê Minh C', 'nhanvien', '0369852147', '789 Đường XYZ, Đà Nẵng', '2025-05-24 10:04:47', '2025-05-24 10:04:47'),
(4, 'tam', '123', 'tam@gmail.com', 'Đào Văn Tâm', 'khachhang', '0969859400', 'Vĩnh Phúc', '2025-05-24 17:39:26', '2025-05-25 03:45:40'),
(5, 'quan', '123456', 'quan@gmail.com', 'Vũ Minh Quân', 'khachhang', '0983465333', 'Nam Định', '2025-05-24 17:40:57', '2025-05-25 02:23:51'),
(8, 'nam', '123456', 'nam@h.h', NULL, 'khachhang', NULL, NULL, '2025-05-24 17:48:13', '2025-05-24 17:48:13'),
(9, 'admin', '123', 'admin@gmail.com', 'Admin', 'admin', '0983465333', 'Test', '2025-05-25 04:57:41', '2025-05-25 04:59:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham`
--

CREATE TABLE `sanpham` (
  `id` int(11) NOT NULL,
  `ten` varchar(255) NOT NULL,
  `duongdan` varchar(255) NOT NULL,
  `mota` text DEFAULT NULL,
  `gia` decimal(15,2) NOT NULL,
  `giacu` decimal(15,2) DEFAULT NULL,
  `hinhanh` varchar(255) DEFAULT NULL,
  `danhmuc_id` int(11) DEFAULT NULL,
  `thuonghieu_id` int(11) DEFAULT NULL,
  `thongso` text DEFAULT NULL,
  `soluongton` int(11) DEFAULT 0,
  `noibat` tinyint(1) DEFAULT 0,
  `trangthai` enum('hoatdong','khonghoatdong') DEFAULT 'hoatdong',
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp(),
  `ngaycapnhat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sanpham`
--

INSERT INTO `sanpham` (`id`, `ten`, `duongdan`, `mota`, `gia`, `giacu`, `hinhanh`, `danhmuc_id`, `thuonghieu_id`, `thongso`, `soluongton`, `noibat`, `trangthai`, `ngaytao`, `ngaycapnhat`) VALUES
(1, 'Dell XPS 13 Plus', 'dell-xps-13-plus', 'Laptop cao cấp với thiết kế tinh tế', 32000000.00, 35000000.00, 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=300&h=200&fit=crop', 2, 1, '{\"cpu\": \"Intel i7\", \"ram\": \"16GB\", \"storage\": \"SSD 512GB\", \"screen\": \"13\"\"}', 10, 0, 'hoatdong', '2025-05-24 10:04:47', '2025-05-24 10:04:47'),
(2, 'HP Pavilion Gaming 15', 'hp-pavilion-gaming-15', 'Gaming laptop hiệu năng cao', 18000000.00, NULL, 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=300&h=200&fit=crop', 1, 2, '{\"cpu\": \"Intel i5\", \"ram\": \"8GB\", \"storage\": \"SSD 256GB\", \"screen\": \"15\"\"}', 15, 1, 'hoatdong', '2025-05-24 10:04:47', '2025-05-24 10:04:47'),
(3, 'MacBook Air M2', 'macbook-air-m2', 'Laptop mỏng nhẹ hiệu năng cao', 28000000.00, NULL, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=300&h=200&fit=crop', 2, 3, '{\"cpu\": \"Apple M2\", \"ram\": \"8GB\", \"storage\": \"SSD 256GB\", \"screen\": \"13\"\"}', 8, 1, 'hoatdong', '2025-05-24 10:04:47', '2025-05-24 10:04:47'),
(4, 'ASUS ROG Strix G15', 'asus-rog-strix-g15', 'Gaming laptop cao cấp', 25000000.00, 27000000.00, 'https://cdn2.fptshop.com.vn/unsafe/828x0/filters:format(webp):quality(75)/2022_7_27_637945294312205667_ASUS-ROG-Strix-G15-G513-fpt-6.jpg', 1, 4, '{\"cpu\": \"AMD Ryzen 7\", \"ram\": \"16GB\", \"storage\": \"SSD 512GB\", \"screen\": \"15\"\"}', 12, 0, 'hoatdong', '2025-05-24 10:04:47', '2025-05-24 10:12:57'),
(5, 'Lenovo ThinkPad X1 Carbon', 'lenovo-thinkpad-x1-carbon', 'Laptop doanh nhân cao cấp', 35000000.00, NULL, 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=300&h=200&fit=crop', 2, 5, '{\"cpu\": \"Intel i7\", \"ram\": \"16GB\", \"storage\": \"SSD 1TB\", \"screen\": \"14\"\"}', 6, 0, 'hoatdong', '2025-05-24 10:04:47', '2025-05-24 10:04:47'),
(6, 'HP Spectre x360', 'hp-spectre-x360', 'Laptop 2-in-1 cao cấp', 30000000.00, NULL, 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=300&h=200&fit=crop', 3, 2, '{\"cpu\": \"Intel i7\", \"ram\": \"16GB\", \"storage\": \"SSD 512GB\", \"screen\": \"13\"\"}', 10, 0, 'hoatdong', '2025-05-24 10:04:47', '2025-05-24 10:04:47'),
(7, 'Dell Inspiron 15 3000', 'dell-inspiron-15-3000', 'Laptop học tập giá rẻ', 12000000.00, NULL, 'https://images.unsplash.com/photo-1484788984921-03950022c9ef?w=300&h=200&fit=crop', 4, 1, '{\"cpu\": \"Intel i3\", \"ram\": \"8GB\", \"storage\": \"HDD 1TB\", \"screen\": \"15\"\"}', 20, 0, 'hoatdong', '2025-05-24 10:04:47', '2025-05-24 10:04:47'),
(8, 'ASUS ZenBook 14', 'asus-zenbook-14', 'Ultrabook mỏng nhẹ', 22000000.00, 24000000.00, 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=300&h=200&fit=crop', 2, 4, '{\"cpu\": \"AMD Ryzen 5\", \"ram\": \"8GB\", \"storage\": \"SSD 512GB\", \"screen\": \"14\"\"}', 15, 0, 'hoatdong', '2025-05-24 10:04:47', '2025-05-24 10:04:47');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thuonghieu`
--

CREATE TABLE `thuonghieu` (
  `id` int(11) NOT NULL,
  `ten` varchar(100) NOT NULL,
  `duongdan` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `mota` text DEFAULT NULL,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thuonghieu`
--

INSERT INTO `thuonghieu` (`id`, `ten`, `duongdan`, `logo`, `mota`, `ngaytao`) VALUES
(1, 'Dell', 'dell', 'dell-logo.png', 'Thương hiệu laptop Dell', '2025-05-24 10:04:47'),
(2, 'HP', 'hp', 'hp-logo.png', 'Thương hiệu laptop HP', '2025-05-24 10:04:47'),
(3, 'Apple', 'apple', 'apple-logo.png', 'Thương hiệu laptop Apple', '2025-05-24 10:04:47'),
(4, 'Asus', 'asus', 'asus-logo.png', 'Thương hiệu laptop Asus', '2025-05-24 10:04:47'),
(5, 'Lenovo', 'lenovo', 'lenovo-logo.png', 'Thương hiệu laptop Lenovo', '2025-05-24 10:04:47');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `yeuthich`
--

CREATE TABLE `yeuthich` (
  `id` int(11) NOT NULL,
  `nguoidung_id` int(11) DEFAULT NULL,
  `sanpham_id` int(11) DEFAULT NULL,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `yeuthich`
--

INSERT INTO `yeuthich` (`id`, `nguoidung_id`, `sanpham_id`, `ngaytao`) VALUES
(1, 1, 1, '2025-05-24 10:04:47'),
(2, 1, 3, '2025-05-24 10:04:47'),
(3, 1, 5, '2025-05-24 10:04:47'),
(4, 2, 2, '2025-05-24 10:04:47'),
(5, 2, 4, '2025-05-24 10:04:47'),
(6, 3, 1, '2025-05-24 10:04:47'),
(7, 3, 6, '2025-05-24 10:04:47'),
(26, 4, 1, '2025-05-25 04:44:11');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donhang_id` (`donhang_id`),
  ADD KEY `sanpham_id` (`sanpham_id`);

--
-- Chỉ mục cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sanpham_id` (`sanpham_id`),
  ADD KEY `nguoidung_id` (`nguoidung_id`);

--
-- Chỉ mục cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `duongdan` (`duongdan`);

--
-- Chỉ mục cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nguoidung_id` (`nguoidung_id`),
  ADD KEY `magiamgia_id` (`magiamgia_id`);

--
-- Chỉ mục cho bảng `giohang`
--
ALTER TABLE `giohang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart` (`nguoidung_id`,`sanpham_id`),
  ADD KEY `sanpham_id` (`sanpham_id`);

--
-- Chỉ mục cho bảng `hinhanhsanpham`
--
ALTER TABLE `hinhanhsanpham`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sanpham_id` (`sanpham_id`);

--
-- Chỉ mục cho bảng `lienhe`
--
ALTER TABLE `lienhe`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `magiamgia`
--
ALTER TABLE `magiamgia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tendangnhap` (`tendangnhap`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `duongdan` (`duongdan`),
  ADD KEY `danhmuc_id` (`danhmuc_id`),
  ADD KEY `thuonghieu_id` (`thuonghieu_id`);

--
-- Chỉ mục cho bảng `thuonghieu`
--
ALTER TABLE `thuonghieu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `duongdan` (`duongdan`);

--
-- Chỉ mục cho bảng `yeuthich`
--
ALTER TABLE `yeuthich`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_yeuthich` (`nguoidung_id`,`sanpham_id`),
  ADD KEY `sanpham_id` (`sanpham_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `donhang`
--
ALTER TABLE `donhang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `giohang`
--
ALTER TABLE `giohang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `hinhanhsanpham`
--
ALTER TABLE `hinhanhsanpham`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `lienhe`
--
ALTER TABLE `lienhe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `magiamgia`
--
ALTER TABLE `magiamgia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `thuonghieu`
--
ALTER TABLE `thuonghieu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `yeuthich`
--
ALTER TABLE `yeuthich`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD CONSTRAINT `chitietdonhang_ibfk_1` FOREIGN KEY (`donhang_id`) REFERENCES `donhang` (`id`),
  ADD CONSTRAINT `chitietdonhang_ibfk_2` FOREIGN KEY (`sanpham_id`) REFERENCES `sanpham` (`id`);

--
-- Các ràng buộc cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD CONSTRAINT `danhgia_ibfk_1` FOREIGN KEY (`sanpham_id`) REFERENCES `sanpham` (`id`),
  ADD CONSTRAINT `danhgia_ibfk_2` FOREIGN KEY (`nguoidung_id`) REFERENCES `nguoidung` (`id`);

--
-- Các ràng buộc cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `donhang_ibfk_1` FOREIGN KEY (`nguoidung_id`) REFERENCES `nguoidung` (`id`),
  ADD CONSTRAINT `donhang_ibfk_2` FOREIGN KEY (`magiamgia_id`) REFERENCES `magiamgia` (`id`);

--
-- Các ràng buộc cho bảng `giohang`
--
ALTER TABLE `giohang`
  ADD CONSTRAINT `giohang_ibfk_1` FOREIGN KEY (`nguoidung_id`) REFERENCES `nguoidung` (`id`),
  ADD CONSTRAINT `giohang_ibfk_2` FOREIGN KEY (`sanpham_id`) REFERENCES `sanpham` (`id`);

--
-- Các ràng buộc cho bảng `hinhanhsanpham`
--
ALTER TABLE `hinhanhsanpham`
  ADD CONSTRAINT `hinhanhsanpham_ibfk_1` FOREIGN KEY (`sanpham_id`) REFERENCES `sanpham` (`id`);

--
-- Các ràng buộc cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`danhmuc_id`) REFERENCES `danhmuc` (`id`),
  ADD CONSTRAINT `sanpham_ibfk_2` FOREIGN KEY (`thuonghieu_id`) REFERENCES `thuonghieu` (`id`);

--
-- Các ràng buộc cho bảng `yeuthich`
--
ALTER TABLE `yeuthich`
  ADD CONSTRAINT `yeuthich_ibfk_1` FOREIGN KEY (`nguoidung_id`) REFERENCES `nguoidung` (`id`),
  ADD CONSTRAINT `yeuthich_ibfk_2` FOREIGN KEY (`sanpham_id`) REFERENCES `sanpham` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
