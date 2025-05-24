<?php
session_start();
require_once '../../config/connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
    exit();
}

$userId = $_SESSION['id'];
$action = $_POST['action'] ?? '';

$response = ['success' => false];

switch ($action) {
    case 'add':
        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

        if ($productId <= 0 || $quantity <= 0) {
            $response['message'] = 'Dữ liệu không hợp lệ';
            break;
        }

        // Kiểm tra sản phẩm có tồn tại và còn hàng không
        $sqlCheckProduct = "SELECT soluongton FROM sanpham WHERE id = ? AND trangthai = 'hoatdong'";
        $stmtCheckProduct = $conn->prepare($sqlCheckProduct);
        $stmtCheckProduct->bind_param("i", $productId);
        $stmtCheckProduct->execute();
        $resultCheckProduct = $stmtCheckProduct->get_result();

        if ($resultCheckProduct->num_rows === 0) {
            $response['message'] = 'Sản phẩm không tồn tại';
            $stmtCheckProduct->close();
            break;
        }

        $product = $resultCheckProduct->fetch_assoc();
        if ($product['soluongton'] < $quantity) {
            $response['message'] = 'Sản phẩm không đủ số lượng trong kho';
            $stmtCheckProduct->close();
            break;
        }
        $stmtCheckProduct->close();

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $sqlCheckCart = "SELECT id, soluong FROM giohang WHERE nguoidung_id = ? AND sanpham_id = ?";
        $stmtCheckCart = $conn->prepare($sqlCheckCart);
        $stmtCheckCart->bind_param("ii", $userId, $productId);
        $stmtCheckCart->execute();
        $resultCheckCart = $stmtCheckCart->get_result();

        if ($resultCheckCart->num_rows > 0) {
            // Cập nhật số lượng nếu sản phẩm đã có trong giỏ hàng
            $cartItem = $resultCheckCart->fetch_assoc();
            $newQuantity = $cartItem['soluong'] + $quantity;

            if ($newQuantity > $product['soluongton']) {
                $response['message'] = 'Số lượng vượt quá tồn kho';
                $stmtCheckCart->close();
                break;
            }

            $sqlUpdate = "UPDATE giohang SET soluong = ? WHERE nguoidung_id = ? AND sanpham_id = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("iii", $newQuantity, $userId, $productId);
            if ($stmtUpdate->execute()) {
                $response = ['success' => true];
            }
            $stmtUpdate->close();
        } else {
            // Thêm mới vào giỏ hàng
            $sqlInsert = "INSERT INTO giohang (nguoidung_id, sanpham_id, soluong) VALUES (?, ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param("iii", $userId, $productId, $quantity);
            if ($stmtInsert->execute()) {
                $response = ['success' => true];
            }
            $stmtInsert->close();
        }
        $stmtCheckCart->close();
        break;

    case 'update':
        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

        if ($productId <= 0 || $quantity <= 0) {
            $response['message'] = 'Dữ liệu không hợp lệ';
            break;
        }

        // Kiểm tra sản phẩm có tồn tại và còn hàng không
        $sqlCheckProduct = "SELECT soluongton FROM sanpham WHERE id = ? AND trangthai = 'hoatdong'";
        $stmtCheckProduct = $conn->prepare($sqlCheckProduct);
        $stmtCheckProduct->bind_param("i", $productId);
        $stmtCheckProduct->execute();
        $resultCheckProduct = $stmtCheckProduct->get_result();

        if ($resultCheckProduct->num_rows === 0) {
            $response['message'] = 'Sản phẩm không tồn tại';
            $stmtCheckProduct->close();
            break;
        }

        $product = $resultCheckProduct->fetch_assoc();
        if ($product['soluongton'] < $quantity) {
            $response['message'] = 'Số lượng vượt quá tồn kho';
            $stmtCheckProduct->close();
            break;
        }
        $stmtCheckProduct->close();

        $sqlUpdate = "UPDATE giohang SET soluong = ? WHERE nguoidung_id = ? AND sanpham_id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("iii", $quantity, $userId, $productId);
        if ($stmtUpdate->execute()) {
            $response = ['success' => true];
        }
        $stmtUpdate->close();
        break;

    case 'remove':
        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

        if ($productId <= 0) {
            $response['message'] = 'ID sản phẩm không hợp lệ';
            break;
        }

        $sqlDelete = "DELETE FROM giohang WHERE nguoidung_id = ? AND sanpham_id = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bind_param("ii", $userId, $productId);
        if ($stmtDelete->execute()) {
            $response = ['success' => true];
        }
        $stmtDelete->close();
        break;

    case 'clear':
        $sqlClear = "DELETE FROM giohang WHERE nguoidung_id = ?";
        $stmtClear = $conn->prepare($sqlClear);
        $stmtClear->bind_param("i", $userId);
        if ($stmtClear->execute()) {
            $response = ['success' => true];
        }
        $stmtClear->close();
        break;

    case 'get_count':
        $sqlCount = "SELECT SUM(soluong) as count FROM giohang WHERE nguoidung_id = ?";
        $stmtCount = $conn->prepare($sqlCount);
        $stmtCount->bind_param("i", $userId);
        $stmtCount->execute();
        $resultCount = $stmtCount->get_result();
        $row = $resultCount->fetch_assoc();
        $response = [
            'success' => true,
            'count' => (int)($row['count'] ?? 0)
        ];
        $stmtCount->close();
        break;

    default:
        $response['message'] = 'Hành động không hợp lệ';
}

echo json_encode($response);
$conn->close();
?>