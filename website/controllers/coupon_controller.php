<?php
session_start();
include '../../config/connect.php';
require_once '../../inc/auth.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
    exit;
}

$userId = $_SESSION['id'];
$action = isset($_POST['action']) ? $_POST['action'] : '';
$shipping = 50000; // Phí vận chuyển mặc định

if ($action === 'apply') {
    $couponCode = trim($_POST['code'] ?? '');

    if (empty($couponCode)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập mã giảm giá']);
        exit;
    }

    // Lấy giỏ hàng
    $sql = "SELECT g.soluong, s.gia
            FROM giohang g
            JOIN sanpham s ON g.sanpham_id = s.id
            WHERE g.nguoidung_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $subtotal = 0;
    $cartCount = 0;
    while ($row = $result->fetch_assoc()) {
        $subtotal += $row['gia'] * $row['soluong'];
        $cartCount += $row['soluong'];
    }
    $stmt->close();

    if ($subtotal <= 0) {
        echo json_encode(['success' => false, 'message' => 'Giỏ hàng rỗng']);
        exit;
    }

    // Kiểm tra mã giảm giá
    $sql = "SELECT * FROM magiamgia WHERE code = ? AND is_active = 1 AND start_date <= NOW() AND end_date >= NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $couponCode);
    $stmt->execute();
    $result = $stmt->get_result();
    $coupon = $result->fetch_assoc();
    $stmt->close();

    if (!$coupon) {
        echo json_encode(['success' => false, 'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn']);
        exit;
    }

    // Kiểm tra giới hạn sử dụng
    if ($coupon['usage_limit'] !== null && $coupon['used_count'] >= $coupon['usage_limit']) {
        echo json_encode(['success' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng']);
        exit;
    }

    // Kiểm tra giá trị đơn hàng tối thiểu
    if ($subtotal < $coupon['min_order_value']) {
        echo json_encode(['success' => false, 'message' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($coupon['min_order_value'], 0, ',', '.') . '₫']);
        exit;
    }

    // Tính toán giảm giá
    $discount = 0;
    if ($coupon['discount_type'] === 'percentage') {
        $discount = ($subtotal * $coupon['discount_value']) / 100;
        if ($coupon['max_discount_value'] !== null && $discount > $coupon['max_discount_value']) {
            $discount = $coupon['max_discount_value'];
        }
    } else {
        $discount = $coupon['discount_value'];
    }

    // Lưu mã giảm giá vào session
    $_SESSION['coupon'] = [
        'code' => $coupon['code'],
        'discount' => $discount,
        'coupon_id' => $coupon['id']
    ];

    $total = $subtotal + $shipping - $discount;

    echo json_encode([
        'success' => true,
        'message' => 'Mã giảm giá được áp dụng thành công',
        'newSubtotal' => $subtotal,
        'newTotal' => $total,
        'newCartCount' => $cartCount,
        'discount' => $discount
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
}

$conn->close();
?>