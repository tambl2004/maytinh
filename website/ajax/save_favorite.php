<?php
session_start();
header('Content-Type: application/json');
include '../config/connect.php';

// Kiểm tra yêu cầu POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Kiểm tra người dùng đã đăng nhập
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Please login to add favorite']);
    exit;
}

// Lấy dữ liệu từ body
$input = json_decode(file_get_contents('php://input'), true);
$product_id = isset($input['product_id']) ? (int)$input['product_id'] : 0;

// Kiểm tra dữ liệu đầu vào
if ($product_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid product ID']);
    exit;
}

// Kiểm tra sản phẩm tồn tại
$query = "SELECT id FROM sanpham WHERE id = ? AND trangthai = 'hoatdong'";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Product not found']);
    exit;
}

// Kiểm tra xem đã có trong danh sách yêu thích chưa
$user_id = (int)$_SESSION['user_id'];
$query = "SELECT id FROM yeuthich WHERE nguoidung_id = ? AND sanpham_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ii', $user_id, $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    echo json_encode(['success' => true, 'message' => 'Product already in favorites']);
    exit;
}

// Thêm vào danh sách yêu thích
$query = "INSERT INTO yeuthich (nguoidung_id, sanpham_id, ngaytao) VALUES (?, ?, NOW())";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ii', $user_id, $product_id);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    echo json_encode(['success' => true, 'message' => 'Product added to favorites']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to add to favorites']);
}
?>