<?php
// Kết nối database
require_once '../../config/connect.php';

// Kiểm tra có ID đơn hàng được truyền vào không
if (!isset($_GET['id'])) {
    die('Không tìm thấy đơn hàng');
}

$order_id = $_GET['id'];

// Lấy thông tin đơn hàng
$stmt = $conn->prepare("SELECT * FROM donhang WHERE id = ? AND trangthai = 'hoanthanh'");
$stmt->bind_param('i', $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die('Đơn hàng không tồn tại hoặc chưa hoàn thành');
}

// Lấy chi tiết đơn hàng
$stmt = $conn->prepare("SELECT c.*, s.ten FROM chitietdonhang c JOIN sanpham s ON c.sanpham_id = s.id WHERE c.donhang_id = ?");
$stmt->bind_param('i', $order_id);
$stmt->execute();
$details = $stmt->get_result();

// Lấy thông tin mã giảm giá nếu có
$magiamgia = null;
if ($order['magiamgia_id']) {
    $stmt = $conn->prepare("SELECT code FROM magiamgia WHERE id = ?");
    $stmt->bind_param('i', $order['magiamgia_id']);
    $stmt->execute();
    $magiamgia = $stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #<?php echo $order_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.6;
            background-color: #f8f9fa;
        }
        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #3b82f6;
        }
        .company-info {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1a56db;
            margin: 20px 0;
        }
        .customer-info, .order-info {
            margin-bottom: 30px;
            padding: 20px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }
        .info-label {
            color: #4b5563;
            font-weight: 600;
            min-width: 150px;
            display: inline-block;
        }
        .table {
            border: 1px solid #e5e7eb;
        }
        .table th {
            background-color: #f3f4f6;
            color: #1f2937;
            font-weight: 600;
        }
        .table td, .table th {
            padding: 12px 15px;
            vertical-align: middle;
        }
        .total-info {
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #e5e7eb;
        }
        .total-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 1.1em;
            color: #1a56db;
        }
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }
        .signature-box {
            flex: 1;
            max-width: 200px;
            padding: 20px;
        }
        .signature-line {
            border-top: 1px dashed #000;
            margin-top: 50px;
        }
        .company-logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 20px;
        }
       
        @media print {
            .no-print { display: none; }
            body { background-color: white; }
            .invoice-container {
                box-shadow: none;
                margin: 0;
                padding: 0;
            }
            @page {
                margin: 1.5cm;
                size: A4;
            }
        }
    </style>
</head>
<body>
   \
    <div class="invoice-container">
        <div class="invoice-header">
            <h1 class="invoice-title">Hóa đơn bán hàng</h1>
            <p class="text-muted mb-0">Mã hóa đơn: #<?php echo $order_id; ?></p>
            <p class="text-muted">Ngày: <?php echo date('d/m/Y H:i', strtotime($order['ngaytao'])); ?></p>
        </div>

        <div class="row">
            <div class="col-md-6 company-info">
                <h4 class="mb-3">TECH LAPTOP</h4>
                <p><i class="bi bi-geo-alt-fill me-2"></i>Xuân La, Tây Hồ, Hà Nội</p>
                <p><i class="bi bi-telephone-fill me-2"></i>0969 859 400</p>
                <p><i class="bi bi-envelope-fill me-2"></i>sales@techlaptop.vn</p>
                <p><i class="bi bi-globe me-2"></i>www.techlaptop.vn</p>
            </div>
            <div class="col-md-6 customer-info">
                <h4 class="mb-3">Thông tin khách hàng</h4>
                <p><span class="info-label">Họ tên:</span> <?php echo htmlspecialchars($order['hoten']); ?></p>
                <p><span class="info-label">Địa chỉ:</span> <?php echo htmlspecialchars($order['diachi']); ?></p>
                <p><span class="info-label">Điện thoại:</span> <?php echo htmlspecialchars($order['sodienthoai']); ?></p>
                <p><span class="info-label">Email:</span> <?php echo htmlspecialchars($order['email']); ?></p>
            </div>
        </div>

        <div class="order-info mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 50px;">STT</th>
                        <th>Tên sản phẩm</th>
                        <th class="text-center" style="width: 100px;">Số lượng</th>
                        <th class="text-end" style="width: 150px;">Đơn giá</th>
                        <th class="text-end" style="width: 150px;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $stt = 1;
                    while ($detail = $details->fetch_assoc()): 
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $stt++; ?></td>
                        <td><?php echo htmlspecialchars($detail['ten']); ?></td>
                        <td class="text-center"><?php echo $detail['soluong']; ?></td>
                        <td class="text-end"><?php echo number_format($detail['gia'], 0, ',', '.') . ' ₫'; ?></td>
                        <td class="text-end"><?php echo number_format($detail['tong'], 0, ',', '.') . ' ₫'; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="total-info">
                <div class="total-row">
                    <span>Tổng tiền hàng:</span>
                    <span><?php echo number_format($order['tongtien'], 0, ',', '.') . ' ₫'; ?></span>
                </div>
                <div class="total-row">
                    <span>Phí vận chuyển:</span>
                    <span><?php echo number_format($order['phivanchuyen'], 0, ',', '.') . ' ₫'; ?></span>
                </div>
                <?php if ($order['tiengiamgia'] > 0): ?>
                <div class="total-row">
                    <span>Giảm giá:</span>
                    <span>-<?php echo number_format($order['tiengiamgia'], 0, ',', '.') . ' ₫'; ?></span>
                </div>
                <?php endif; ?>
                <div class="total-row">
                    <span>Tổng thanh toán:</span>
                    <span><?php echo number_format($order['tienthucte'], 0, ',', '.') . ' ₫'; ?></span>
                </div>
            </div>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <p>Người mua hàng</p>
                <div class="signature-line"></div>
                <p><em>(Ký, ghi rõ họ tên)</em></p>
            </div>
            <div class="signature-box">
                <p>Người bán hàng</p>
                <div class="signature-line"></div>
                <p><em>(Ký, ghi rõ họ tên)</em></p>
            </div>
        </div>

        <div class="text-center mt-4 mb-4 no-print">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer"></i> In hóa đơn
            </button>
            <button onclick="window.close()" class="btn btn-secondary ms-2">
                <i class="bi bi-x-circle"></i> Đóng
            </button>
        </div>
    </div>
</body>
</html>
