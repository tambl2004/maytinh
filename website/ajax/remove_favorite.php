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
    echo json_encode(['error' => 'Please login to remove favorite']);
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

// Xóa khỏi danh sách yêu thích
$user_id = (int)$_SESSION['user_id'];
$query = "DELETE FROM yeuthich WHERE nguoidung_id = ? AND sanpham_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ii', $user_id, $product_id);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    echo json_encode(['success' => true, 'message' => 'Product removed from favorites']);
} else {
    echo json_encode(['success' => true, 'message' => 'Product not in favorites']);
}
?>