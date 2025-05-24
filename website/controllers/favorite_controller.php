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
    case 'toggle':
        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        
        if ($productId <= 0) {
            $response['message'] = 'ID sản phẩm không hợp lệ';
            break;
        }

        // Kiểm tra xem sản phẩm đã có trong danh sách yêu thích chưa
        $sqlCheck = "SELECT id FROM yeuthich WHERE nguoidung_id = ? AND sanpham_id = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("ii", $userId, $productId);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        if ($resultCheck->num_rows > 0) {
            // Xóa khỏi yêu thích
            $sqlDelete = "DELETE FROM yeuthich WHERE nguoidung_id = ? AND sanpham_id = ?";
            $stmtDelete = $conn->prepare($sqlDelete);
            $stmtDelete->bind_param("ii", $userId, $productId);
            if ($stmtDelete->execute()) {
                $response = ['success' => true, 'action' => 'removed'];
            }
            $stmtDelete->close();
        } else {
            // Thêm vào yêu thích
            $sqlInsert = "INSERT INTO yeuthich (nguoidung_id, sanpham_id) VALUES (?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param("ii", $userId, $productId);
            if ($stmtInsert->execute()) {
                $response = ['success' => true, 'action' => 'added'];
            }
            $stmtInsert->close();
        }
        $stmtCheck->close();
        break;

    case 'remove':
        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        
        if ($productId <= 0) {
            $response['message'] = 'ID sản phẩm không hợp lệ';
            break;
        }

        $sqlDelete = "DELETE FROM yeuthich WHERE nguoidung_id = ? AND sanpham_id = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bind_param("ii", $userId, $productId);
        if ($stmtDelete->execute()) {
            $response = ['success' => true];
        }
        $stmtDelete->close();
        break;

    case 'clear':
        $sqlClear = "DELETE FROM yeuthich WHERE nguoidung_id = ?";
        $stmtClear = $conn->prepare($sqlClear);
        $stmtClear->bind_param("i", $userId);
        if ($stmtClear->execute()) {
            $response = ['success' => true];
        }
        $stmtClear->close();
        break;

    case 'add_all_favorites':
        // Thêm tất cả sản phẩm yêu thích vào giỏ hàng
        $sqlFavorites = "SELECT sanpham_id FROM yeuthich WHERE nguoidung_id = ?";
        $stmtFavorites = $conn->prepare($sqlFavorites);
        $stmtFavorites->bind_param("i", $userId);
        $stmtFavorites->execute();
        $resultFavorites = $stmtFavorites->get_result();

        $successCount = 0;
        while ($row = $resultFavorites->fetch_assoc()) {
            $productId = $row['sanpham_id'];

            // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
            $sqlCheckCart = "SELECT id FROM giohang WHERE nguoidung_id = ? AND sanpham_id = ?";
            $stmtCheckCart = $conn->prepare($sqlCheckCart);
            $stmtCheckCart->bind_param("ii", $userId, $productId);
            $stmtCheckCart->execute();
            $resultCheckCart = $stmtCheckCart->get_result();

            if ($resultCheckCart->num_rows > 0) {
                // Cập nhật số lượng nếu sản phẩm đã có trong giỏ hàng
                $sqlUpdateCart = "UPDATE giohang SET soluong = soluong + 1 WHERE nguoidung_id = ? AND sanpham_id = ?";
                $stmtUpdateCart = $conn->prepare($sqlUpdateCart);
                $stmtUpdateCart->bind_param("ii", $userId, $productId);
                if ($stmtUpdateCart->execute()) {
                    $successCount++;
                }
                $stmtUpdateCart->close();
            } else {
                // Thêm mới vào giỏ hàng
                $sqlInsertCart = "INSERT INTO giohang (nguoidung_id, sanpham_id, soluong) VALUES (?, ?, 1)";
                $stmtInsertCart = $conn->prepare($sqlInsertCart);
                $stmtInsertCart->bind_param("ii", $userId, $productId);
                if ($stmtInsertCart->execute()) {
                    $successCount++;
                }
                $stmtInsertCart->close();
            }
            $stmtCheckCart->close();
        }
        $stmtFavorites->close();

        $response = ['success' => $successCount > 0];
        break;

    default:
        $response['message'] = 'Hành động không hợp lệ';
}

echo json_encode($response);
$conn->close();
?>