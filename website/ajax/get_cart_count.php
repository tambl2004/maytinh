<?php
session_start();
header('Content-Type: application/json');

// Khởi tạo số lượng
$count = 0;

// Tính tổng số lượng sản phẩm trong giỏ hàng
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $count = array_sum(array_column($_SESSION['cart'], 'quantity'));
}

echo json_encode(['count' => $count]);
?>