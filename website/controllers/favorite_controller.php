<?php
session_start();
include '../config/connect.php';

header('Content-Type: application/json');
$response = ['success' => false];

if (!isset($_SESSION['user_id'])) {
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($action === 'remove') {
    $product_id = $_POST['product_id'] ?? 0;
    $stmt = $conn->prepare("DELETE FROM yeuthich WHERE nguoidung_id = ? AND sanpham_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    if ($stmt->execute()) {
        $response['success'] = true;
    }
} elseif ($action === 'clear') {
    $stmt = $conn->prepare("DELETE FROM yeuthich WHERE nguoidung_id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $response['success'] = true;
    }
} elseif ($action === 'add') {
    $product_id = $_POST['product_id'] ?? 0;
    $stmt = $conn->prepare("INSERT INTO yeuthich (nguoidung_id, sanpham_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $product_id);
    if ($stmt->execute()) {
        $response['success'] = true;
    }
}

echo json_encode($response);
?>