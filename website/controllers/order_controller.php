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

if ($action === 'place_order') {
    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $province = trim($_POST['province']);
    $district = trim($_POST['district']);
    $ward = trim($_POST['ward']);
    $address = trim($_POST['address']);
    $note = trim($_POST['note']);
    $shippingMethod = trim($_POST['shipping_method']);
    $paymentMethod = trim($_POST['payment_method']);

    // Validate input
    if (empty($fullname) || empty($phone) || empty($province) || empty($district) || empty($ward) || empty($address)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin bắt buộc']);
        exit;
    }

    // Validate phone number
    if (!preg_match('/^[0-9]{10,11}$/', $phone)) {
        echo json_encode(['success' => false, 'message' => 'Số điện thoại không hợp lệ']);
        exit;
    }

    // Validate email if provided
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email không hợp lệ']);
        exit;
    }

    // Tính phí vận chuyển
    $shippingFees = [
        'standard' => 50000,
        'express' => 100000,
        'same_day' => 200000
    ];
    $shipping = isset($shippingFees[$shippingMethod]) ? $shippingFees[$shippingMethod] : 50000;

    // Lấy giỏ hàng
    $sql = "SELECT g.sanpham_id, g.soluong, s.gia
            FROM giohang g
            JOIN sanpham s ON g.sanpham_id = s.id
            WHERE g.nguoidung_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $cartItems = [];
    $subtotal = 0;
    while ($row = $result->fetch_assoc()) {
        $row['subtotal'] = $row['gia'] * $row['soluong'];
        $subtotal += $row['subtotal'];
        $cartItems[] = $row;
    }
    $stmt->close();

    if (empty($cartItems)) {
        echo json_encode(['success' => false, 'message' => 'Giỏ hàng rỗng']);
        exit;
    }

    // Lấy mã giảm giá từ session
    $discount = isset($_SESSION['coupon']['discount']) ? $_SESSION['coupon']['discount'] : 0;
    $total = $subtotal + $shipping - $discount;

    // Lưu đơn hàng
    $conn->begin_transaction();
    try {
        // Thêm vào bảng donhang
        $sql = "INSERT INTO donhang (nguoidung_id, hoten, email, sodienthoai, diachi, tongtien, phivanchuyen, tiengiamgia, tienthucte, phuongthucthanhtoan, ghichu)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $addressFull = "$address, $ward, $district, $province";
        $stmt->bind_param("isssssddsss", $userId, $fullname, $email, $phone, $addressFull, $subtotal, $shipping, $discount, $total, $paymentMethod, $note);
        $stmt->execute();
        $orderId = $conn->insert_id;
        $stmt->close();

        // Thêm vào bảng chitietdonhang
        $sql = "INSERT INTO chitietdonhang (donhang_id, sanpham_id, soluong, gia, tong) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        foreach ($cartItems as $item) {
            $itemTotal = $item['gia'] * $item['soluong'];
            $stmt->bind_param("iiidd", $orderId, $item['sanpham_id'], $item['soluong'], $item['gia'], $itemTotal);
            $stmt->execute();
        }
        $stmt->close();

        // Cập nhật số lượt sử dụng mã giảm giá
        if (isset($_SESSION['coupon']['coupon_id'])) {
            $sqlUpdateCoupon = "UPDATE magiamgia SET used_count = used_count + 1 WHERE id = ?";
            $stmtUpdateCoupon = $conn->prepare($sqlUpdateCoupon);
            $stmtUpdateCoupon->bind_param("i", $_SESSION['coupon']['coupon_id']);
            $stmtUpdateCoupon->execute();
            $stmtUpdateCoupon->close();
        }

        // Xóa giỏ hàng
        $sql = "DELETE FROM giohang WHERE nguoidung_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();

        // Xóa mã giảm giá trong session
        if (isset($_SESSION['coupon'])) {
            unset($_SESSION['coupon']);
        }

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Đơn hàng đã được đặt thành công']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Lỗi khi đặt hàng: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
}

$conn->close();
?>