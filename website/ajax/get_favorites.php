<?php
session_start();
header('Content-Type: application/json');
include '../config/connect.php';

// Kiểm tra người dùng đã đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

// Lấy danh sách sản phẩm yêu thích
$user_id = (int)$_SESSION['user_id'];
$query = "SELECT sanpham_id FROM yeuthich WHERE nguoidung_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$favorites = [];
while ($row = mysqli_fetch_assoc($result)) {
    $favorites[] = (int)$row['sanpham_id'];
}

echo json_encode($favorites);
?>