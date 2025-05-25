<?php
require_once '../../config/connect.php';

$sanpham_id = isset($_GET['sanpham_id']) ? (int)$_GET['sanpham_id'] : 0;
$query = "SELECT * FROM hinhanhsanpham WHERE sanpham_id = $sanpham_id";
$result = mysqli_query($conn, $query);
$images = mysqli_fetch_all($result, MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($images);
?>