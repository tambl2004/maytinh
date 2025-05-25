<?php
header('Content-Type: application/json');
include '../../config/connect.php';

// Hàm validate ngày
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

// Lấy tham số từ request
$dateRange = isset($_GET['dateRange']) ? $_GET['dateRange'] : '7days';
$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : null;
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : null;

// Xác định khoảng thời gian
$today = date('Y-m-d');
if ($dateRange === '7days') {
    $start = date('Y-m-d', strtotime('-6 days', strtotime($today)));
    $end = $today;
} elseif ($dateRange === '30days') {
    $start = date('Y-m-d', strtotime('-29 days', strtotime($today)));
    $end = $today;
} elseif ($dateRange === 'custom' && validateDate($startDate) && validateDate($endDate)) {
    $start = $startDate;
    $end = $endDate;
} else {
    $start = date('Y-m-d', strtotime('-6 days', strtotime($today)));
    $end = $today;
}

// Chuẩn bị câu truy vấn
$whereClause = "WHERE donhang.ngaytao BETWEEN '$start 00:00:00' AND '$end 23:59:59'";
if ($categoryId > 0) {
    $whereClause .= " AND sanpham.danhmuc_id = $categoryId";
}

// 1. Tổng doanh thu, tổng đơn hàng, đánh giá trung bình
$summaryQuery = "
    SELECT 
        SUM(donhang.tienthucte) as totalRevenue,
        COUNT(DISTINCT donhang.id) as totalOrders,
        AVG(danhgia.diemso) as avgRating
    FROM donhang
    LEFT JOIN chitietdonhang ON donhang.id = chitietdonhang.donhang_id
    LEFT JOIN sanpham ON chitietdonhang.sanpham_id = sanpham.id
    LEFT JOIN danhgia ON sanpham.id = danhgia.sanpham_id
    $whereClause
";
$summaryResult = mysqli_query($conn, $summaryQuery);
$summary = mysqli_fetch_assoc($summaryResult);

// 2. Doanh thu theo ngày
$revenueQuery = "
    SELECT 
        DATE(donhang.ngaytao) as date,
        SUM(donhang.tienthucte) as revenue
    FROM donhang
    LEFT JOIN chitietdonhang ON donhang.id = chitietdonhang.donhang_id
    LEFT JOIN sanpham ON chitietdonhang.sanpham_id = sanpham.id
    $whereClause
    GROUP BY DATE(donhang.ngaytao)
    ORDER BY DATE(donhang.ngaytao)
";
$revenueResult = mysqli_query($conn, $revenueQuery);
$revenueLabels = [];
$revenueData = [];
while ($row = mysqli_fetch_assoc($revenueResult)) {
    $revenueLabels[] = date('d/m', strtotime($row['date']));
    $revenueData[] = (float)$row['revenue'];
}

// 3. Top sản phẩm bán chạy
$topProductsQuery = "
    SELECT 
        sanpham.ten,
        SUM(chitietdonhang.soluong) as totalSold
    FROM chitietdonhang
    JOIN sanpham ON chitietdonhang.sanpham_id = sanpham.id
    JOIN donhang ON chitietdonhang.donhang_id = donhang.id
    $whereClause
    GROUP BY sanpham.id
    ORDER BY totalSold DESC
    LIMIT 5
";
$topProductsResult = mysqli_query($conn, $topProductsQuery);
$topProductsLabels = [];
$topProductsData = [];
while ($row = mysqli_fetch_assoc($topProductsResult)) {
    $topProductsLabels[] = $row['ten'];
    $topProductsData[] = (int)$row['totalSold'];
}

// 4. Báo cáo chi tiết
$reportQuery = "
    SELECT 
        DATE(donhang.ngaytao) as date,
        SUM(donhang.tienthucte) as revenue,
        COUNT(DISTINCT donhang.id) as orders,
        SUM(chitietdonhang.soluong) as productsSold,
        AVG(danhgia.diemso) as avgRating
    FROM donhang
    LEFT JOIN chitietdonhang ON donhang.id = chitietdonhang.donhang_id
    LEFT JOIN sanpham ON chitietdonhang.sanpham_id = sanpham.id
    LEFT JOIN danhgia ON sanpham.id = danhgia.sanpham_id
    $whereClause
    GROUP BY DATE(donhang.ngaytao)
    ORDER BY DATE(donhang.ngaytao)
";
$reportResult = mysqli_query($conn, $reportQuery);
$report = [];
while ($row = mysqli_fetch_assoc($reportResult)) {
    $report[] = [
        'date' => date('d/m/Y', strtotime($row['date'])),
        'revenue' => (float)$row['revenue'],
        'orders' => (int)$row['orders'],
        'productsSold' => (int)$row['productsSold'],
        'avgRating' => $row['avgRating'] ? (float)$row['avgRating'] : 0
    ];
}

// Trả về dữ liệu JSON
echo json_encode([
    'summary' => [
        'totalRevenue' => $summary['totalRevenue'] ? (float)$summary['totalRevenue'] : 0,
        'totalOrders' => (int)$summary['totalOrders'],
        'avgRating' => $summary['avgRating'] ? (float)$summary['avgRating'] : 0
    ],
    'revenue' => [
        'labels' => $revenueLabels,
        'data' => $revenueData
    ],
    'topProducts' => [
        'labels' => $topProductsLabels,
        'data' => $topProductsData
    ],
    'report' => $report
]);

mysqli_close($conn);
?>