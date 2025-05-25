<?php
header('Content-Type: application/json');
include '../../config/connect.php';

// Hàm định dạng ngày
function getToday() {
    return date('Y-m-d');
}

// Lấy dữ liệu tổng quan
$today = getToday();

// 1. Doanh thu hôm nay
$revenueQuery = "
    SELECT SUM(tienthucte) as todayRevenue
    FROM donhang
    WHERE DATE(ngaytao) = '$today'
";
$revenueResult = mysqli_query($conn, $revenueQuery);
$todayRevenue = mysqli_fetch_assoc($revenueResult)['todayRevenue'] ?? 0;
$revenueChange = 0; // Giả định so sánh với hôm qua (cần thêm logic nếu có dữ liệu lịch sử)

// 2. Số đơn hàng mới hôm nay
$ordersQuery = "
    SELECT COUNT(id) as newOrders
    FROM donhang
    WHERE DATE(ngaytao) = '$today'
";
$ordersResult = mysqli_query($conn, $ordersQuery);
$newOrders = mysqli_fetch_assoc($ordersResult)['newOrders'] ?? 0;
$orderChange = 0; // Giả định so sánh với tuần trước (cần thêm logic nếu có dữ liệu lịch sử)

// 3. Sản phẩm bán chạy (top 1 hôm nay)
$topProductsQuery = "
    SELECT COUNT(chitietdonhang.sanpham_id) as topProducts
    FROM chitietdonhang
    JOIN donhang ON chitietdonhang.donhang_id = donhang.id
    WHERE DATE(donhang.ngaytao) = '$today'
    GROUP BY chitietdonhang.sanpham_id
    ORDER BY topProducts DESC
    LIMIT 1
";
$topProductsResult = mysqli_query($conn, $topProductsQuery);
$topProducts = mysqli_fetch_assoc($topProductsResult)['topProducts'] ?? 0;

// 4. Sản phẩm hết hàng
$outOfStockQuery = "
    SELECT COUNT(id) as outOfStock
    FROM sanpham
    WHERE soluongton <= 0
";
$outOfStockResult = mysqli_query($conn, $outOfStockQuery);
$outOfStock = mysqli_fetch_assoc($outOfStockResult)['outOfStock'] ?? 0;

// Trả về dữ liệu JSON
echo json_encode([
    'todayRevenue' => (float)$todayRevenue,
    'revenueChange' => (float)$revenueChange,
    'newOrders' => (int)$newOrders,
    'orderChange' => (int)$orderChange,
    'topProducts' => (int)$topProducts,
    'outOfStock' => (int)$outOfStock
]);

mysqli_close($conn);
?>