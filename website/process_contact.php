<?php
session_start();
require_once '../config/connect.php';
require_once '../inc/auth.php';

// Hàm làm sạch dữ liệu đầu vào
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Hàm validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Hàm validate số điện thoại
function isValidPhone($phone) {
    return empty($phone) || preg_match('/^[0-9]{10,11}$/', $phone);
}

// Xử lý yêu cầu POST từ form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $fullName = sanitizeInput($_POST['fullName'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $phone = sanitizeInput($_POST['phone'] ?? '');
        $subject = sanitizeInput($_POST['subject'] ?? '');
        $message = sanitizeInput($_POST['message'] ?? '');
        $agreeTerms = isset($_POST['agreeTerms']) && $_POST['agreeTerms'] === 'on';

        // Validate dữ liệu
        $errors = [];

        if (empty($fullName)) {
            $errors[] = 'Họ và tên là bắt buộc.';
        } elseif (strlen($fullName) > 100) {
            $errors[] = 'Họ và tên không được vượt quá 100 ký tự.';
        }

        if (empty($email) || !isValidEmail($email)) {
            $errors[] = 'Email không hợp lệ.';
        } elseif (strlen($email) > 100) {
            $errors[] = 'Email không được vượt quá 100 ký tự.';
        }

        if (!empty($phone) && !isValidPhone($phone)) {
            $errors[] = 'Số điện thoại không hợp lệ (10-11 số).';
        } elseif (strlen($phone) > 20) {
            $errors[] = 'Số điện thoại không được vượt quá 20 ký tự.';
        }

        if (empty($subject)) {
            $errors[] = 'Chủ đề là bắt buộc.';
        } elseif (strlen($subject) > 200) {
            $errors[] = 'Chủ đề không được vượt quá 200 ký tự.';
        }

        if (empty($message)) {
            $errors[] = 'Nội dung tin nhắn là bắt buộc.';
        }

        if (!$agreeTerms) {
            $errors[] = 'Bạn phải đồng ý với điều khoản dịch vụ.';
        }

        // Nếu có lỗi, trả về JSON
        if (!empty($errors)) {
            echo json_encode(['success' => false, 'message' => implode('<br>', $errors)]);
            exit;
        }

        // Lưu vào cơ sở dữ liệu
        $sql = "INSERT INTO lienhe (hoten, email, sodienthoai, chude, noidung, trangthai, ngaytao) 
                VALUES (?, ?, ?, ?, ?, 'moi', NOW())";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Lỗi chuẩn bị câu lệnh SQL: ' . $conn->error);
        }

        $stmt->bind_param('sssss', $fullName, $email, $phone, $subject, $message);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => "Cảm ơn $fullName! Tin nhắn của bạn đã được gửi thành công. Chúng tôi sẽ phản hồi trong vòng 24 giờ."], JSON_UNESCAPED_UNICODE);
            } else {
            throw new Exception('Lỗi khi lưu tin nhắn: ' . $stmt->error);
        }

        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ']);
}

$conn->close();
?>