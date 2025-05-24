<?php
session_start();
include '../config/connect.php';

header('Content-Type: application/json');
$response = ['success' => false];

if ($action = $_POST['action'] ?? $_GET['action'] ?? '') {
    if ($action === 'add') {
        $product_id = $_POST['product_id'] ?? 0;
        $quantity = $_POST['quantity'] ?? 1;
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
        $response['success'] = true;
    } elseif ($action === 'remove') {
        $product_id = $_POST['product_id'] ?? 0;
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            $response['success'] = true;
        }
    } elseif ($action === 'update') {
        $product_id = $_POST['product_id'] ?? 0;
        $quantity = $_POST['quantity'] ?? 1;
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            $response['success'] = true;
        }
    } elseif ($action === 'clear') {
        $_SESSION['cart'] = [];
        $response['success'] = true;
    } elseif ($action === 'get_count') {
        $count = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $count += $item['quantity'];
            }
        }
        $response['success'] = true;
        $response['count'] = $count;
    } elseif ($action === 'add_all_favorites') {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT sanpham_id FROM yeuthich WHERE nguoidung_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $product_id = $row['sanpham_id'];
            if (!isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id] = ['quantity' => 1];
            }
        }
        $response['success'] = true;
    }
}

echo json_encode($response);
?>