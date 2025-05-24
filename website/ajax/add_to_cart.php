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

// Lấy dữ liệu từ body
$input = json_decode(file_get_contents('php://input'), true);
$product_id = isset($input['product_id']) ? (int)$input['product_id'] : 0;
$quantity = isset($input['quantity']) ? (int)$input['quantity'] : 1;

// Kiểm tra dữ liệu đầu vào
if ($product_id <= 0 || $quantity <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid product ID or quantity']);
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

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Thêm hoặc cập nhật sản phẩm trong giỏ hàng
if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
} else {
    $_SESSION['cart'][$product_id] = [
        'product_id' => $product_id,
        'quantity' => $quantity
    ];
}

echo json_encode(['success' => true, 'message' => 'Product added to cart']);
?>